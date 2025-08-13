<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class NilaiAkhir extends Model
{
    protected $table = 'nilai_akhirs';

    protected $fillable = ['alternatif_id','total','peringkat','periode_id'];

    protected $casts = [
        'total' => 'float',
        'peringkat' => 'integer',
    ];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class,'alternatif_id');
    }

    public function scopePeriode(Builder $q, ?int $periodeId): Builder
    {
        if ($periodeId !== null && Schema::hasColumn($this->getTable(), 'periode_id')) {
            $q->where('periode_id', $periodeId);
        }
        return $q;
    }

    // Update method tanpa parameter User
    public static function hitungTotal(?int $periodeId = null): void
    {
        $hasPeriodeNA = Schema::hasColumn('nilai_akhirs', 'periode_id');
        $hasPeriodePN = Schema::hasColumn('penilaians', 'periode_id');

        $bobot = Kriteria::pluck('bobot_roc','id');

        $penQ = Penilaian::query();
        if ($periodeId !== null && $hasPeriodePN) {
            $penQ->where('periode_id', $periodeId);
        }

        $altIds = $penQ->select('alternatif_id')->distinct()->pluck('alternatif_id');
        if ($altIds->isEmpty()) return;

        DB::transaction(function () use ($altIds, $bobot, $periodeId, $hasPeriodeNA, $hasPeriodePN) {
            $del = static::query()->whereIn('alternatif_id', $altIds);
            if ($periodeId !== null && $hasPeriodeNA) $del->where('periode_id', $periodeId);
            $del->delete();

            foreach ($altIds as $altId) {
                $q = Penilaian::where('alternatif_id', $altId);
                if ($periodeId !== null && $hasPeriodePN) $q->where('periode_id', $periodeId);

                $items = $q->get(['kriteria_id','nilai_normal']);

                $total = 0.0;
                foreach ($items as $it) {
                    $total += (float)($bobot[$it->kriteria_id] ?? 0) * (float)($it->nilai_normal ?? 0);
                }

                $data = [
                    'alternatif_id' => $altId,
                    'total' => round($total, 6),
                ];
                if ($periodeId !== null && $hasPeriodeNA) $data['periode_id'] = $periodeId;

                static::create($data);
            }

            $rankQ = static::query()->whereIn('alternatif_id', $altIds);
            if ($periodeId !== null && $hasPeriodeNA) $rankQ->where('periode_id', $periodeId);

            $ranked = $rankQ->orderByDesc('total')->orderBy('alternatif_id')->get();
            $rank = 1;
            foreach ($ranked as $row) {
                $row->peringkat = $rank++;
                $row->save();
            }
        });
    }
}
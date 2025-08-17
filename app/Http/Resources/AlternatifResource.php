<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlternatifResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode_menu' => $this->kode_menu,       
            'nama_menu' => $this->nama_menu,       
            'jenis_menu' => $this->jenis_menu,     
            'harga' => $this->harga,
            'gambar' => $this->gambar,
            'harga_label' => $this->harga_label,   // Add accessor fields if needed
            'jenis_menu_label' => $this->jenis_menu_label,
            'gambar_url' => $this->gambar_url
        ];
    }
}
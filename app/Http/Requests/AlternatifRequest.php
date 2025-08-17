<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlternatifRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('alternatif')?->id ?? $this->id;
        
        return [
            'kode_menu' => [
                'required',
                'string',
                'max:30',
                Rule::unique('alternatifs', 'kode_menu')->ignore($id)
            ],
            'nama_menu' => [
                'required',
                'string',
                'max:100'
            ],
            'jenis_menu' => [
                'required',
                Rule::in(['makanan', 'cemilan', 'coffee', 'milkshake', 'mojito', 'yakult', 'tea'])
            ],
            'harga' => [
                'required',
                Rule::in(['<=20000', '>20000-<=25000', '>25000-<=30000', '>30000'])
            ],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048', // Max 2MB
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Validasi MIME type
                        $mimeType = $value->getMimeType();
                        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'])) {
                            $fail('File harus berupa gambar JPG, PNG, atau WebP.');
                        }
                        
                        // Validasi konten gambar
                        $imgInfo = @getimagesize($value->getRealPath());
                        if (!$imgInfo) {
                            $fail('File bukan gambar yang valid.');
                        }
                        
                        // Validasi dimensi minimal (optional)
                        if ($imgInfo[0] < 300 || $imgInfo[1] < 300) {
                            $fail('Gambar minimal berukuran 300x300 pixels.');
                        }
                    }
                }
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'kode_menu.required' => 'Kode menu wajib diisi.',
            'kode_menu.unique' => 'Kode menu sudah digunakan.',
            'kode_menu.max' => 'Kode menu maksimal 30 karakter.',
            
            'nama_menu.required' => 'Nama menu wajib diisi.',
            'nama_menu.max' => 'Nama menu maksimal 100 karakter.',
            
            'jenis_menu.required' => 'Jenis menu wajib dipilih.',
            'jenis_menu.in' => 'Jenis menu tidak valid.',
            
            'harga.required' => 'Kategori harga wajib dipilih.',
            'harga.in' => 'Kategori harga tidak valid.',
            
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'kode_menu' => 'Kode Menu',
            'nama_menu' => 'Nama Menu',
            'jenis_menu' => 'Jenis Menu',
            'harga' => 'Kategori Harga',
            'gambar' => 'Gambar Menu'
        ];
    }
}
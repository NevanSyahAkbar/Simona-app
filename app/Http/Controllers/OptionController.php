<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OptionController extends Controller
{
    /**
     * Menampilkan halaman/data pilihan.
     */
    public function index(Request $request)
    {
        $types = Option::select('type')->distinct()->pluck('type');
        $selectedType = $request->input('type', $types->first());

        // Mencegah error jika tidak ada data sama sekali
        if (!$selectedType) {
            $selectedType = 'general'; // default type
        }

        $options = Option::where('type', $selectedType)->latest()->paginate(10);
        $optionToEdit = $request->has('edit') ? Option::find($request->edit) : null;

        return view('pages.options.index', compact('types', 'selectedType', 'options', 'optionToEdit'));
    }

    /**
     * Menyimpan pilihan baru.
     */
    public function store(Request $request)
    {
        // Validasi awal untuk memastikan salah satu dari tipe diisi
        $request->validate([
            'value' => 'required|string|max:255',
            'type' => 'required_without:custom_type|string|max:255|nullable',
            'custom_type' => 'required_without:type|string|max:255|nullable',
        ], [
            'type.required_without' => 'Silakan pilih Tipe atau isi Tipe Baru.',
            'custom_type.required_without' => 'Silakan pilih Tipe atau isi Tipe Baru.',
        ]);

        // Tentukan tipe mana yang akan digunakan (input baru lebih prioritas)
        $typeToSave = $request->custom_type ?: $request->type;

        // Validasi kedua untuk memastikan data unik
        $request->validate([
            'value' => Rule::unique('options')->where('type', $typeToSave)
        ], [
            'value.unique' => 'Nama ini sudah ada untuk tipe tersebut.'
        ]);

        Option::create([
            'type' => $typeToSave,
            'value' => $request->value,
        ]);

        return redirect()->route('options.index', ['type' => $typeToSave])->with('success', 'Pilihan berhasil ditambahkan.');
    }

    /**
     * Memperbarui data pilihan yang ada.
     */
    public function update(Request $request, Option $option)
    {
        $request->validate([
            'value' => [
                'required', 'string', 'max:255',
                Rule::unique('options')->where(fn ($query) => $query->where('type', $option->type))->ignore($option->id)
            ],
        ]);

        $option->update($request->only('value'));

        return redirect()->route('options.index', ['type' => $option->type])->with('success', 'Pilihan berhasil diperbarui.');
    }

    /**
     * Menghapus pilihan dari database.
     */
    public function destroy(Option $option)
    {
        $type = $option->type;
        $option->delete();

        return redirect()->route('options.index', ['type' => $type])->with('success', 'Pilihan berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminBeritaController extends Controller
{
    public function index(): View
    {
        return view('admin.berita.index', [
            'beritas' => Berita::latest('tanggal_publikasi')->latest('id')->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['judul']);
        $data['gambar'] = $request->file('gambar')?->store('berita', 'public');

        Berita::create($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(Berita $berita): View
    {
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['judul'], $berita);
        $previousImage = $berita->gambar;
        $newImage = null;

        if ($request->hasFile('gambar')) {
            $newImage = $request->file('gambar')->store('berita', 'public');
            $data['gambar'] = $newImage;
        } else {
            unset($data['gambar']);
        }

        $berita->update($data);

        if ($newImage && $previousImage) {
            Storage::disk('public')->delete($previousImage);
        }

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:4096'],
            'ringkasan' => ['required', 'string', 'max:1000'],
            'isi' => ['required', 'string'],
            'tanggal_publikasi' => ['required', 'date'],
        ]);
    }

    private function uniqueSlug(string $judul, ?Berita $berita = null): string
    {
        $baseSlug = Str::slug($judul);
        $slug = $baseSlug;
        $suffix = 2;

        while (Berita::where('slug', $slug)
            ->when($berita, fn ($query) => $query->whereKeyNot($berita->getKey()))
            ->exists()) {
            $slug = $baseSlug.'-'.$suffix++;
        }

        return $slug;
    }
}

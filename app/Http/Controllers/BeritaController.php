<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $published = Berita::whereDate('tanggal_publikasi', '<=', today());
        $categories = (clone $published)->select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        $query = (clone $published)->latest('tanggal_publikasi')->latest('id');

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();
            $query->where(function ($builder) use ($search): void {
                $builder->where('judul', 'like', "%{$search}%")
                    ->orWhere('ringkasan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->string('kategori')->toString());
        }

        return view('frontend.berita.index', [
            'beritas' => $query->paginate(9)->withQueryString(),
            'categories' => $categories,
        ]);
    }

    public function show(string $slug): View
    {
        $berita = Berita::where('slug', $slug)
            ->whereDate('tanggal_publikasi', '<=', today())
            ->firstOrFail();

        return view('frontend.berita.show', compact('berita'));
    }
}

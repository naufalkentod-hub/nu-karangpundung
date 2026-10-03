<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Agenda;
use App\Models\Wisata;
use App\Models\Saran;

class PublicController extends Controller {
    public function index() {
        $stats = [
            'anggota' => Anggota::count(),
            'kegiatan' => Agenda::count(),
            'agenda' => Agenda::where('status', 'Akan Datang')->count(),
            'wisata' => Wisata::count()
        ];
        $agendas = Agenda::latest()->take(3)->get();
        $wisatas = Wisata::latest()->take(3)->get();
        return view('frontend.beranda', compact('stats', 'agendas', 'wisatas'));
    }

    public function tentang() {
        return view('frontend.tentang');
    }

    public function struktur() {
        $pembina = Anggota::where('jabatan', 'LIKE', '%Pembina%')->get();
        $ketua = Anggota::where('jabatan', 'LIKE', '%Ketua%')->get();
        $pengurusInti = Anggota::whereIn('jabatan', ['Wakil Ketua', 'Sekretaris', 'Bendahara'])->get();
        $departemen = Anggota::whereNotIn('id', $pembina->merge($ketua)->merge($pengurusInti)->pluck('id'))->get();
        return view('frontend.struktur', compact('pembina', 'ketua', 'pengurusInti', 'departemen'));
    }

    public function anggota(Request $request) {
        $query = Anggota::query();
        if ($request->filled('search')) {
            $query->where('nama', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('jabatan', 'LIKE', '%' . $request->search . '%');
        }
        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }
        $anggotas = $query->paginate(12)->withQueryString();
        $divisis = Anggota::select('divisi')->distinct()->pluck('divisi');
        return view('frontend.anggota', compact('anggotas', 'divisis'));
    }

    public function agenda() {
        $agendas = Agenda::latest()->paginate(6);
        return view('frontend.agenda', compact('agendas'));
    }

    public function agendaDetail(int $id) {
        $agenda = Agenda::findOrFail($id);
        return view('frontend.agenda-detail', compact('agenda'));
    }

    public function wisata() {
        $wisatas = Wisata::latest()->paginate(6);
        return view('frontend.wisata', compact('wisatas'));
    }

    public function wisataDetail(int $id) {
        $wisata = Wisata::findOrFail($id);
        return view('frontend.wisata-detail', compact('wisata'));
    }

    public function saranStore(Request $request) {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'kategori' => 'required',
            'isi' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('saran', 'public');
        }

        Saran::create($data);
        return back()->with('success', 'Suara dan saran Anda berhasil dikirim! Terima kasih.');
    }
}
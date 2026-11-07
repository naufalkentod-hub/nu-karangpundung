<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Agenda;
use App\Models\Wisata;
use App\Models\Saran;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller {
    public function loginForm() {
        if (session()->get('admin_authenticated', false)) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $adminUsername = config('admin.username');
        $adminPasswordHash = config('admin.password_hash');

        if (!is_string($adminUsername) || $adminUsername === '' || !is_string($adminPasswordHash) || $adminPasswordHash === '') {
            Log::error('Admin login is unavailable because ADMIN_USERNAME or ADMIN_PASSWORD_HASH is not configured.');

            return back()->withErrors([
                'username' => 'Login admin belum dikonfigurasi. Hubungi pengelola situs.',
            ])->onlyInput('username');
        }

        if (!hash_equals($adminUsername, $credentials['username']) || !Hash::check($credentials['password'], $adminPasswordHash)) {
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ])->onlyInput('username');
        }

        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request) {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard() {
        $stats = [
            'total_anggota' => Anggota::count(),
            'total_agenda' => Agenda::count(),
            'total_wisata' => Wisata::count(),
            'total_saran' => Saran::count(),
        ];
        $recent_sarans = Saran::latest()->take(5)->get();
        $upcoming_agendas = Agenda::where('status', 'Akan Datang')->take(5)->get();
        $totalAnggota = $stats['total_anggota'];
        $totalAgenda = $stats['total_agenda'];
        $totalWisata = $stats['total_wisata'];
        $totalSaran = $stats['total_saran'];
        $chartDataValues = [$totalAnggota, $totalAgenda, $totalWisata, $totalSaran];

        return view('admin.dashboard', compact(
            'stats', 'recent_sarans', 'upcoming_agendas',
            'totalAnggota', 'totalAgenda', 'totalWisata', 'totalSaran', 'chartDataValues'
        ));
    }

    public function anggotaIndex() {
        $anggotas = Anggota::latest()->paginate(10);
        return view('admin.anggota.index', compact('anggotas'));
    }

    public function anggotaStore(Request $request) {
        $data = $request->validate([
            'nama' => 'required', 'jabatan' => 'required', 'divisi' => 'required',
            'periode' => 'required', 'foto' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('anggota', 'public');
        }
        Anggota::create($data);
        return redirect()->back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function anggotaDestroy(Anggota $anggota) {
        if ($anggota->foto) Storage::disk('public')->delete($anggota->foto);
        $anggota->delete();
        return redirect()->back()->with('success', 'Anggota berhasil dihapus.');
    }

    public function agendaIndex() {
        $agendas = Agenda::latest()->paginate(10);
        return view('admin.agenda.index', compact('agendas'));
    }

    public function agendaStore(Request $request) {
        $data = $request->validate([
            'judul' => 'required', 'tanggal' => 'required|date', 'waktu' => 'required',
            'lokasi' => 'required', 'deskripsi' => 'required', 'status' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('agenda', 'public');
        }
        Agenda::create($data);
        return redirect()->back()->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function agendaDestroy(Agenda $agenda) {
        if ($agenda->foto) {
            Storage::disk('public')->delete($agenda->foto);
        }
        $agenda->delete();
        return redirect()->back()->with('success', 'Agenda berhasil dihapus.');
    }

    public function wisataIndex() {
        $wisatas = Wisata::latest()->paginate(10);
        return view('admin.wisata.index', compact('wisatas'));
    }

    public function wisataStore(Request $request) {
        $data = $request->validate([
            'nama' => 'required', 'lokasi' => 'required', 'deskripsi' => 'required',
            'fasilitas' => 'required', 'jam_buka' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('wisata', 'public');
        }
        Wisata::create($data);
        return redirect()->back()->with('success', 'Destinasi Wisata berhasil ditambahkan.');
    }

    public function wisataDestroy(Wisata $wisata) {
        if ($wisata->foto) Storage::disk('public')->delete($wisata->foto);
        $wisata->delete();
        return redirect()->back()->with('success', 'Wisata berhasil dihapus.');
    }

    public function saranIndex() {
        $sarans = Saran::latest()->paginate(10);
        return view('admin.saran.index', compact('sarans'));
    }

    public function saranDestroy(Saran $saran) {
        $saran->delete();
        return redirect()->back()->with('success', 'Saran berhasil dihapus.');
    }

    public function cetakIndex() {
        return view('admin.cetak.index');
    }

    public function cetakPreview(Request $request) {
        $type = $request->type;
        if ($type == 'anggota') {
            $data = Anggota::all();
            return view('admin.cetak.preview-anggota', compact('data'));
        } elseif ($type == 'agenda') {
            $data = Agenda::all();
            return view('admin.cetak.preview-agenda', compact('data'));
        }
        return back();
    }
}
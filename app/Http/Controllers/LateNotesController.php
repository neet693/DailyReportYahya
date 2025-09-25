<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\LateNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class LateNotesController extends Controller
{
    public function index()
    {
        // Redirect khusus jika role-nya 'pegawai'
        if (auth()->user()->role === 'pegawai') {
            return redirect()->route('keterlambatan.create');
        }

        $keterlambatan = LateNotes::latest()->get();

        return view('late_notes.index', compact('keterlambatan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('late_notes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_terlambat' => 'required|date',
            'alasan' => 'required|string',
            // salah satu wajib: foto (base64) atau foto_fallback (file upload)
        ]);

        $imagePath = null;

        // --- 1. Jika foto dari kamera (base64)
        if ($request->filled('foto')) {
            $data = $request->foto;

            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]);

                if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                    return back()->withErrors(['foto' => 'Format gambar tidak valid.'])->withInput();
                }

                $data = base64_decode($data);
                if ($data === false) {
                    return back()->withErrors(['foto' => 'Gagal decoding foto.'])->withInput();
                }

                $imageName = time() . '.' . $type;
                $path = public_path('bukti_keterlambatan');

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                file_put_contents($path . '/' . $imageName, $data);
                $imagePath = 'bukti_keterlambatan/' . $imageName;
            }

            // --- 2. Jika foto dari galeri / fallback input
        } elseif ($request->hasFile('foto_fallback')) {
            $file = $request->file('foto_fallback');
            $request->validate([
                'foto_fallback' => 'image|mimes:jpg,jpeg,png|max:2048'
            ]);

            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->move(public_path('bukti_keterlambatan'), $imageName);
            $imagePath = 'bukti_keterlambatan/' . $imageName;
        } else {
            return back()->withErrors(['foto' => 'Foto wajib diisi (kamera atau upload).'])->withInput();
        }

        // Cari absensi yang sesuai dengan user & tanggal
        $absensi = Absensi::where('user_id', auth()->id())
            ->whereDate('tanggal', $request->tanggal_terlambat)
            ->first();

        LateNotes::create([
            'user_id' => auth()->id(),
            'slug' => Str::slug(auth()->user()->name . '-' . now()->timestamp),
            'tanggal_terlambat' => $request->tanggal_terlambat,
            'alasan' => $request->alasan,
            'foto' => $imagePath,
            'absensi_id' => $absensi?->id,
        ]);

        return redirect()
            ->route('late_notes.index')
            ->with('success', 'Catatan keterlambatan berhasil dikirim.');
    }


    public function show($slug)
    {
        $keterlambatan = LateNotes::where('slug', $slug)->firstOrFail();
        return view('late_notes.show', compact('keterlambatan'));
    }

    public function edit(LateNotes $lateNotes)
    {
        //
    }


    public function update(Request $request, LateNotes $lateNotes)
    {
        //
    }

    public function destroy(LateNotes $lateNotes)
    {
        //
    }

    public function acc($slug)
    {
        $late = LateNotes::where('slug', $slug)->first();

        if (!$late) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $late->izin = true;
        $late->save();

        return response()->json([
            'success' => true,
            'message' => 'Izin berhasil di-ACC'
        ]);
    }
}

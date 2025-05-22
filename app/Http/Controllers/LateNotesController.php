<?php

namespace App\Http\Controllers;

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
            'waktu_terlambat' => 'required|date',
            'alasan' => 'required|string',
            'foto' => 'required|string', // base64 string dari kamera wajib ada
        ]);

        $imagePath = null;
        $data = $request->foto;

        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, etc.

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
        } else {
            return back()->withErrors(['foto' => 'Data foto tidak valid.'])->withInput();
        }

        LateNotes::create([
            'user_id' => auth()->id(),
            'slug' => Str::slug(auth()->user()->name . '-' . now()->timestamp),
            'waktu_terlambat' => $request->waktu_terlambat,
            'alasan' => $request->alasan,
            'foto' => $imagePath,
        ]);

        return redirect()->view('late_notes.index')->with('success', 'Catatan keterlambatan berhasil dikirim.');
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
}

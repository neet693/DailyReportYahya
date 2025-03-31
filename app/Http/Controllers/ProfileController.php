<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('auth.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'marital_status' => 'nullable',
            'birth_date' => 'nullable|date'
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|min:8|confirmed',
            ]);
            $user->password = bcrypt($request->input('password'));
        }

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('profile_images'), $imageName);
            $user->profile_image = $imageName;
        }

        // Simpan data yang baru diinput
        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->address = $request->input('address', $user->address);
        $user->gender = $request->input('gender', $user->gender);
        $user->birth_date = $request->input('birth_date', $user->birth_date);
        $user->marital_status = $request->input('marital_status', $user->marital_status);

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}

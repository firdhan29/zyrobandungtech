<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->avatar && File::exists(public_path('uploads/avatars/' . $user->avatar))) {
                File::delete(public_path('uploads/avatars/' . $user->avatar));
            }

            $image = $request->file('photo');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/avatars');
            
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            
            $image->move($destinationPath, $name);
            
            $user->avatar = $name;
            $user->save();
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}

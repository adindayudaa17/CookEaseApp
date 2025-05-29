<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    // Tampilkan form edit nama
    public function editName()
    {
        return view('edit-name');
    }

    // Proses update nama
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->save();

        return redirect()->route('settings')->with('success', 'Name updated successfully.');
    }
}

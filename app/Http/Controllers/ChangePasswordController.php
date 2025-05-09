<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    // Show form
    public function show()
    {
        return view('auth.passwords.change');
    }

    // Process form
    public function update(Request $request)
    {
        $user = $request->user();

        // Validate inputs
        $request->validate([
            'current_password'      => ['required'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check current password
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'This does not match your current password.',
            ]);
        }

        // Update to new password
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('status', 'Your password has been changed.');
    }
}

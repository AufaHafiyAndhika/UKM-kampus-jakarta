<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Exception;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nim' => ['required', 'string', 'max:20', 'unique:users', 'regex:/^[0-9]+$/'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users', 'ends_with:@student.telkomuniversity.ac.id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'gender' => ['required', 'in:male,female'],
            'faculty' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'batch' => ['required', 'string', 'size:4', 'regex:/^[0-9]{4}$/'],
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'nim.regex' => 'NIM harus berupa angka.',
            'email.ends_with' => 'Email harus menggunakan domain @student.telkomuniversity.ac.id',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'batch.size' => 'Angkatan harus 4 digit tahun.',
            'batch.regex' => 'Angkatan harus berupa tahun (contoh: 2024).',
        ]);

        $user = User::create([
            'nim' => $request->nim,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'gender' => $request->gender,
            'faculty' => $request->faculty,
            'major' => $request->major,
            'batch' => $request->batch,
            'role' => 'student',
            'status' => 'pending', // Changed from 'active' to 'pending'
        ]);

        event(new Registered($user));

        // Don't auto-login, redirect to success page instead
        try {
            return redirect()->route('register.success')->with([
                'user_name' => $user->name,
                'user_email' => $user->email
            ]);
        } catch (Exception $e) {
            // Fallback to backup route if main route fails
            return redirect()->route('register.success.backup')->with([
                'user_name' => $user->name,
                'user_email' => $user->email
            ]);
        }
    }

    /**
     * Show registration success page
     */
    public function success(): View
    {
        return view('auth.register-success');
    }
}

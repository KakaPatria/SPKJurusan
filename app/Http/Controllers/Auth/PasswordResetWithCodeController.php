<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;

class PasswordResetWithCodeController extends Controller
{
    public function resetWithCode(Request $request)
    {
        $data = $request->only(['email','token','password','password_confirmation']);

        $validator = Validator::make($data, [
            'email' => 'required|email',
            'token' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $data['email'];
        $code = $data['token'];

        $expiryMinutes = config('auth.passwords.users.expire', 60);
        $cutoff = Carbon::now()->subMinutes($expiryMinutes);

        $row = DB::table('password_reset_codes')
            ->where('email', $email)
            ->where('code', $code)
            ->where('created_at', '>=', $cutoff)
            ->first();

        if (! $row) {
            return back()->withErrors(['token' => 'Token tidak valid atau sudah kadaluarsa.'])->withInput();
        }

        $user = User::where('email', $email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'Akun dengan email ini tidak ditemukan.'])->withInput();
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        // remove used codes
        DB::table('password_reset_codes')->where('email', $email)->delete();

        return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan login dengan password baru.');
    }

    /**
     * Verify code for an email without changing password (used for two-step flow)
     */
    public function verifyCode(Request $request)
    {
        $data = $request->only(['email','token']);

        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'email' => 'required|email',
            'token' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $email = $data['email'];
        $code = $data['token'];

        $expiryMinutes = config('auth.passwords.users.expire', 60);
        $cutoff = Carbon::now()->subMinutes($expiryMinutes);

        $row = DB::table('password_reset_codes')
            ->where('email', $email)
            ->where('code', $code)
            ->where('created_at', '>=', $cutoff)
            ->first();

        if (! $row) {
            return response()->json(['ok' => false, 'message' => 'Token tidak valid atau sudah kadaluarsa.'], 404);
        }

        return response()->json(['ok' => true, 'message' => 'Token valid. Silakan masukkan password baru.']);
    }
}

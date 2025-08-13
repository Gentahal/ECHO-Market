<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register user baru
     */
    /**
     * Register user baru
     */
    public function register(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['nullable', 'string', 'in:superadmin'],
            ]);

            // Mulai database transaction
            DB::beginTransaction();

            // Buat user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'] ?? 'superadmin', // Default ke 'user' bukan 'superadmin'
            ]);

            // Buat token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Commit transaction jika semua berhasil
            DB::commit();

            return response()->json([
                'message' => 'Registrasi berhasil',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user->makeHidden(['password']) // Sembunyikan password dari response
            ], 201);
        } catch (ValidationException $e) {
            // Tangkap error validasi
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (QueryException $e) {
            // Rollback transaction jika ada error database
            DB::rollBack();

            // Cek jika error karena duplikat email
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return response()->json([
                    'message' => 'Email sudah terdaftar',
                    'errors' => ['email' => ['Email ini sudah digunakan']]
                ], 422);
            }

            return response()->json([
                'message' => 'Terjadi kesalahan database',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        } catch (\Exception $e) {
            // Rollback transaction jika ada error lain
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Hapus token lama jika ada (opsional)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    /**
     * Ambil data user login
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}

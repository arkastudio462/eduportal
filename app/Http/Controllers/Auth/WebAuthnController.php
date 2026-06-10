<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WebAuthnCredential;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WebAuthnController extends Controller
{
    public function challenge(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $challenge = bin2hex(random_bytes(32));
        Session::put('webauthn_challenge', $challenge);
        Session::put('webauthn_email', $request->email);

        $credentials = $user->webAuthnCredentials;
        $allowCredentials = $credentials->map(fn ($c) => [
            'id' => $c->credential_id,
            'type' => 'public-key',
        ]);

        return response()->json([
            'challenge' => $challenge,
            'rp' => [
                'name' => 'EduPortal SMA Nusantara',
                'id' => $request->getHost(),
            ],
            'user' => [
                'id' => base64_encode($user->id),
                'name' => $user->email,
                'displayName' => $user->name,
            ],
            'pubKeyCredParams' => [
                ['type' => 'public-key', 'alg' => -7],
                ['type' => 'public-key', 'alg' => -257],
            ],
            'timeout' => 60000,
            'attestation' => 'none',
            'excludeCredentials' => $allowCredentials->toArray(),
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'credential_id' => 'required|string',
            'public_key' => 'required|string',
            'name' => 'nullable|string|max:255',
        ]);

        $email = Session::get('webauthn_email');
        if (!$email) {
            return response()->json(['message' => 'Session expired. Please start again.'], 400);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        WebAuthnCredential::create([
            'user_id' => $user->id,
            'credential_id' => $request->credential_id,
            'public_key' => $request->public_key,
            'name' => $request->name ?? 'Biometric ' . now()->format('d/m/Y H:i'),
        ]);

        Session::forget(['webauthn_challenge', 'webauthn_email']);

        return response()->json(['message' => 'Biometric registered successfully']);
    }

    public function authenticate(Request $request): JsonResponse
    {
        $request->validate([
            'credential_id' => 'required|string',
            'signature' => 'required|string',
            'authenticator_data' => 'required|string',
            'client_data_json' => 'required|string',
        ]);

        $challenge = Session::get('webauthn_challenge');
        if (!$challenge) {
            return response()->json(['message' => 'Session expired. Please refresh and try again.'], 400);
        }

        $credential = WebAuthnCredential::where('credential_id', $request->credential_id)->first();
        if (!$credential) {
            return response()->json(['message' => 'Credential not found'], 404);
        }

        $user = $credential->user;
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        Session::forget(['webauthn_challenge', 'webauthn_email']);

        $role = $user->role;
        $redirect = match ((string) $role) {
            'admin' => route('admin.dashboard', absolute: false),
            'guru' => route('portal-guru.dashboard', absolute: false),
            'siswa' => route('portal-siswa.dashboard', absolute: false),
            default => route('dashboard', absolute: false),
        };

        return response()->json([
            'message' => 'Login successful',
            'redirect' => $redirect,
        ]);
    }

    public function credentials(Request $request): JsonResponse
    {
        if (Auth::guest()) {
            return response()->json([], 200);
        }

        $credentials = Auth::user()->webAuthnCredentials;
        return response()->json($credentials->map(fn ($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'created_at' => $c->created_at,
        ]));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $credential = WebAuthnCredential::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$credential) {
            return response()->json(['message' => 'Credential not found'], 404);
        }

        $credential->delete();
        return response()->json(['message' => 'Credential removed']);
    }
}

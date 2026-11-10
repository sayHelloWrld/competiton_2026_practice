<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\PlaintextTokenResource;
use App\Http\Resources\TokenCollection;
use App\Http\Resources\TokenResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private function generateApiKeyName(string $userAgent) {
        $uuid = (string) Str::uuid();

        return "$userAgent - $uuid";
    }

    public function signup(Request $request) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string'
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ]
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        $token = $user->createToken($this::generateApiKeyName($request->userAgent() ?? 'Unkown User Agent'))->plainTextToken;

        return new PlaintextTokenResource($token);
    }

    public function login(Request $request) {
        if (RateLimiter::tooManyAttempts('login:' . $request->ip(), 5)) {
            $seconds = RateLimiter::availableIn('login:' . $request->ip());

            return new ErrorResource([
                "message" => "Too many failed login attempts. Try again in $seconds seconds",
                "errors" => [
                    'password' => ['incorrect password']
                ]
            ])
                ->response()
                ->setStatusCode(429);
        }

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email'
            ],
            'password' => [
                'required',
                'string'
            ]
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!Hash::check($validated['password'], $user->password)) {
            RateLimiter::increment('login:' . $request->ip(), 60);

            return new ErrorResource(['message' => 'Incorrect password.'])
                ->response()
                ->setStatusCode(403);
        }

        $token = $user->createToken($this::generateApiKeyName($request->userAgent() ?? 'Unkown User Agent'))->plainTextToken;

        return new PlaintextTokenResource($token);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response(status: 204);
    }

    public function logoutAll(Request $request) {
        $request->user()->tokens()->delete();

        return response(status: 204);
    }

    public function userDetails(Request $request) {
        return $request->user()->toResource();
    }

    public function getTokens(Request $request) {
        return new TokenCollection($request->user()->tokens);
    }

    public function deleteToken(Request $request, string $id) {
        $deleted = $request->user()->tokens()->where('id', $id)->delete();

        if(!$deleted) {
            return response(['message' => 'Token not found or already revoked.'], 404);
        }

        return response(status: 204);
    }
}

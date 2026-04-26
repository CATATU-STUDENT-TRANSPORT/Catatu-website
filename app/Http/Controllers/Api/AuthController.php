<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:64', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['nullable', Rule::in([User::ROLE_STUDENT])],
        ]);

        $data['role'] = User::ROLE_STUDENT;
        $data['student_verification_status'] = $this->looksLikeStudentEmail($data['email'])
            ? 'pending'
            : 'not_required';

        $user = User::create($data);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('api')->plainTextToken,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required_without:username', 'nullable', 'email'],
            'username' => ['required_without:email', 'nullable', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->when($data['email'] ?? null, fn ($q, $email) => $q->where('email', $email))
            ->when($data['username'] ?? null, fn ($q, $username) => $q->where('username', $username))
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('api')->plainTextToken,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()]);
    }

    protected function looksLikeStudentEmail(string $email): bool
    {
        foreach (config('catatu.booking.student_email_domain_suffixes', []) as $suffix) {
            if (str_ends_with(strtolower($email), strtolower(trim($suffix)))) {
                return true;
            }
        }

        return false;
    }
}

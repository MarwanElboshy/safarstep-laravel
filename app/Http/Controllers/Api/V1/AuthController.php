<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function checkEmail(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $exists = User::where('email', $data['email'])->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'exists' => $exists,
            ],
        ]);
    }

    public function validateCredentials(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->with('tenant')->first();
        if (!$user || !Hash::check($data['password'], (string) $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 422);
        }

        $tenants = [];
        if ($user->tenant) {
            $tenants[] = [
                'id' => $user->tenant->id,
                'name' => $user->tenant->name,
                'description' => $user->tenant->slug,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'tenants' => $tenants,
            ],
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'tenant_id' => ['nullable'],
            'device_name' => ['sometimes', 'string'],
        ]);

        $user = User::where('email', $data['email'])->with('tenant')->first();
        if (!$user || !Hash::check($data['password'], (string) $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 422);
        }

        // Verify tenant_id if provided matches user's tenant
        if (isset($data['tenant_id']) && $user->tenant_id !== $data['tenant_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid tenant selection.',
            ], 422);
        }

        $token = $user->createToken($data['device_name'] ?? 'api')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'tenant_id' => $user->tenant_id,
                ],
                'tenant' => $user->tenant ? [
                    'id' => $user->tenant->id,
                    'name' => $user->tenant->name,
                    'slug' => $user->tenant->slug,
                    'primary_color' => $user->tenant->primary_color,
                    'secondary_color' => $user->tenant->secondary_color,
                ] : null,
                'permissions' => [],
            ],
        ], 200);
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'tenant_id' => ['sometimes', 'string'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'tenant_id' => $data['tenant_id'] ?? null,
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ],
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()?->currentAccessToken();
        if ($token) {
            $token->delete();
        }
        return response()->json(['success' => true, 'message' => 'Logged out']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['success' => true, 'data' => ['user' => $request->user()]], 200);
    }
}

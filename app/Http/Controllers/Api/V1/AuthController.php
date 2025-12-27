<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class AuthController extends Controller
{
    public function checkEmail(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'email' => ['required', 'email'],
            ]);

            $email = trim(strtolower($data['email']));
            $exists = User::where('email', $email)->exists();

            return response()->json([
                'success' => true,
                'data' => ['exists' => $exists],
            ], 200);
        } catch (\Throwable $e) {
            \Log::error('Auth checkEmail error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to check email',
            ], 422);
        }
    }

    public function validateCredentials(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            $email = trim(strtolower($data['email']));
            $user = User::where('email', $email)->with('tenant')->first();

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
                'data' => ['tenants' => $tenants],
            ], 200);
        } catch (\Throwable $e) {
            \Log::error('Auth validateCredentials error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
            ], 422);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
                'tenant_id' => ['nullable'],
                'device_name' => ['sometimes', 'string'],
            ]);

            $email = trim(strtolower($data['email']));
            $user = User::where('email', $email)->with('tenant')->first();

            if (!$user || !Hash::check($data['password'], (string) $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials.',
                ], 422);
            }

            if (isset($data['tenant_id']) && $user->tenant_id !== $data['tenant_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid tenant selection.',
                ], 422);
            }

            // Establish session-based login for dashboard access
            Auth::login($user);
            $request->session()->regenerate();
            if ($user->tenant_id) {
                $request->session()->put('tenant_id', (string) $user->tenant_id);
            }

            // Set permission team for current tenant
            app(PermissionRegistrar::class)->setPermissionsTeamId($user->tenant_id);

            // Track last login timestamp if column exists (prevents 422 on environments not yet migrated)
            try {
                if (Schema::hasColumn('users', 'last_login_at')) {
                    $user->last_login_at = now();
                    $user->save();
                }
            } catch (\Throwable $e) {
                \Log::warning('Skipping last_login_at update', ['error' => $e->getMessage()]);
            }

            // Create API token for SPA/API calls
            $token = $user->createToken($data['device_name'] ?? 'api')->plainTextToken;
            $permissions = $user->getAllPermissions()->pluck('name');

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
                    'roles' => $user->roles->pluck('name'),
                    'permissions' => $permissions,
                ],
            ], 200);
        } catch (\Throwable $e) {
            \Log::error('Auth login error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
            ], 422);
        }
    }

    public function register(Request $request): JsonResponse
    {
        // Basic placeholder registration; adjust per business rules if needed
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = trim(strtolower($data['email']));
        $user->password = bcrypt($data['password']);
        $user->save();

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
        if ($token && method_exists($token, 'delete')) {
            $token->delete();
        }

        // Also end any existing web session to fully log out from dashboard
        try {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } catch (\Throwable $e) {
            // ignore if session is not available in this context
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out',
        ], 200);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => ['user' => $request->user()],
        ], 200);
    }
}

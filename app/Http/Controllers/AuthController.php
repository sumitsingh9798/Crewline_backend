<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Register Employee / Freelancer / Client
     *
     * Admin registration is NOT allowed here.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'company' => [
                'nullable',
                'string',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in([
                    'employee',
                    'freelancer',
                    'client',
                ]),
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'company' => $validated['company'] ?? null,

            // User model has 'hashed' cast
            'password' => $validated['password'],

            // Only employee/freelancer/client allowed
            'role' => $validated['role'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'company' => $user->company,
                'role' => $user->role,
            ],
        ], 201);
    }


    /**
     * Secure Admin Registration
     *
     * Only authenticated Admin can create another Admin.
     */
    public function registerAdmin(Request $request)
    {
        $currentUser = $request->user();

        if (!$currentUser || $currentUser->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only an admin can create another admin.',
            ], 403);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],

            // Admin role controlled by backend
            'role' => 'admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin registration successful',

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 201);
    }


    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        $user = User::where(
            'email',
            $validated['email']
        )->first();

        if (
            !$user ||
            !Hash::check(
                $validated['password'],
                $user->password
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        // Create Sanctum token
        $token = $user
            ->createToken('crewline-token')
            ->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',

            'token' => $token,

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'company' => $user->company,
                'role' => $user->role,
            ],
        ], 200);
    }


    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'company' => $user->company,
                'role' => $user->role,
            ],
        ], 200);
    }
}
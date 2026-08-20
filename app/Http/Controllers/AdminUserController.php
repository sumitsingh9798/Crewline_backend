<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * GET ALL USERS
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->select([
                'id',
                'name',
                'email',
                'phone',
                'company',
                'role',
                'created_at',
            ])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }


    /**
     * CREATE USER
     */
    public function store(Request $request)
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
                    'admin',
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
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);


        return response()->json([
            'success' => true,

            'message' => 'User created successfully.',

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
     * UPDATE USER
     */
    public function update(
        Request $request,
        User $user
    ) {

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
                Rule::unique('users', 'email')
                    ->ignore($user->id),
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

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'employee',
                    'freelancer',
                    'client',
                ]),
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);


        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->company = $validated['company'] ?? null;
        $user->role = $validated['role'];


        if (
            !empty($validated['password'])
        ) {
            $user->password =
                $validated['password'];
        }


        $user->save();


        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
        ]);
    }


    /**
     * DELETE USER
     */
    public function destroy(
        Request $request,
        User $user
    ) {

        /*
        |--------------------------------------------------------------------------
        | Prevent admin deleting himself
        |--------------------------------------------------------------------------
        */

        if (
            $request->user()->id === $user->id
        ) {

            return response()->json([
                'success' => false,
                'message' =>
                    'You cannot delete your own account.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Delete tokens
        |--------------------------------------------------------------------------
        */

        $user->tokens()->delete();


        /*
        |--------------------------------------------------------------------------
        | Delete user
        |--------------------------------------------------------------------------
        */

        $user->delete();


        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
        ]);
    }
}
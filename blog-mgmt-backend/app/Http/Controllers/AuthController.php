<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the incoming request data.
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user with the hashed password.
        $user = User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Generate a new token for the user.
        $token = $user->createToken('api-token')->plainTextToken;

        // Return the token along with the user details.
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ], 201);
    }
    
    public function login(Request $request)
    {
        // Validate the incoming request.
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Retrieve the user by email.
    
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and if the password is correct.
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create a new token for the user.
        $token = $user->createToken('api-token')->plainTextToken;

        // Return the token in the response.
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }

    /**
     * Logout the user by deleting the current access token.
     */
    public function logout(Request $request)
    {
        // Delete the token that was used to authenticate the current request.
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        // Ensure roles and permissions are loaded
        $user->load('roles', 'permissions');
        $permissions = $user->roles->flatMap(function ($role) {
            return $role->permissions->pluck('name');
        })->unique();
        return response()->json([
            'id'          => $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'roles'       => $user->roles->pluck('name'),       // returns an array of role names
            'permissions' => $permissions,   // returns an array of permission names
        ]);
    }

}

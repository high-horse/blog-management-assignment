<?php

namespace App\Http\Controllers;

use App\Traits\HandleErrorTrait;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use HandleErrorTrait;
    public function register(Request $request)
    {
        try {
            // Validate the incoming request data.
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Create the user with the hashed password.
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Generate a new token for the user.
            $token = $user->createToken('api-token')->plainTextToken;

            // Return the token along with the user details.
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 201);
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }

    }

    public function login(Request $request)
    {
        try {
            // Validate the incoming request.
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Retrieve the user by email.

            $user = User::where('email', $request->email)->first();

            // Check if the user exists and if the password is correct.
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw new Exception('Invalid Credentials');
            }

            // Create a new token for the user.
            $token = $user->createToken('api-token')->plainTextToken;

            // Return the token in the response.
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }

    /**
     * Logout the user by deleting the current access token.
     */
    public function logout(Request $request)
    {
        try {
            // Delete the token that was used to authenticate the current request.
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }

    }

    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            // Ensure roles and permissions are loaded
            $user->load('roles', 'permissions');
            $permissions = $user->roles->flatMap(function ($role) {
                return $role->permissions->pluck('name');
            })->unique();
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),       // returns an array of role names
                'permissions' => $permissions,   // returns an array of permission names
            ]);
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }

}

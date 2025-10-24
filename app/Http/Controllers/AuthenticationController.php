<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        try {
            Log::info('Register request received.', ['request' => $request->all()]);

            $attr = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = User::create([
                'name' => $attr['name'],
                'password' => bcrypt($attr['password']),
                'email' => $attr['email'],
                'role' => 'member'
            ]);

            Log::info('User registered successfully.', ['user_id' => $user->id]);

            return response()->json(['message' => 'Registration successful'], 200);
        } catch (\Exception $e) {
            Log::error('Error registering user: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Registration failed'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            Log::info('Login request received.', ['request' => $request->all()]);

            $attr = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|min:6',
                'role' => 'nullable|string|in:admin,member'
            ]);

            if (!Auth::attempt($attr)) {
                Log::warning('Login failed: credentials do not match.', ['email' => $request->email]);

                return response()->json(['message' => 'Credentials not match'], 401);
            }

            $response = [
                'access_token' => auth()->user()->createToken('API Token')->plainTextToken,
                'token_type' => 'Bearer',
                'user' => auth()->user()->only(['name', 'email'])
            ];

            Log::info('User logged in successfully.', ['user_id' => auth()->id()]);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::error('Error during login: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Login failed'], 500);
        }
    }

    public function logout()
    {
        try {
            Log::info('Logout request received.', ['user_id' => auth()->id()]);

            auth()->user()->tokens()->delete();

            Log::info('User logged out successfully.', ['user_id' => auth()->id()]);

            return response()->json(['message' => 'Tokens Revoked'], 200);
        } catch (\Exception $e) {
            Log::error('Error during logout: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Logout failed'], 500);
        }
    }

    public function showUsers()
    {
        if (Auth::user()->role !== 'admin') {
            Log::warning('Unauthorized attempt to create exercise by user ID: ' . Auth::id());
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            Log::info('Fetching all users.');

            $users = User::all();

            Log::info('Users fetched successfully.', ['user_count' => $users->count()]);

            return response()->json($users, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Failed to fetch users'], 500);
        }
    }

    public function deleteUser($id)
    {
        if (Auth::user()->role !== 'admin') {
            Log::warning('Unauthorized attempt to create exercise by user ID: ' . Auth::id());
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            Log::info('Delete user request received.', ['user_id' => $id]);

            $user = User::find($id);

            if (!$user) {
                Log::warning('User not found.', ['user_id' => $id]);

                return response()->json(['error' => 'User not found'], 404);
            }

            $user->achievements()->delete();
            $user->delete();

            Log::info('User and associated records deleted successfully.', ['user_id' => $id]);

            return response()->json(['success' => 'User and associated records deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting user and associated records: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            Log::warning('Unauthorized attempt to create exercise by user ID: ' . Auth::id());
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            Log::info('Update user request received.', ['user_id' => $id, 'request' => $request->all()]);

            $user = User::find($id);

            if (!$user) {
                Log::warning('User not found.', ['user_id' => $id]);

                return response()->json(['error' => 'User not found'], 404);
            }

            Log::info('User found.', ['user' => $user->toArray()]);

            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
                'role' => [
                    'sometimes',
                    'string',
                    'max:255',
                    \Illuminate\Validation\Rule::in(['admin', 'member']),
                ],
            ]);

            Log::info('Validated data.', ['validated_data' => $validatedData]);

            $user->update($validatedData);

            if ($user->isDirty()) {
                $user->save();
                Log::info('User updated and saved.', ['user' => $user->toArray()]);
            } else {
                Log::info('No changes detected for the user.', ['user' => $user->toArray()]);
            }

            return response()->json(['success' => 'User updated successfully'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . $e->getMessage(), ['validation_errors' => $e->errors()]);

            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
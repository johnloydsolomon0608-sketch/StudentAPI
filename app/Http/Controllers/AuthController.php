<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100', 'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $data['password'] = Hash::make($data['password']);
        return $this->issueToken(User::create($data), 'User registered successfully.', 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
        }
        return $this->issueToken($user, 'Login successful.', 200);
    }

    private function issueToken(User $user, string $message, int $status)
    {
        return response()->json(['message' => $message, 'token_type' => 'Bearer',
            'access_token' => $user->createToken('employee-api')->plainTextToken, 'user' => $user], $status);
    }

    public function profile(Request $request)
    {
        return ['user' => $request->user()];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ['message' => 'Logged out successfully.'];
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return ['message' => 'Logged out from all devices.'];
    }
}

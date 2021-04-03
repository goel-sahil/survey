<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Login the user into the system
     *
     * @param Request $request
     * @return void
     */
    function login(Request $request)
    {
        $user = User::where('username', $request->input('username'))->first();

        if (!$user) {
            return response()->json(['message' => 'These credentials do not match our records.!'], 400);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'These credentials do not match our records.!'], 400);
        }

        if ($user->Status == 0) {
            return response()->json(['message' => 'Your account is not verified.!'], 400);
        }

        $token = auth()->attempt($request->only('username', 'password'));
        if ($token) {
            return $this->respondWithToken($token);
        }
        return response()->json(['message' => 'These credentials do not match our records.!'], 400);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = auth()->user();
        $user->load(['ulb', 'district_relation']);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ], 200);
    }
}

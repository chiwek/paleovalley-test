<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SanctumLoginController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $authSuccess = Auth::attempt($request->only('email', 'password'));
        if (!$authSuccess) {
            return response()->json([
                'message' => 'Invalid login attempt'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['accessToken' => $token]);
    }
}

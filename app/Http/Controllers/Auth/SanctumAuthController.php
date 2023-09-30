<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class SanctumAuthController extends Controller
{

    public function __invoke(Request $request): JsonResponse
    {
        $requestErrors = $this->getProductPostRequestErrors($request);
        if ($requestErrors) {
            return $requestErrors;
        }

        $user = User::factory()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['accessToken' => $token]);
    }


    private function getProductPostRequestErrors(Request $request): ?JsonResponse
    {
        $validateData = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ];
        try {
            $this->validate($request, $validateData);
        } catch (ValidationException $ex) {
            return Response::json(['errors' => $ex->errors()], HttpResponse::HTTP_BAD_REQUEST);
        }

        return null;
    }
}

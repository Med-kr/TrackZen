<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        $token = $user->createToken('api_token')->plainTextToken;

        return $this->okResponse([
            'user' => $user,
            'token' => $token,
        ], 'Compte créé avec succès', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->failResponse([
                'email' => ['Email ou mot de passe invalide'],
            ], 'Identifiants incorrects', 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return $this->okResponse([
            'user' => $user,
            'token' => $token,
        ], 'Authentification réussie');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->okResponse(null, 'Déconnexion réussie');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->okResponse($request->user(), 'Informations utilisateur');
    }
}

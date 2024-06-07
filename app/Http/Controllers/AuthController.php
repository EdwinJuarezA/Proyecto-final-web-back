<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/login",
 *     summary="Iniciar sesión",
 *     tags={"Autenticación"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="password", type="string", example="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Token de autenticación",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string"),
 *             @OA\Property(property="user", ref="#/components/schemas/User")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Credenciales inválidas"
 *     )
 * )
 */

    public function login(Request $request){
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)){
            return $this->handleLogin($request);
        }

        return response()->json(['message'=>'Invalid credentials',401]);
    }

    private function handleLogin(Request $request){
        $user = auth()->user();  // Asegúrate de obtener el usuario autenticado
        $token = $request->user()->createToken('auth_token');
    
        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user,
        ]);
    }
    
}

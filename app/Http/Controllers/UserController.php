<?php

namespace App\Http\Controllers;

use App\Models\User;
use Faker\Guesser\Name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(
 *     title="Documentación de la API de Desastres",
 *     version="1.0.0",
 *     description="Esta es la documentación de la API del proyecto Desastres."
 * )
 */
class UserController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/users",
 *     summary="Listar todos los usuarios",
 *     tags={"Usuarios"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de usuarios",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
 *     )
 * )
 */
    public function index()
    {
        $users=User::all();
        return response()->json($users);
    }

/**
 * @OA\Post(
 *     path="/api/users",
 *     summary="Crear un nuevo usuario",
 *     tags={"Usuarios"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", example="john@example.com"),
 *             @OA\Property(property="password", type="string", example="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Usuario creado",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     )
 * )
 */
    public function store(Request $request)
    {
        $user=User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' =>Hash::make($request->input('password')),
        ]);

        return response()->json($user);
    }

/**
 * @OA\Get(
 *     path="/api/users/{id}",
 *     summary="Mostrar un usuario específico",
 *     tags={"Usuarios"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalles del usuario",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usuario no encontrado"
 *     )
 * )
 */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
        //return response()->json($user);
    }

/**
 * @OA\Put(
 *     path="/api/users/{id}",
 *     summary="Actualizar un usuario",
 *     tags={"Usuarios"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="John Doe Actualizado"),
 *             @OA\Property(property="email", type="string", example="john.updated@example.com"),
 *             @OA\Property(property="password", type="string", example="newpassword")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usuario actualizado",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     )
 * )
 */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' =>Hash::make($request->input('password')),
        ]);
        return response()->json($user);
    }

/**
 * @OA\Delete(
 *     path="/api/users/{id}",
 *     summary="Eliminar un usuario",
 *     tags={"Usuarios"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usuario eliminado",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Se ha eliminado el usuario correctamente"))
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usuario no encontrado"
 *     )
 * )
 */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'Se ha eliminado el usuario correctamente']);
    }
}

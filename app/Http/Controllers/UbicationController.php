<?php

namespace App\Http\Controllers;

use App\Models\Ubication;
use Illuminate\Http\Request;

class UbicationController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/ubications",
 *     summary="Listar todas las ubicaciones",
 *     tags={"Ubicaciones"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de ubicaciones",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Ubication"))
 *     )
 * )
 */
    public function index()
    {
        $ubications = Ubication::all();
        return response()->json($ubications);
    }

/**
 * @OA\Post(
 *     path="/api/ubications",
 *     summary="Crear una nueva ubicación",
 *     tags={"Ubicaciones"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "latitude", "longitude"},
 *             @OA\Property(property="name", type="string", example="Parque Central"),
 *             @OA\Property(property="description", type="string", example="Un hermoso parque en el centro de la ciudad."),
 *             @OA\Property(property="latitude", type="number", format="float", example=20.6700),
 *             @OA\Property(property="longitude", type="number", format="float", example=-103.3500),
 *             @OA\Property(property="address", type="string", example="Calle Principal #123"),
 *             @OA\Property(property="city", type="string", example="Ciudad de México"),
 *             @OA\Property(property="state", type="string", example="CDMX"),
 *             @OA\Property(property="country", type="string", example="México"),
 *             @OA\Property(property="postal_code", type="string", example="01000")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Ubicación creada",
 *         @OA\JsonContent(ref="#/components/schemas/Ubication")
 *     )
 * )
 */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $ubication = Ubication::create($validatedData);

        return response()->json($ubication, 201);
    }

/**
 * @OA\Get(
 *     path="/api/ubications/{id}",
 *     summary="Mostrar una ubicación específica",
 *     tags={"Ubicaciones"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalles de la ubicación",
 *         @OA\JsonContent(ref="#/components/schemas/Ubication")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Ubicación no encontrada"
 *     )
 * )
 */
    public function show(string $id)
    {
        $ubication = Ubication::findOrFail($id);
        return response()->json($ubication);
    }

/**
 * @OA\Put(
 *     path="/api/ubications/{id}",
 *     summary="Actualizar una ubicación",
 *     tags={"Ubicaciones"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "latitude", "longitude"},
 *             @OA\Property(property="name", type="string", example="Parque Central Actualizado"),
 *             @OA\Property(property="description", type="string", example="Un hermoso parque actualizado en el centro de la ciudad."),
 *             @OA\Property(property="latitude", type="number", format="float", example=20.6700),
 *             @OA\Property(property="longitude", type="number", format="float", example=-103.3500),
 *             @OA\Property(property="address", type="string", example="Calle Principal #123"),
 *             @OA\Property(property="city", type="string", example="Ciudad de México"),
 *             @OA\Property(property="state", type="string", example="CDMX"),
 *             @OA\Property(property="country", type="string", example="México"),
 *             @OA\Property(property="postal_code", type="string", example="01000")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Ubicación actualizada",
 *         @OA\JsonContent(ref="#/components/schemas/Ubication")
 *     )
 * )
 */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $ubication = Ubication::findOrFail($id);
        $ubication->update($validatedData);

        return response()->json($ubication);
    }

/**
 * @OA\Delete(
 *     path="/api/ubications/{id}",
 *     summary="Eliminar una ubicación",
 *     tags={"Ubicaciones"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Ubicación eliminada",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Se ha eliminado la ubicación correctamente"))
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Ubicación no encontrada"
 *     )
 * )
 */
    public function destroy(string $id)
    {
        $ubication = Ubication::findOrFail($id);
        $ubication->delete();

        return response()->json(null, 204);
    }
}

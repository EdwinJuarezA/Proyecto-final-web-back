<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/images",
 *     summary="Listar todas las imágenes",
 *     tags={"Imágenes"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de imágenes",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Image"))
 *     )
 * )
 */
    public function index()
    {
        $images=Image::all();
        return response()->json($images);
    }

/**
 * @OA\Post(
 *     path="/api/images",
 *     summary="Subir una nueva imagen",
 *     tags={"Imágenes"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"url", "note_id"},
 *             @OA\Property(property="url", type="string", example="images/image.jpg"),
 *             @OA\Property(property="note_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Imagen creada",
 *         @OA\JsonContent(ref="#/components/schemas/Image")
 *     )
 * )
 */
    public function store(Request $request)
    {
        $image=Image::create([
            'url' => $request->input('url'),
            'note_id'=> $request->input('note_id'),
        ]);

        return response()->json($image);
    }

/**
 * @OA\Get(
 *     path="/api/images/{id}",
 *     summary="Mostrar una imagen específica",
 *     tags={"Imágenes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalles de la imagen",
 *         @OA\JsonContent(ref="#/components/schemas/Image")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Imagen no encontrada"
 *     )
 * )
 */
    public function show($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
        return response()->json($image);
    }

/**
 * @OA\Put(
 *     path="/api/images/{id}",
 *     summary="Actualizar una imagen",
 *     tags={"Imágenes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"url", "note_id"},
 *             @OA\Property(property="url", type="string", example="images/image_updated.jpg"),
 *             @OA\Property(property="note_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Imagen actualizada",
 *         @OA\JsonContent(ref="#/components/schemas/Image")
 *     )
 * )
 */
    public function update(Request $request, $id)
    {
        $image=Image::find($id);
        $image->update([
            'url' => $request->input('url'),
            'note_id'=> $request->input('note_id'),
        ]);
        return response()->json($image);
    }

/**
 * @OA\Delete(
 *     path="/api/images/{id}",
 *     summary="Eliminar una imagen",
 *     tags={"Imágenes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Imagen eliminada",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Se ha eliminado la imagen correctamente"))
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Imagen no encontrada"
 *     )
 * )
 */
    public function destroy( $id)
    {
        $image = Image::find($id);
        $image->delete();
        return response()->json(['message' => 'Se ha eliminado la imagen correctamente']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
 * @OA\Get(
 *     path="/api/categories",
 *     summary="Listar todas las categorías",
 *     tags={"Categorías"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de categorías",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Category"))
 *     )
 * )
 */
    public function index()
    {
        $categories = DB::table('categories as c')
        ->leftJoin('notes as n', function ($join) {
            $join->on('c.id', '=', 'n.category_id')
                ->where('n.status', '=', 1);
        })
        ->select('c.id', 'c.name', 'c.url', DB::raw('COUNT(n.id) as status'))
        ->groupBy('c.id')
        ->get();
        return response()->json($categories);
    }

/**
 * @OA\Post(
 *     path="/api/categories",
 *     summary="Crear una nueva categoría",
 *     tags={"Categorías"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="Incendios")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Categoría creada",
 *         @OA\JsonContent(ref="#/components/schemas/Category")
 *     )
 * )
 */
    public function store(Request $request)
    {
        $category=Category::create([
            'name' => $request->input('name'),
        ]);

        return response()->json($category);
    }

/**
 * @OA\Get(
 *     path="/api/categories/{id}",
 *     summary="Mostrar una categoría específica",
 *     tags={"Categorías"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalles de la categoría",
 *         @OA\JsonContent(ref="#/components/schemas/Category")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Categoría no encontrada"
 *     )
 * )
 */
    public function show($id)
    {
        $category=Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

/**
 * @OA\Put(
 *     path="/api/categories/{id}",
 *     summary="Actualizar una categoría",
 *     tags={"Categorías"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="Inundaciones")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Categoría actualizada",
 *         @OA\JsonContent(ref="#/components/schemas/Category")
 *     )
 * )
 */
    public function update(Request $request,$id)
    {
        $category=Category::find($id);
        $category->update([
            'name' => $request->input('name'),
        ]);

        return response()->json($category);
    }

/**
 * @OA\Delete(
 *     path="/api/categories/{id}",
 *     summary="Eliminar una categoría",
 *     tags={"Categorías"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Categoría eliminada",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Se ha eliminado la categoria correctamente"))
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Categoría no encontrada"
 *     )
 * )
 */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return response()->json(['message' => 'Se ha eliminado la categoria correctamente']);
    }
}

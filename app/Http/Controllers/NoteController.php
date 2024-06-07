<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/notes",
 *     summary="Listar todas las notas",
 *     tags={"Notas"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de notas",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Note"))
 *     )
 * )
 */
    public function index()
    {
        $notes=Note::all();
        return response()->json($notes);
    }

/**
 * @OA\Post(
 *     path="/api/notes",
 *     summary="Crear una nueva nota",
 *     tags={"Notas"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "description", "ubication", "status", "category_id"},
 *             @OA\Property(property="title", type="string", example="Incendio en el bosque"),
 *             @OA\Property(property="description", type="string", example="Un gran incendio en el bosque ha causado daños significativos."),
 *             @OA\Property(property="ubication", type="string", example="Bosque Nacional"),
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="images", type="array", @OA\Items(type="string", format="binary"))
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Nota creada",
 *         @OA\JsonContent(ref="#/components/schemas/Note")
 *     )
 * )
 */
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ubication' => 'required|string|max:255',
            'status' => 'required|boolean',
            'category_id' => 'required|integer|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Crear la nota
        $note = Note::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'ubication' => $request->input('ubication'),
            'status' => $request->input('status'),
            'user_id' => $request->user()->id,
            'category_id' => $request->input('category_id'),
        ]);
    
        // Manejar la subida de imágenes
        if ($request->hasFile('images')) {
            $files = $request->file('images');
    
            foreach ($files as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('images', $file, $filename);
                $note->images()->create([
                    'url' => 'images/' . $filename
                ]);
            }
        }
    
        return response()->json($note); 
    }       

/**
 * @OA\Get(
 *     path="/api/notes/{id}",
 *     summary="Mostrar una nota específica",
 *     tags={"Notas"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalles de la nota",
 *         @OA\JsonContent(ref="#/components/schemas/Note")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Nota no encontrada"
 *     )
 * )
 */
    public function show($id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }
        return response()->json($note);
    }

/**
 * @OA\Put(
 *     path="/api/notes/{id}",
 *     summary="Actualizar una nota",
 *     tags={"Notas"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "description", "ubication", "status", "category_id"},
 *             @OA\Property(property="title", type="string", example="Incendio en el bosque actualizado"),
 *             @OA\Property(property="description", type="string", example="Actualización: el incendio ha sido controlado."),
 *             @OA\Property(property="ubication", type="string", example="Bosque Nacional"),
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="category_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Nota actualizada",
 *         @OA\JsonContent(ref="#/components/schemas/Note")
 *     )
 * )
 */
    public function update(Request $request, $id)
    {
        $note = Note::find($id);
    
        if (!$note) {
            return response()->json(['message' => 'Nota no encontrada'], 404);
        }
    
        $note->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'ubication' => $request->input('ubication'),
            'status' => $request->input('status'),
            'category_id' => $request->input('category_id'),
        ]);
    
        return response()->json($note);
    }
    

/**
 * @OA\Delete(
 *     path="/api/notes/{id}",
 *     summary="Eliminar una nota",
 *     tags={"Notas"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Nota eliminada",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Se ha eliminado la nota correctamente"))
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Nota no encontrada"
 *     )
 * )
 */
    public function destroy($id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Nota no encontrada'], 404);
        }

        // Eliminar las imágenes relacionadas
        foreach ($note->images as $image) {
            Storage::disk('public')->delete($image->url); 
            $image->delete();
        }

        $note->delete();

        return response()->json(['message' => 'Se ha eliminado la nota correctamente']);
    }

    public function muchos($id){
        $note = Note::find($id);
        $note -> categories()->attach(1);
    }

    /**
 * @OA\Get(
 *     path="/api/notesIncendios",
 *     summary="Obtener notas de incendios",
 *     tags={"Notas"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de notas de incendios",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Note"))
 *     )
 * )
 */
    public function getIncendiosNotes()
    {
        $notes = Note::select('notes.*')
        ->addSelect([
            'images' => Image::selectRaw('JSON_ARRAYAGG(url)')
                ->whereColumn('note_id', 'notes.id')
        ])
        ->addSelect([
            'user_name' => User::select('name')
                ->whereColumn('id', 'notes.user_id')
                ->limit(1)
        ])
        ->join('categories', 'notes.category_id', '=', 'categories.id')
        ->join('users', 'notes.user_id', '=', 'users.id')
        ->where('categories.name', 'Incendios')
        ->groupBy('notes.id')
        ->get()
        ->map(function ($note) {
            $note->images = json_decode($note->images, true) ?? [];
            return $note;
        });
    return response()->json($notes);
    }
    
/**
 * @OA\Get(
 *     path="/api/notesDerrumbes",
 *     summary="Obtener notas de derrumbes",
 *     tags={"Notas"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de notas de derrumbes",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Note"))
 *     )
 * )
 */
    public function getInundacionesNotes()
    {
        $notes = Note::select('notes.*')
            ->addSelect([
                'images' => Image::selectRaw('JSON_ARRAYAGG(url)')
                    ->whereColumn('note_id', 'notes.id')
            ])
            ->addSelect([
                'user_name' => User::select('name')
                    ->whereColumn('id', 'notes.user_id')
                    ->limit(1)
            ])
            ->join('categories', 'notes.category_id', '=', 'categories.id')
            ->where('categories.name', 'Inundaciones')
            ->groupBy('notes.id')
            ->get()
            ->map(function ($note) {
                $note->images = json_decode($note->images, true) ?? [];
                return $note;
            });
    
        return response()->json($notes);
    }

    /**
 * @OA\Get(
 *     path="/api/notesInundaciones",
 *     summary="Obtener notas de inundaciones",
 *     tags={"Notas"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de notas de inundaciones",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Note"))
 *     )
 * )
 */
    public function getDerrumbesNotes()
    {
        $notes = Note::select('notes.*')
            ->addSelect([
                'images' => Image::selectRaw('JSON_ARRAYAGG(url)')
                    ->whereColumn('note_id', 'notes.id')
            ])
            ->addSelect([
                'user_name' => User::select('name')
                    ->whereColumn('id', 'notes.user_id')
                    ->limit(1)
            ])
            ->join('categories', 'notes.category_id', '=', 'categories.id')
            ->where('categories.name', 'Derrumbes')
            ->groupBy('notes.id')
            ->get()
            ->map(function ($note) {
                $note->images = json_decode($note->images, true) ?? [];
                return $note;
            });
    
        return response()->json($notes);
    }

/**
 * @OA\Get(
 *     path="/api/mynotes",
 *     summary="Obtener notas del usuario autenticado",
 *     tags={"Notas"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de notas del usuario",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Note"))
 *     )
 * )
 */
    public function myNotes(Request $request)
    {
        $user = $request->user();
        $notes = Note::where('user_id', $user->id)->get();
        return response()->json($notes);
    }

    
    
}

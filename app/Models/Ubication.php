<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Ubication",
 *     type="object",
 *     required={"name", "latitude", "longitude"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Parque Central"),
 *     @OA\Property(property="description", type="string", example="Un hermoso parque en el centro de la ciudad."),
 *     @OA\Property(property="latitude", type="number", format="float", example=20.6700),
 *     @OA\Property(property="longitude", type="number", format="float", example=-103.3500),
 *     @OA\Property(property="address", type="string", example="Calle Principal #123"),
 *     @OA\Property(property="city", type="string", example="Ciudad de México"),
 *     @OA\Property(property="state", type="string", example="CDMX"),
 *     @OA\Property(property="country", type="string", example="México"),
 *     @OA\Property(property="postal_code", type="string", example="01000"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Ubication extends Model
{
    use HasFactory;
}

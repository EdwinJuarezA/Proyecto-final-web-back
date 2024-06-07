<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="Image",
 *     type="object",
 *     required={"url", "note_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="url", type="string", example="images/image.jpg"),
 *     @OA\Property(property="note_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Image extends Model
{
    use HasFactory;

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }
}

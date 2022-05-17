<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     title="Song model",
 *     description="A song that a user uploaded",
 *     type="object",
 * )
 */
class Song extends Model
{
    use HasFactory;

    /**
     *
     * The attributes that are mass assignable
     *
     */
    protected $fillable = [
        'filename',
        'url',
        'title',
        'duration',
        'artist_name'
    ];

    /**
     * @OA\Property( format="string", maxLength=64, example="Title" )
     *
     * @var Title
     */
    private string $title;

    /**
     * @OA\Property( format="string", maxLength=64, example="duration" )
     */
    private string $duration;

    /**
     * @OA\Property( format="string", maxLength=255,  example="Juan Dela Cruz" )
     */
    private string $artist_name;

    /**
     * @OA\Property( format="string", maxLength=255,  example="song.mp3" )
     */
    private string $filename;

    /**
     * @OA\Property( format="binary" )
     */
    private string $song;
}

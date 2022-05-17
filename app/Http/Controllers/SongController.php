<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Http\Requests\StoreSongRequest;
use App\Http\Requests\UpdateSongRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    /**
     * Fetch a list of Songs
     *
     * @OA\Get(
     *      path="/api/v1/songs",
     *      tags={"Song"}, summary="Get a list of songs",
     *      description="Returns list of songs",
     *      @OA\Response( response=200, description="successful operation" )
     * )
     */
    public function index()
    {
        return new ResourceCollection(Song::paginate(2));
    }

    /**
     * Create a new Song
     *
     * @OA\Post(
     *      path="/api/v1/songs",
     *      summary="Store a new song", tags={"Song"},
     *      description="Validates theme JSON and stores song",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="_method",
     *                      format="string",
     *                      type="string",
     *                      example="PUT"
     *                  ),
     *                  @OA\Property(
     *                      property="file",
     *                      format="binary",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="title",
     *                      format="string",
     *                      type="string",
     *                      example="Song Title"
     *                  ),
     *                  @OA\Property(
     *                      property="duration",
     *                      format="string",
     *                      type="string",
     *                      example="3:00"
     *                  ),
     *                  @OA\Property(
     *                      property="artist_name",
     *                      format="string",
     *                      type="string",
     *                      example="Juan Dela Cruz"
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response( response=201, description="Song successfully created", ),
     *      @OA\Response( response=422, description="Error with supplied data" )
     * )
     */
    public function store(StoreSongRequest $request)
    {
        $input = $request->validated();

        if ($file = $request->file('file')) {
            $input['url'] = $file->store('public/songs');
            $input['filename'] = $file->getClientOriginalName();
        }

        $song = Song::create($input);

        return new JsonResource($song);
    }

    /**
     * Display a Song
     *
     * @OA\Get(
     *      path="/api/v1/songs/{song}",
     *      summary="Get a song", tags={"Song"},
     *      description="Returns a single song",
     *      @OA\Parameter(
     *          name="song", in="path",
     *          description="Id of song", required=true,
     *          @OA\Schema( type="string", example="1" )
     *      ),
     *      @OA\Response( response=200, description="Song successfully retrieved" ),
     *      @OA\Response( response=404, description="Song not found" )
     * )
     */
    public function show(Song $song)
    {
        return new JsonResource($song);
    }


    /**
     * Update a Song
     *
     * @OA\Post(
     *      path="/api/v1/songs/{song}",
     *      summary="Update an song", tags={"Song"},
     *      description="Updates an song with the supplied details",
     *      @OA\Parameter(
     *          name="song", in="path",
     *          description="Id of song", required=true,
     *          @OA\Schema( type="string", example="1" )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="file",
     *                      format="binary",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="title",
     *                      format="string",
     *                      type="string",
     *                      example="Song Title"
     *                  ),
     *                  @OA\Property(
     *                      property="duration",
     *                      format="string",
     *                      type="string",
     *                      example="3:00"
     *                  ),
     *                  @OA\Property(
     *                      property="artist_name",
     *                      format="string",
     *                      type="string",
     *                      example="Juan Dela Cruz"
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response( response=200, description="Song successfully updated" ),
     *      @OA\Response( response=422, description="Validation Error" ),
     *      @OA\Response( response=404, description="Song not found" ),
     * )
     */
    public function update(UpdateSongRequest $request, Song $song)
    {
        $input = $request->validated();

        if ($file = $request->file('file')) {
            $input['url'] = $file->store('public/songs');
            $input['filename'] = $file->getClientOriginalName();
        }

        if (Storage::exists($song->getAttribute('url'))) {
            Storage::delete($song->getAttribute('url'));
        }

        $song->update($input);

        return new JsonResource($song);
    }

    /**
     * Soft delete a Song
     *
     * @OA\Delete(
     *      path="/api/v1/songs/{song}",
     *      summary="Delete an song", tags={"Song"},
     *      description="Returns a single Song",
     *      @OA\Parameter(
     *          name="song", in="path",
     *          description="Id of song", required=true,
     *          @OA\Schema( type="string", example="430a9dc1-ded6-4e04-91c9-c350c692499d" )
     *      ),
     *      @OA\Response( response=204, description="Song successfully deleted" ),
     *      @OA\Response( response=404, description="Song not found" ),
     * )
     */
    public function destroy(Song $song)
    {
        if (Storage::exists($song->getAttribute('url'))) {
            Storage::delete($song->getAttribute('url'));
        }

        $song->delete();

        return response()->noContent();
    }
}

<?php

namespace Tests\Feature;

use App\Models\Song;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SongTest extends TestCase
{
    /**
     * A test for fetching songs.
     *
     * @return void
     */
    public function test_songs_can_be_fetched()
    {
        $response = $this->get('api/v1/songs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "*" => [
                        "id",
                        "filename",
                        "url",
                        "title",
                        "duration",
                        "artist_name",
                        "created_at",
                        "updated_at"
                    ]
                ]
            ]);
    }

    /**
     * A song can be created
     *
     * @return void
     */
    public function test_song_can_be_created()
    {
        Storage::fake();

        $response = $this
            ->postJson('/api/v1/songs', [
                'song' => UploadedFile::fake()->create('music.mp3', '3000', 'mp3'),
                'title' => 'Test Song',
                'duration' => '3:00',
                'artist_name' => 'Juan Dela Cruz'
            ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.title', 'Test Song');
    }

    /**
     * An error will occur when song is not in mp3 format
     *
     * @return void
     */
    public function test_error_thrown_in_creating_when_song_is_not_mp3()
    {
        Storage::fake();

        $response = $this
            ->postJson('/api/v1/songs', [
                'song' => UploadedFile::fake()->create('music.csv', '3000', 'csv'),
                'title' => 'Test Song',
                'duration' => '3:00',
                'artist_name' => 'Juan Dela Cruz'
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonPath('message', 'The song must be a file of type: mp3.');
    }

    /**
     * A song can be created
     *
     * @return void
     */
    public function test_song_can_be_updated()
    {
        Storage::fake();

        $song = Song::inrandomOrder()->first();

        $response = $this
            ->putJson("/api/v1/songs/{$song->getAttribute('id')}", [
                'song' => UploadedFile::fake()->create('music.mp3', '3000', 'mp3'),
                'title' => 'Test Song Updated',
                'duration' => '3:00',
                'artist_name' => 'Juan Dela Cruz'
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.title', 'Test Song Updated');
    }

    /**
     * An error will occur when song is not in mp3 format
     *
     * @return void
     */
    public function test_error_thrown_in_updating_when_song_is_not_mp3()
    {
        Storage::fake();

        $response = $this
            ->postJson('/api/v1/songs', [
                'song' => UploadedFile::fake()->create('music.csv', '3000', 'csv'),
                'title' => 'Test Song',
                'duration' => '3:00',
                'artist_name' => 'Juan Dela Cruz'
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonPath('message', 'The song must be a file of type: mp3.');
    }

    /**
     * A song can be deleted
     *
     * @return void
     */
    public function prize_pool_can_be_deleted(): void
    {
        Storage::fake();

        $song = Song::inrandomOrder()->first();

        $response = $this
            ->delete('/api/v1/songs/' . $song->getAttribute('id'));

        $response->assertNoContent();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $user->createToken('user-token', [
            'songs:store',
            'songs:update',
            'login'
        ]);
    }
}

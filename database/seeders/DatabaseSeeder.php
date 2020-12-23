<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create()
            ->each(function ($user) {
                Post::factory(random_int(2, 5))->seeding()->create(['user_id' => $user])->each(function ($post) {
                    Comment::factory(random_int(1, 3))->create(['post_id' => $post->id]);
                });
            });

        User::first()->update([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);
    }
}

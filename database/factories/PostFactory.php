<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'status' => Post::OPEN,
            'title' => $this->faker->realText(20),
            'body' => $this->faker->realText(100),
        ];
    }

    public function seeding()
    {
        return $this->state(function (array $attribute) {
            return [
                'status' => $this->faker->biasedNumberBetween(0, 1, ['\Faker\Provider\Biased', 'linearHigh'])
            ];
        });
    }

    public function closed()
    {
        return $this->state(function (array $attribute) {
            return [
                'status' => Post::CLOSED,
            ];
        });
    }

    public function withCommentsData(array $comments)
    {
        return $this->afterCreating(function (Post $post) use ($comments) {
            foreach ($comments as $comment) {
                Comment::factory()->create(array_merge(['post_id' => $post->id], $comment));
            }
        });
    }
}

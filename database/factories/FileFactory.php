<?php

namespace Database\Factories;

use App\Models\Directory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word . '.' . $this->faker->fileExtension,
            'path' => uniqid('path/to/' . $this->faker->unique()->word . '.' . $this->faker->fileExtension),
            'directory_id' => Directory::create(['name' => 'example-dir', 'path' => 'example-dir', 'user_id' => 1]),
            'is_public' => $this->faker->boolean,
            'download_token' => $this->faker->uuid,
            'user_id' => User::factory(),
        ];
    }
}

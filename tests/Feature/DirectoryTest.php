<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_store(): void
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'example-dir',
            'path' => 'directories/example-dir',
            'user_id' => $user->id,
        ];

        $this->actingAs($user)
            ->post(route('directories.store', $data));
    }
}

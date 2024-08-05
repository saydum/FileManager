<?php

namespace Test\Feature\Http;

use App\Models\Directory;
use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreDirectory()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Test Directory',
            'path' => 'unique-path',
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson(route('directories.store'), $data);

        $response->assertStatus(201);
        $response->assertJson([
            'name' => $data['name'],
            'path' => $data['path'],
            'user_id' => $data['user_id'],
        ]);
    }

    public function testDestroyDirectory()
    {
        $user = User::factory()->create();
        $directory = Directory::create([
            'name' => 'Test Directory',
            'path' => 'unique-path',
            'user_id' => $user->id
        ]);
        $file = File::create([
            'name' => 'Test Directory',
            'path' => 'unique-path',
            'directory_id' => $directory->id,
            'user_id' => $user->id,
            'is_public' => true
        ]);

        $response = $this->actingAs($user)->deleteJson(route('directories.destroy', $directory->id));

        $response->assertStatus(200)
            ->assertJson([
                "message" => "Директория и файлы внутри него успешно удалены."
            ]);

        $this->assertDatabaseMissing('directories', ['id' => $directory->id]);
        $this->assertDatabaseMissing('files', ['id' => $file->id]);
    }

    public function testRenameDirectory()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Old Directory Name',
            'path' => 'path/to/old-dir',
            'user_id' => $user->id,
        ];

        $directory = Directory::create($data);

        $data['name'] = 'Renamed Directory';

        $response = $this->actingAs($user)->putJson(route('directories.rename', $directory->id), $data);

        // @TODO не верный ответ 500 а должен 200
        $response->assertStatus(200)
            ->assertJson(['message' => 'Директория успешно переименована.']);

        $this->assertDatabaseHas('directories', [
            'id' => $directory->id,
            'name' => $data['name'],
        ]);

        $this->assertDatabaseMissing('directories', [
            'id' => $directory->id,
            'name' => 'Old Directory Name',
        ]);
    }
}

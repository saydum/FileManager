<?php

namespace Feature\Http;

use App\Models\Directory;
use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    private Directory $directory;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $this->user = User::factory()->create();
        $this->directory = Directory::create([
            'name' => 'example-dir-test',
            'path' => 'example-dir-test',
            'user_id' => $this->user->id
        ]);
    }

    public function testUploadFile()
    {

    }

    public function testRenameFile()
    {

    }

    public function testDeleteFile()
    {

    }

    public function testGetFileInfo()
    {

    }

    public function testHiddenFile()
    {

    }

    public function testShowFile()
    {

    }

    public function testGenerateDownloadTokenFile()
    {

    }

    public function testDownloadFile()
    {

    }
}

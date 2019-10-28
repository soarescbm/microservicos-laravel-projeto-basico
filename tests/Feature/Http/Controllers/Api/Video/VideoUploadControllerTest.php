<?php


namespace Tests\Feature\Http\Controllers\Api\Video;

use App\Model\Category;
use App\Model\Genre;
use App\Model\Traits\UploadFiles;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Traits\TestUploads;

class VideoUploadControllerTest extends VideoBaseControllerTestCase
{
    use TestUploads;

    public function testStoreWithFiles()
    {
        Storage::fake();
        $files = $this->getFiles();
        $category = factory(Category::class)->create();
        $genre = factory(Genre::class)->create();
        $genre->categories()->sync([$category->id]);
        $relations = ['categories_id' => [$category->id], 'genres_id' => [$genre->id]];

        $response =  $this->json('POST', $this->routeStore(),
            $this->sendData +
            $relations +
            $files);

        $response->assertStatus(201);
        $id = $response->json('id');
        foreach ($files as $file){
            Storage::assertExists("$id/{$file->hashName()}");
        }


    }
    public function testUpdateWithFiles()
    {
        Storage::fake();
        $files = $this->getFiles();
        $category = factory(Category::class)->create();
        $genre = factory(Genre::class)->create();
        $genre->categories()->sync([$category->id]);
        $relations = ['categories_id' => [$category->id], 'genres_id' => [$genre->id]];

        $response =  $this->json('PUT', $this->routeUpdate(),
            $this->sendData +
            $relations // +
        //$files
        );


        $response->assertStatus(200);
        $id = $response->json('id');
        foreach ($files as $file){
            // Storage::assertExists("$id/{$file->hashName()}");
        }


    }
    public function testValidationVideoFile()
    {
        $this->assertInvalidationFile(
            'video_file',
            'mp4',
            '12',
            'mimetypes', ['values' => 'video/mp4']
        );

    }
    public function getFiles()
    {
        return ['video_file' => UploadedFile::fake()->create('video.mp4')];
    }
}

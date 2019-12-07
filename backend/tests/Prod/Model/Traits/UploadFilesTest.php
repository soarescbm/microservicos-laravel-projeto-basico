<?php

namespace Tests\Prod\Models\Trails;

use App\Model\Category;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Model\Traits\Uuid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tests\Stubs\Model\UploadFileStub;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\TestProd;
use Tests\Traits\TestStorage;

class UploadFilesTest extends TestCase
{
    use DatabaseMigrations, TestStorage, TestProd;

    private $obj;


    public function setUp(): void
    {


        $this->skipTestIfNoProd('Teste de Produção');
        $this->obj = new UploadFileStub();
        parent::setUp();
        Config::set('filesystems.default', 'gcs');
        $this->deleteAllFiles();

    }

    public function testUploadFile()
    {

        $file = UploadedFile::fake()->create('video.mp4');
        $this->obj->uploadFile($file);
        Storage::assertExists('1/' . $file->hashName());
    }

    public function testUploadFiles()
    {

        $file1 = UploadedFile::fake()->create('video1.mp4');
        $file2 = UploadedFile::fake()->create('video2.mp4');
        $this->obj->uploadFiles([$file1, $file2]);
        Storage::assertExists('1/' . $file1->hashName());
        Storage::assertExists('1/' . $file2->hashName());
    }

    public function testDeleteFile()
    {

        $file = UploadedFile::fake()->create('video.mp4');
        $this->obj->uploadFile($file);
        $filename = $file->hashName();
        $this->obj->deleteFile($filename);
        Storage::assertMissing('1/' . $filename);


        $file = UploadedFile::fake()->create('video1.mp4');
        $this->obj->uploadFile($file);
        $this->obj->deleteFile($file);
        Storage::assertMissing('1/' . $file->hashName());
    }

    public function testDeleteOldFiles()
    {

        $file1 = UploadedFile::fake()->create('video1.mp4');
        $file2 = UploadedFile::fake()->create('video2.mp4');
        $this->obj->uploadFiles([$file1, $file2]);
        $this->obj->deleteOldFiles();
        $this->assertCount(2, Storage::allFiles());

        $this->obj->oldFiles =  [$file1->hashName()];

        $this->obj->deleteOldFiles();
        Storage::assertMissing('1/' . $file1->hashName());
        Storage::assertExists('1/' . $file2->hashName());
    }
    public function testDeleteFiles()
    {

        $file1 = UploadedFile::fake()->create('video1.mp4');
        $file2 = UploadedFile::fake()->create('video2.mp4');
        $this->obj->uploadFile($file1);
        $this->obj->uploadFile($file2);
        $filename = $file1->hashName();
        $this->obj->deleteFiles([$filename, $file2]);
        Storage::assertMissing('1/' . $filename);
        Storage::assertMissing('1/' . $file2->hashName());

    }

    public function testExtractFiles()
    {
        $attributes = [];
        $files = UploadFileStub::extractFiles($attributes);
        $this->assertCount(0, $attributes);
        $this->assertCount(0, $files);

        $attributes = ['file1' => 'test'];
        $files = UploadFileStub::extractFiles($attributes);
        $this->assertCount(1, $attributes);
        $this->assertEquals(['file1' => 'test'], $attributes);
        $this->assertCount(0, $files);

        $attributes = ['file1' => 'test1', 'file2' => 'test2'];
        $files = UploadFileStub::extractFiles($attributes);
        $this->assertCount(2, $attributes);
        $this->assertEquals(['file1' => 'test1', 'file2' => 'test2'], $attributes);
        $this->assertCount(0, $files);

        $file1 = UploadedFile::fake()->create('video4.mp4');
        $attributes = ['file1' => $file1, 'other' => 'test2'];
        $files = UploadFileStub::extractFiles($attributes);
        $this->assertCount(2, $attributes);
        $this->assertEquals(['file1' => $file1->hashName(), 'other' => 'test2'], $attributes);
        $this->assertEquals([$file1], $files);

        $file2 = UploadedFile::fake()->create('video5.mp4');
        $attributes = ['file1' => $file1, 'file2' => $file2, 'other' => 'test2'];
        $files = UploadFileStub::extractFiles($attributes);
        $this->assertCount(3, $attributes);
        $this->assertEquals(['file1' => $file1->hashName(), 'file2' => $file2->hashName(),'other' => 'test2'], $attributes);
        $this->assertEquals([$file1, $file2], $files);
    }
}

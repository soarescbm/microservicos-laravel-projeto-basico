<?php


namespace Tests\Feature\Models\Video;


use App\Model\Video;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\Exceptions\TestException;

class VideoUploadTest extends VideoBaseTestCase
{
    public function testCreateWithFiles()
    {
        Storage::fake();

        $video = Video::create($this->data +
        [
            'video_file' => UploadedFile::fake()->create('video.mp4'),
            'thumb_file' => UploadedFile::fake()->create('image.jpeg')
        ]);


        Storage::assertExists("{$video->id}/{$video->video_file}");
        Storage::assertExists("{$video->id}/{$video->thumb_file}");


    }

    public function testCreateRollBackFiles()
    {
        Storage::fake();
        Event::listen(TransactionCommitted::class, function (){
            throw new TestException();
        });

        $hasErro = false;

        try {

            $video = Video::create($this->data +
                [
                    'video_file' => UploadedFile::fake()->create('video.mp4'),
                    'thumb_file' => UploadedFile::fake()->create('image.jpeg')
                ]);
        } catch (TestException $e ){
            $this->assertCount(0, Storage::allFiles());
            $hasErro = true;
        }

        $this->assertTrue($hasErro);


    }

    public function testUpdateWithFiles()
    {
        Storage::fake();

        $video = factory(Video::class)->create();
        $video_file =  UploadedFile::fake()->create('video.mp4');
        $thumb_file = UploadedFile::fake()->create('image.jpeg');
        $video->update($this->data +
            [
                'video_file' => $video_file,
                'thumb_file' => $thumb_file
            ]);


        Storage::assertExists("{$video->id}/{$video->video_file}");
        Storage::assertExists("{$video->id}/{$video->thumb_file}");

        $new_video_file =  UploadedFile::fake()->create('videonew.mp4');
        $video->update($this->data +
            [
                'video_file' => $new_video_file,
                'thumb_file' => $thumb_file
            ]);

        Storage::assertExists("{$video->id}/{$new_video_file->hashName()}");
        Storage::assertExists("{$video->id}/{$thumb_file->hashName()}");
        Storage::assertMissing("{$video->id}/{$video_file->hashName()}");

    }

    public function testUpdateRollBackFiles()
    {
        Storage::fake();
        Event::listen(TransactionCommitted::class, function (){
            throw new TestException();
        });

        $hasErro = false;

        $video = factory(Video::class)->create();
        try {

            $video->update($this->data +
                [
                    'video_file' => UploadedFile::fake()->create('video.mp4'),
                    'thumb_file' => UploadedFile::fake()->create('image.jpeg')
                ]);

        } catch (TestException $e ){
            $this->assertCount(0, Storage::allFiles());
            $hasErro = true;
        }

        $this->assertTrue($hasErro);


    }
}

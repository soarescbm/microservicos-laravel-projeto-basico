<?php

namespace Tests\Feature\Models;

use App\Model\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideoTest extends TestCase
{
    use DatabaseMigrations;

    private $video;

    public static function setUpBeforeClass(): void
    {
        // parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    public function setUp(): void
    {
        $this->video = new Video();
        parent::setUp();
    }

    public function testList()
    {
        factory(Video::class,1)->create();
        $videos = Video::all();
        $this->assertCount(1, $videos);
        $videoKeys = array_keys($videos->first()->getAttributes());

        $this->assertEqualsCanonicalizing([
            'id',
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration',
            'deleted_at',
            'created_at',
            'updated_at'

        ] ,
            $videoKeys);
    }

    public function testCreate()
    {
        $data = [
            'title' => 'title',
            'description' => 'descriptin',
            'opened' => false,
            'year_launched' => 2019,
            'rating' => Video::RATING_LIST[0],
            'duration' => 9
        ];
        $video = Video::create($data);

        $video->refresh();

        $this->assertEquals(36, strlen($video->id));
        $this->assertFalse($video->opened);
        $this->assertEquals('L', $video->rating);
        $this->assertEquals(2019, $video->year_launched);
        $this->assertEquals(9, $video->duration);


    }

    public function testUpdate()
    {

        $video = factory(Video::class)->create(['opened' => false]);

        $data = [
            'title' => 'title',
            'description' => 'descriptin',
            'opened' => true,
            'year_launched' => 2019,
            'rating' => Video::RATING_LIST[0],
            'duration' => 9
        ];

        $video->update($data);

        foreach ($data as $key => $value){
            $this->assertEquals($value, $video->{$key});
        }


    }

    public function testDelete()
    {
        $video = factory(Video::class)->create(['opened' => false]);
        $video->delete();

        $this->assertNull(Video::find($video->id));

        $video->restore();
        $this->assertNotNull(Video::find($video->id));

    }
}

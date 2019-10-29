<?php


namespace Tests\Feature\Models\Video;

use App\Model\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

abstract class VideoBaseTestCase extends TestCase
{
    use DatabaseMigrations;


    protected $video;
    protected $data;

    public function setUp(): void
    {
        $this->video = new Video();
        parent::setUp();
        $this->data = [
            'title' => 'title',
            'description' => 'descriptin',
            'year_launched' => 2019,
            'rating' => Video::RATING_LIST[0],
            'duration' => 9
        ];
    }
}


<?php

use App\Model\Video;
use Illuminate\Database\Seeder;
use App\Model\Genre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class VideoTableSeeder extends Seeder
{
    protected $allGenres;
    protected $relations = [
        'genres_id' => [],
        'categories_id' => []
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dir = \Storage::getDriver()->getAdapter()->getPathPrefix();
        \File::deleteDirectory($dir, true);

        $self = $this;
        $this->allGenres = Genre::All();
        Model::reguard();

        factory(Video::class,100)
            ->make()
            ->each(function (Video $video) use ($self) {
                $self->fetchRelations();
                Video::create(
                    array_merge(
                        $video->toArray(),
                        [
                            'video_file' => $self->getVideoFile(),
                            'thumb_file' => $self->getImageFile(),
                            'trailer_file' => $self->getVideoFile(),
                            'banner_file' => $self->getImageFile()
                        ],
                        $self->relations
                    )
                );
            });

        Model::unguard();

    }

    public function fetchRelations()
    {
        $subGenres = $this->allGenres->random(5)->load('categories');
        $genresId = $subGenres->pluck('id')->toArray();
        $cagegoriesId = [];
        foreach ($subGenres as $genre){
            array_push($cagegoriesId, ...$genre->categories()->pluck('id')->toArray());
        }
        $cagegoriesId = array_unique($cagegoriesId);
        $this->relations['categories_id'] = $cagegoriesId;
        $this->relations['genres_id'] = $genresId;
    }

    public function getImageFile()
    {
        return new UploadedFile(
            storage_path('faker/thumb/thumb.jpg'),
            'Faker Thumb'
        );
    }

    public function getVideoFile()
    {
        return new UploadedFile(
            storage_path('faker/video/video.MP4'),
            'Faker Video'
        );
    }
}

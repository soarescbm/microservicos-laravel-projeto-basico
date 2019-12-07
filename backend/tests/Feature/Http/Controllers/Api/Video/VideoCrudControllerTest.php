<?php


namespace Tests\Feature\Http\Controllers\Api\Video;

use App\Http\Resources\VideoResource;
use App\Model\Category;
use App\Model\Genre;
use App\Model\Video;

class VideoCrudControllerTest extends VideoBaseControllerTestCase
{
    public function testIndex()
    {

        $response = $this->get(route('videos.index'));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->serializedFields
                ],
                'links' => [],
                'meta' => []
            ]);

        $resource = VideoResource::collection(collect([$this->video]));
        $this->assertResource($response, $resource);
    }

    public function testShow()
    {

        $response = $this->get(route('videos.show', ['video' => $this->video->id]));
        $response->assertStatus(200);
        $id =  $this->video->id;
        $resource = new VideoResource(Video::find($id));
        $this->assertResource($response, $resource);
    }


    public function testInvalidateRequired()
    {
        $data = [
            'title' => '',
            'description' => '',
            'year_launched' => '',
            'rating' => '',
            'duration' => '',
            'categories_id' => '',
            'genres_id' => '',
        ];

        $this->assertValidationStoreAction($data, 'required');
        $this->assertValidationUpdateAction($data, 'required');

    }

    public function testInvalidateMax()
    {
        $data = [
            'title' => str_repeat('a',256),
        ];

        $this->assertValidationStoreAction($data,'max.string' ,   ['max' => 255]);
        $this->assertValidationUpdateAction($data,'max.string' ,  ['max' => 255]);

    }

    public function testInvalidateInteger()
    {
        $data = [
            'duration' => 'a',
        ];

        $this->assertValidationStoreAction($data,'integer');
        $this->assertValidationUpdateAction($data,'integer');

    }

    public function testInvalidateYearLaunchedField()
    {
        $data = [
            'year_launched' => 'a',
        ];

        $this->assertValidationStoreAction($data,'date_format',['format' => 'Y'] );
        $this->assertValidationUpdateAction($data,'date_format', ['format' => 'Y']);

    }

    public function testInvalidateOpenedField()
    {
        $data = [
            'opened' => 'a',
        ];

        $this->assertValidationStoreAction($data,'boolean');
        $this->assertValidationUpdateAction($data,'boolean');

    }

    public function testInvalidateCategoriesIdField()
    {
        $data = [
            'categories_id' => 'a',
        ];

        $this->assertValidationStoreAction($data,'array');
        $this->assertValidationUpdateAction($data,'array');

        $data = [
            'categories_id' => [100],
        ];

        $this->assertValidationStoreAction($data,'exists');
        $this->assertValidationUpdateAction($data,'exists');

    }

    public function testInvalidateGenresIdField()
    {
        $data = [
            'genres_id' => 'a',
        ];

        $this->assertValidationStoreAction($data,'array');
        $this->assertValidationUpdateAction($data,'array');

        $data = [
            'genres_id' => [100],
        ];

        $this->assertValidationStoreAction($data,'exists');
        $this->assertValidationUpdateAction($data,'exists');

    }

    public function testInvalidateRatingField()
    {
        $data = [
            'rating' => 'a',
        ];

        $this->assertValidationStoreAction($data,'in');
        $this->assertValidationUpdateAction($data,'in');

    }



    public function testSaveWithoutFiles()
    {

        $category = factory(Category::class)->create();
        $genre = factory(Genre::class)->create();
        $genre->categories()->sync([$category->id]);

        $relations = ['categories_id' => [$category->id], 'genres_id' => [$genre->id]];
        // $relations = [];
        $data = [
            [
                'send_data' => $this->sendData + $relations,
                'test_data' => $this->sendData + ['opened' => false]
            ],
            [
                'send_data' => $this->sendData + ['opened' => true] + $relations ,
                'test_data' => $this->sendData + ['opened' => true]
            ],
            [
                'send_data' => $this->sendData +  ['rating' => Video::RATING_LIST[1]] + $relations,
                'test_data' => $this->sendData +  ['rating' => Video::RATING_LIST[1]]
            ]
        ];


        foreach ($data as $key => $value){

            $response = $this->assertStore($value['send_data'], $value['test_data'] + ['deleted_at' => null]);
            $response->assertJsonStructure([
                'data' =>  $this->serializedFields
            ]);

            $this->assertHasCategory(
                $response->json('data.id'),
                $value['send_data']['categories_id'][0]
            );
            $this->assertHasGenre(
                $response->json('data.id'),
                $value['send_data']['genres_id'][0]
            );

            $response = $this->assertUpdate($value['send_data'], $value['test_data'] + ['deleted_at' => null]);
            $response->assertJsonStructure([
                'data' =>  $this->serializedFields
            ]);

            $this->assertHasCategory(
                $response->json('data.id'),
                $value['send_data']['categories_id'][0]
            );
            $this->assertHasGenre(
                $response->json('data.id'),
                $value['send_data']['genres_id'][0]
            );
        }
    }

    public function testRollBackCreate()
    {

        $hasError = false;
        try {
            Video::create( ['title' => 'title',
                    'description' => 'descriptin',
                    'year_launched' => 2019,
                    'rating' => Video::RATING_LIST[0],
                    'duration' => 9,
                    'categories_id' => [0,2,3,4]]
            );
        } catch (\Exception $exception) {
            $this->assertCount(1, Video::all());
            $hasError = true;
        }

        $this->assertTrue($hasError);


    }

    public function testRollBackUpdate()
    {

        $hasError = false;
        try {
            $this->video->update(['title' => 'title',
                'description' => 'descriptin',
                'year_launched' => 2019,
                'rating' => Video::RATING_LIST[0],
                'duration' => 9,
                'categories_id' => [0,2,3,4,5]]);
        } catch (\Exception $exception) {
            $this->assertCount(1, Video::all());
            $hasError = true;
        }
        $this->assertTrue($hasError);

    }

    public function testSyncCategories()
    {
        $categoriesId = factory(Category::class,3)->create()->pluck('id')->toArray();
        $genre = factory(Genre::class)->create();
        $genre->categories()->sync($categoriesId);
        $genreId = $genre->id;



        $response = $this->json('POST', $this->routeStore(), $this->sendData +
            [
                'categories_id' =>  [$categoriesId[0]],
                'genres_id' => [$genreId]
            ]);

        $this->assertDatabaseHas('category_video',
            [
                'category_id' =>  $categoriesId[0],
                'video_id' => $response->json('data.id')
            ]);



        $response = $this->json('PUT',
            route('videos.update', ['video' => $response->json('data.id')]),
            $this->sendData +
            [
                'categories_id' =>  [$categoriesId[1], $categoriesId[2]],
                'genres_id' => [$genreId]
            ]);

        $this->assertDatabaseMissing('category_video',
            [
                'video_id' => $response->json('data.id'),
                'category_id' => $categoriesId[0]
            ]);

        $this->assertDatabaseHas('category_video',
            [
                'video_id' => $response->json('data.id'),
                'category_id' => $categoriesId[1]
            ]);

        $this->assertDatabaseHas('category_video',
            [
                'video_id' => $response->json('data.id'),
                'category_id' => $categoriesId[2]
            ]);
    }

    public function testSyncGenres()
    {
        $genres = factory(Genre::class,3)->create();
        $genresId = $genres->pluck('id')->toArray();
        $categoryId = factory(Category::class)->create()->id;
        $genres->each(function ($genre) use ($categoryId) {
            $genre->categories()->sync([$categoryId]);
        });


        $response = $this->json('POST', $this->routeStore(), $this->sendData +
            [
                'categories_id' =>  [$categoryId],
                'genres_id' => [$genresId[0]]
            ]);

        $this->assertDatabaseHas('genre_video',
            [
                'genre_id' => $genresId[0],
                'video_id' => $response->json('data.id')
            ]);



        $response = $this->json('PUT',
            route('videos.update', ['video' => $response->json('data.id')]),
            $this->sendData +
            [
                'categories_id' =>  [$categoryId],
                'genres_id' => [$genresId[1], $genresId[2]]
            ]);

        $this->assertDatabaseMissing('genre_video',
            [
                'video_id' => $response->json('data.id'),
                'genre_id' => $genresId[0]
            ]);

        $this->assertDatabaseHas('genre_video',
            [
                'video_id' => $response->json('data.id'),
                'genre_id' => $genresId[1]
            ]);

        $this->assertDatabaseHas('genre_video',
            [
                'video_id' => $response->json('data.id'),
                'genre_id' => $genresId[2]
            ]);
    }

    public function testDelete()
    {

        $response = $this->json('delete', route('videos.destroy',  ['video' => $this->video->id]));
        $response->assertStatus(204);


        $response = $this->get(route('videos.show', ['video' => $this->video->id]));
        $response->assertStatus(404);


        $this->video->restore();

        $response = $this->get(route('videos.show', ['video' => $this->video->id]));
        $response->assertStatus(200);

    }

    protected  function assertHasCategory($videoId, $categoryId)
    {
        $this->assertDatabaseHas('category_video',
            [
                'category_id' => $categoryId,
                'video_id' => $videoId
            ]
        );
    }

    protected  function assertHasGenre($videoId, $genreId)
    {
        $this->assertDatabaseHas('genre_video', [
                'genre_id' => $genreId,
                'video_id' => $videoId]
        );
    }
}

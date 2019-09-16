<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Model\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;

    protected $category;

    public  function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->category = factory(Category::class)->create();
    }

    public function testIndex()
    {

        $response = $this->get(route('categories.index'));
        $response->assertStatus(200)
        ->assertJson([$this->category->toArray()]);
    }

    public function testShow()
    {

        $response = $this->get(route('categories.show', ['category' => $this->category->id]));
        $response->assertStatus(200)
            ->assertJson($this->category->toArray());
    }

    public function testInvalidateData()
    {

        $data  = [
            'name' => ''
        ];

        $this->assertValidationStoreAction($data, 'required');
        $this->assertValidationUpdateAction($data, 'required');

        $data  = [
            'name' => str_repeat('a', 256)
        ];

        $this->assertValidationStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertValidationUpdateAction($data, 'max.string', ['max' => 255]);

        $data  = [
            'is_active' => 'a'
        ];

        $this->assertValidationStoreAction($data, 'boolean');
        $this->assertValidationUpdateAction($data, 'boolean');


    }

    protected function assertInvalidationRequired($response){

        $this->assertValidationFields($response, ['name'], 'required');

    }

    protected function assertInvalidationMax($response){

        $this->assertValidationFields($response, ['name'], 'max.string', ['max' => 255]);

    }

    protected function assertInvalidationBoolean($response)
    {
        $this->assertValidationFields($response, ['is_active'], 'boolean');

    }

    public function testStore()
    {
        $data = [
            'name' => 'test'
        ];

        $response =  $this->assertStore($data, $data + ['description' => null, 'is_active' => true, 'deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
        $data = [
            'name' => 'test',
            'description' => 'description',
            'is_active' => false

        ];

        $this->assertStore($data, $data + [ 'deleted_at' => null]);



    }

    public function testUpdate()
    {
        $this->category = factory(Category::class)->create([
            'description' => 'description',
            'is_active' => false
        ]);

        $data = [
            'name' => 'test',
            'description' => 'test',
            'is_active' => true
        ];

        $response =  $this->assertUpdate($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);

        $data = [
            'name' => 'test',
            'description' => '',
        ];

       $this->assertUpdate($data, array_merge($data,  ['description' => null]));

       $data = [
            'name' => 'test',
            'description' => 'test',
        ];
        $this->assertUpdate($data, array_merge($data,  ['description' => 'test']));



    }

    public function testDelete()
    {

        $response = $this->json('delete', route('categories.destroy',  ['category' => $this->category->id]));
        $response->assertStatus(204);


        $response = $this->get(route('categories.show', ['category' => $this->category->id]));
        $response->assertStatus(404);


        $this->category->restore();

        $response = $this->get(route('categories.show', ['category' => $this->category->id]));
        $response->assertStatus(200)
        ->assertJson($this->category->toArray());

    }

    protected function routeStore()
    {
        return route('categories.store');
    }

    protected function routeUpdate()
    {
        return route('categories.update', ['category' => $this->category->id]);
    }

    protected function model()
    {
        return Category::class;
    }

}

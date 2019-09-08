<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Model\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function testIndex()
    {
        $category = factory(Category::class,1)->create();
        $response = $this->get(route('categories.index'));
        $response->assertStatus(200)
        ->assertJson($category->toArray());
    }

    public function testShow()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.show', ['category' => $category->id]));
        $response->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testInvalidateData()
    {
        $response = $this->json('post', route('categories.store', []));
       // dd($response->content());
        $this->assertInvalidationRequired($response);

        $response = $this->json('post', route('categories.store', ['name' => str_repeat('a', 256), 'is_active' => 'a']));
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);



        $category = factory(Category::class)->create();
        $response = $this->json('put', route('categories.update',['category' => $category->id]), []);
        $this->assertInvalidationRequired($response);


        $category = factory(Category::class)->create();
        $response = $this->json('put', route('categories.update',['category' => $category->id]),
            [
                'name' => str_repeat('a', 256),
                'is_active' => 'a']
        );
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);
    }

    protected function assertInvalidationRequired($response){

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([\Lang::trans('validation.required', ['attribute' => 'name'])]);
    }

    protected function assertInvalidationMax($response){

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([\Lang::trans('validation.max.string', ['attribute' => 'name', 'max' => 255 ])]);
    }

    protected function assertInvalidationBoolean($response)
    {
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'is_active'])
            ->assertJsonFragment([\Lang::trans('validation.boolean', ['attribute' => 'is active'])]);
    }
}

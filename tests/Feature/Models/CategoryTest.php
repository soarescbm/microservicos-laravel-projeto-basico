<?php

namespace Tests\Feature\Models;

use App\Model\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{

    use DatabaseMigrations;

    private $category;

    public static function setUpBeforeClass()
    {
        // parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    public function setUp(): void
    {
        $this->category = new Category();
        parent::setUp();
    }

    public function testList()
    {
          factory(Category::class,1)->create();
          $categories = Category::all();
          $this->assertCount(1, $categories);
          $categoryKeys = array_keys($categories->first()->getAttributes());

          $this->assertEqualsCanonicalizing([
              'id',
              'name',
              'description',
              'is_active',
              'deleted_at',
              'created_at',
              'updated_at'

          ] ,
              $categoryKeys);
    }

    public function testCreate()
    {
        $category = Category::create(['name' => 'test1']);
        $category->refresh();

        $this->assertEquals(36, strlen($category->id));
        $this->assertNull($category->descrition);
        $this->assertTrue((bool)$category->is_active);

        $category = Category::create(['name' => 'test1', 'description' => null]);
        $this->assertNull($category->description);

        $category = Category::create(['name' => 'test1', 'description' => 'test_description']);
        $this->assertEquals('test_description', $category->description);

        $category = Category::create(['name' => 'test1', 'is_active' => false]);
        $this->assertFalse($category->is_active);

        $category = Category::create(['name' => 'test1', 'is_active' => true]);
        $this->assertTrue($category->is_active);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create(['description' => 'test_description', 'is_active' => false]);

        $data = [
            'name' => 'test_name_updated',
            'description' => 'test_description_updated',
            'is_active' => true
        ];

        $category->update($data);

        foreach ($data as $key => $value){
            $this->assertEquals($value, $category->{$key});
        }


    }

    public function testDelete()
    {
        $category = factory(Category::class)->create(['description' => 'test_description', 'is_active' => false]);
        $category->delete();

        $this->assertNull(Category::find($category->id));

        $category->restore();
        $this->assertNotNull(Category::find($category->id));

    }


}

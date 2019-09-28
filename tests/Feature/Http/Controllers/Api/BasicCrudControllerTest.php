<?php


namespace Tests\Feature\Http\Controllers\Api;


use App\Http\Controllers\Api\BasicCrudController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\Stubs\Controllers\CategoryControllerStub;
use Tests\Stubs\Model\CategoryStub;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class BasicCrudControllerTest extends TestCase
{


    protected $controller;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        CategoryStub::dropTable();
        CategoryStub::createTable();
        $this->controller =  new CategoryControllerStub();

    }

    public function tearDown(): void
    {
        CategoryStub::dropTable();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    public function testIndex()
    {
        $category =  CategoryStub::create(['name' => 'test', 'description' => 'description', 'is_active' => false]);
        $result = $this->controller->index()->toArray();
        $this->assertEquals([$category->toArray()], $result);
    }

//    /**
//     * @expectedException('\Illuminate\Validation\ValidationException')
//     */
    public function testValidationDataInStore()
    {
        $this->expectException(ValidationException::class);
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);
        $this->controller->store($request);

    }


    public function testStore()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn(['name' => 'test']);
       $obj = $this->controller->store($request);

       $this->assertEquals(CategoryStub::find(1)->toArray(),
           $obj->toArray());

    }

    public function testIfFirstOrFailFetchModel()
    {
        $category =  CategoryStub::create(['name' => 'test']);
        $reflectionClass =  new \ReflectionClass( BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invokeArgs($this->controller, ['id' =>$category->id]);
        $this->assertInstanceOf(CategoryStub::class, $result);
    }

//    /**
//     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
//     */
    public function testIfFirstOrFailThrowEXceptionIdInvalid()
    {
        $this->expectException(ModelNotFoundException::class);
        $category =  CategoryStub::create(['name' => 'test']);
        $reflectionClass =  new \ReflectionClass( BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invokeArgs($this->controller, ['id' => 0]);

    }


    public function testUpdate()
    {
        $category =  CategoryStub::create(['name' => 'test']);
        $data = ['name' => 'test_update'];

        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn($data);
        $obj = $this->controller->update($request, $category->id);

        $this->assertEquals(CategoryStub::find($category->id)->toArray(),
            $obj->toArray());

    }


    public function testShow()
    {
        $category =  CategoryStub::create(['name' => 'test']);

        $obj = $this->controller->show($category->id);

        $this->assertEquals(CategoryStub::find($category->id)->toArray(),
            $obj->toArray());

    }

    public function testDestroy()
    {
        $category =  CategoryStub::create(['name' => 'test']);

        $obj = $this->controller->destroy($category->id);
        $this->assertEquals($obj->getStatusCode(), 204);


    }

}

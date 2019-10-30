<?php


namespace Tests\Stubs\Controllers;


use App\Http\Controllers\Api\BasicCrudController;
use Tests\Stubs\Model\CategoryStub;
use Tests\Stubs\Resources\CategoryResourceStub;

class CategoryControllerStub extends BasicCrudController
{

    protected function model()
    {
        return CategoryStub::class;
    }

    protected function rulesStore()
    {
        return [
            'name' => 'required|max:255',
            'descripiton' => 'nullable',
            'is_active' => 'boolean'
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'name' => 'required|max:255',
            'descripiton' => 'nullable',
            'is_active' => 'boolean'
        ];
    }

    protected function resource()
    {
        return CategoryResourceStub::class;
    }

    protected function resourceCollection()
    {
        return $this->resource();
    }
}

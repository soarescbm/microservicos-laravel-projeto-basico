<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Model\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends BasicCrudController
{

    //
    protected function model()
    {
        return Category::class;
    }


    protected function rulesStore()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'is_active' => 'boolean'
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'is_active' => 'boolean'
        ];
    }

    protected function resourceCollection()
    {
        return $this->resource();
       //return CategoryCollection::class;
    }

    protected function resource()
    {
        return CategoryResource::class;
    }




//    private $rules = [
//        'name' => 'required|max:255',
//        'is_active' => 'boolean'
//    ];
//    public function index()
//    {
//        return Category::all();
//    }
//
//
//    public function store(Request $request)
//    {
//        $this->validate($request, $this->rules);
//        $category =  Category::create($request->all());
//        $category->refresh();
//        return $category;
//    }
//
//
//    public function show(Category $category)
//    {
//        $data = $category;
//    }
//
//
//    public function update(Request $request, Category $category)
//    {
//
//        $this->validate($request, $this->rules);
//        $category->update($request->all());
//        return $category;
//    }
//
//
//    public function destroy(Category $category)
//    {
//        $category->delete();
//        return response()->noContent(); //status 204
//    }

}

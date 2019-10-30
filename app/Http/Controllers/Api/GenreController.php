<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GenreResource;
use App\Model\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenreController extends BasicCrudController
{
    private $rules ;

    public function __construct()
    {
        $this->rules = [
            'name' => 'required|max:255',
            'is_active' => 'boolean',
            'categories_id' => 'required|array|exists:categories,id,deleted_at,NULL'
        ];
    }

    public function store(Request $request)
    {
        $validation =  $this->validate($request, $this->rulesStore());

        $self = $this;
        $obj = \DB::transaction(function () use($validation, $request, $self){
            $obj = $this->model()::create($validation);
            $self->handleRelations($obj, $request);
            return $obj;
        });

        $obj->refresh();
        $resource = $this->resource();
        return new $resource($obj);
    }


    public function update(Request $request, $id)
    {
        $validation = $this->validate($request, $this->rulesUpdate());

        $self = $this;

        $obj = \DB::transaction(function () use($validation, $request,$id, $self){
            $obj = $this->findOrFail($id);
            $obj->update($validation);
            $self->handleRelations($obj, $request);
            return $obj;
        });

        $resource = $this->resource();
        return new $resource($obj);
    }


    protected function handleRelations($genre, $request)
    {
        $genre->categories()->sync($request->get('categories_id'));
    }

    protected function model()
    {
        return Genre::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return  $this->rules;
    }

    protected function resource()
    {
        return GenreResource::class;
    }

    protected function resourceCollection()
    {
        return $this->resource();
    }
}

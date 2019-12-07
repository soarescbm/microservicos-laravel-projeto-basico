<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Model\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class BasicCrudController extends Controller
{

    protected $paginationSize = 15;
    protected abstract function model();
    protected abstract function rulesStore();
    protected abstract function rulesUpdate();
    protected abstract function resource();
    protected abstract function resourceCollection();

    public function index()
    {
        $data = ($this->paginationSize) ? $this->model()::paginate($this->paginationSize) : $this->model()::all() ;
        $resourceCollection = $this->resourceCollection();
        $refClass = new \ReflectionClass($resourceCollection);

        return ($refClass->isSubclassOf(ResourceCollection::class))?
            new $resourceCollection($this->model()::paginate($this->paginationSize) ) :
            $resourceCollection::collection($this->model()::paginate($this->paginationSize) );

    }


    public function store(Request $request)
    {
       $validation =  $this->validate($request, $this->rulesStore());
       $obj = $this->model()::create($validation);
       $obj->refresh();
       $resource = $this->resource();
       return new $resource($obj);

    }

    public function update(Request $request, $id)
    {

        $validation = $this->validate($request, $this->rulesUpdate());
        $obj = $this->findOrFail($id);
        $obj->update($validation);
        $resource = $this->resource();
        return  new $resource($obj);
    }

    public function show($id)
    {
        $obj = $this->findOrFail($id);
        $resource = $this->resource();
        return  new $resource($obj);
    }

    public function destroy($id)
    {
        $obj = $this->findOrFail($id);
        $obj->delete();
        return response()->noContent(); //status 204
    }

    protected function findOrFail($id)
    {
        $model = $this->model();
        $keyName =  (new $model)->getRouteKeyName();
        return $this->model()::where($keyName, $id)->firstOrFail();
    }



}

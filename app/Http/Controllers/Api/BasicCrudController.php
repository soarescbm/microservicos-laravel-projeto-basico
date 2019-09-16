<?php

namespace App\Http\Controllers\Api;

use App\Model\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

abstract class BasicCrudController extends Controller
{

    protected abstract function model();
    protected abstract function rulesStore();
    protected abstract function rulesUpdate();


    public function index()
    {
        return $this->model()::all();
    }


    public function store(Request $request)
    {
       $validation =  $this->validate($request, $this->rulesStore());
       $obj = $this->model()::create($validation);
       $obj->refresh();
       return $obj;

    }

    public function update(Request $request, $id)
    {

        $validation = $this->validate($request, $this->rulesUpdate());
        $obj = $this->findOrFail($id);
        $obj->update($validation);
        return $obj;
    }

    public function show($id)
    {
        return $this->findOrFail($id);
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

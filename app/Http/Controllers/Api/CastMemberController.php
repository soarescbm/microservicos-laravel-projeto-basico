<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CastMemberResource;
use App\Http\Resources\CategoryResource;
use App\Model\CastMember;


class CastMemberController extends BasicCrudController
{
    //
    protected function model()
    {
        return CastMember::class;
    }

    protected function rulesStore()
    {
        return [
            'name' => 'required|max:255',
            'type' => 'required|integer',
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'name' => 'required|max:255',
            'type' => 'required|integer',
        ];
    }

    protected function resource()
    {
        return CastMemberResource::class;
    }

    protected function resourceCollection()
    {
       return $this->resource();
    }
}

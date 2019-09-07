<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Traits\Uuid;

class Category extends Model
{

    use SoftDeletes, Uuid;

    protected $fillable = ['name', 'description', 'is_active'];
    protected $dates = ['deleted_at'];
    protected $casts = ['id' => 'string', 'is_active' => 'boolean'];
    public $incrementing = false;


}

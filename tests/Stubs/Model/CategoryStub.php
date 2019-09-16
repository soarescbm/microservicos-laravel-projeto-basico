<?php


namespace Tests\Stubs\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class CategoryStub extends Model
{
    protected $table =  'categoriy_stubs';
    protected $fillable = ['name', 'description', 'is_active'];

    public static function createTable()
    {
        \Schema::create('categoriy_stubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public static function dropTable()
    {
        \Schema::dropIfExists('categoriy_stubs');
    }
}


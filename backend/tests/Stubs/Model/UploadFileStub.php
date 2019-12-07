<?php


namespace Tests\Stubs\Model;


use App\Model\Traits\UploadFiles;
use App\Model\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;


class UploadFileStub extends Model
{
    use UploadFiles;



    protected $table =  'upload_file_stup';
    protected $fillable = ['name', 'file1', 'file2'];
    public  static $filesFields = ['file1', 'file2'];

    public static function makeTable()
    {
        Schema::create('upload_file_stup', function($table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('file1')->nullable();
            $table->string('file2')->nullable();
            $table->timestamps();
        });
    }

    public static function dropTable()
    {
        Schema::dropIfExists('upload_file_stup');
    }


    protected function uploadDir()
    {
        return 1;
    }
}


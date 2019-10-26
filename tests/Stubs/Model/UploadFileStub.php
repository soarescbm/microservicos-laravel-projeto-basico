<?php


namespace Tests\Stubs\Model;


use App\Model\Traits\UploadFiles;
use Illuminate\Database\Eloquent\Model;


class UploadFileStub extends Model
{
    use UploadFiles;

//    protected $table =  'categoriy_stubs';
//    //protected $fillable = ['name', 'description', 'is_active'];
    public  static $filesFields = ['file1', 'file2'];

    protected function uploadDir()
    {
        return 1;
    }
}


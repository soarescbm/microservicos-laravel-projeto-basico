<?php


namespace Tests\Traits;


use Illuminate\Support\Facades\Storage;

trait TestStorage
{
    protected function deleteAllFiles()
    {
        $dirs = Storage::allDirectories();
        foreach ($dirs as $dir){
            $files = Storage::allFiles();
            Storage::delete($files);
            Storage::deleteDirectory($dir);
        }
    }
}

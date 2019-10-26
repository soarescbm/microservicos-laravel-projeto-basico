<?php

namespace App\Model\Traits;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadFiles {

    protected abstract function uploadDir();

    public function uploadFile(UploadedFile $file)
    {
        $file->store($this->uploadDir());
    }

    public function uploadFiles(array $files)
    {
        foreach ($files as $file){
            $this->uploadFile($file);
        }
    }

    public function deleteFiles(array $files)
    {
        foreach ($files as $file){
            $this->deleteFile($file);
        }
    }
    /**
     * @param $file string | UploadedFile
     */
    public function deleteFile($file)
    {
        $filename = ($file instanceof UploadedFile)? $file->hashName() : $file;
        Storage::delete("{$this->uploadDir()}/{$filename}");
    }

    public static function extractFiles (Array &$attributes = [])
    {
        $files = [];
        foreach (self::$filesFields as $file){
            if(isset($attributes[$file]) && $attributes[$file] instanceof UploadedFile) {
              $files[] = $attributes[$file];
              $attributes[$file] = $attributes[$file]->hashName();
            }
        }
        return $files;
    }
}

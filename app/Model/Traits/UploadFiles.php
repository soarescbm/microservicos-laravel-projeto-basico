<?php

namespace App\Model\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

trait UploadFiles {

    public $oldFiles = [];

    protected abstract function uploadDir();

    public static function bootUploadFiles()
    {

        static::updating(function ($model) {
            $fieldsUpdated = array_keys($model->getDirty());
            $filesUpdated = array_intersect($fieldsUpdated, self::$filesFields);
            $filesFiltered = Arr::where($filesUpdated, function ($fieldFile) use ($model) {
                return $model->getOriginal($fieldFile);
            });
            $model->oldFiles = array_map(function ($fieldFile) use ($model) {
                return $model->getOriginal($fieldFile);
            }, $filesFiltered);

        });

    }

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

    public function deleteOldFiles()
    {

        $this->deleteFiles($this->oldFiles);
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

    public function getFileUrl($file)
    {
        $path =  "{$this->id}/{$file}";
        if(Storage::exists($path)){
            return Storage::url($path);
        }

        return null;

    }

}

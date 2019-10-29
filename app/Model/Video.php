<?php

namespace App\Model;

use App\Model\Traits\UploadFiles;
use App\Model\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes, Uuid, UploadFiles;

    const RATING_LIST = ['L', '10', '12', '14', '16',  '18'];


    protected $fillable = [
        'title',
        'description',
        'year_launched',
        'opened',
        'rating',
        'duration',
        'video_file',
        'thumb_file',
        'banner_file',
        'trailer_file'
    ];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'id' => 'string',
        'year_launched' => 'integer',
        'opened' => 'boolean',
        'duration' => 'integer'
    ];
    public $incrementing = false;

    public static $filesFields = ['video_file', 'thumb_file', 'banner_file', 'trailer_file'];

    public function getVideoFileUrlAttribute()
    {
        return $this->getFileUrl($this->video_file);
    }

    public function getThumbFileUrlAttribute()
    {
        return $this->getFileUrl($this->thumb_file);
    }

    public function getBannerFileUrlAttribute()
    {
        return $this->getFileUrl($this->banner_file);
    }

    public function getTrailerFileUrlAttribute()
    {
        return $this->getFileUrl($this->trailer_file);
    }

    public static function create(array $attributes = [])
    {
        $files = self::extractFiles($attributes);
        try {
            \DB::beginTransaction();
            $obj = parent::query()->create($attributes);
            static::handleRelations($obj, $attributes);
            $obj->uploadFiles($files);
            //upload
            \DB::commit();
            return $obj;
        } catch (\Exception $e) {
            if(isset($obj)){
                $obj->deleteFiles($files);
            }
            \DB::rollBack();
            throw $e;
        }


    }

    public function update(array $attributes = [], array $options = [])
    {
        $files = self::extractFiles($attributes);
        try {
            \DB::beginTransaction();
            $saved = parent::update($attributes, $options);
            static::handleRelations($this, $attributes);
            if($saved){
                $this->uploadFiles($files);
            }
            \DB::commit();
            if($saved && count($files)){
                $this->deleteOldFiles();
            }
            return $saved;
        } catch (\Exception $e) {
            $this->deleteFiles($files);
            \DB::rollBack();
            throw $e;
        }


    }

    public static function handleRelations(Video $video, $attributtes)
    {

        if(isset($attributtes['categories_id'])){
            $video->categories()->sync($attributtes['categories_id']);
        }

        if(isset($attributtes['genres_id'])){
            $video->genres()->sync($attributtes['genres_id']);
        }

    }

    public function categories()
    {
        return $this->belongsToMany('App\Model\Category')->withTrashed();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class)->withTrashed();
    }

    protected function uploadDir()
    {
        return $this->id;
    }


}

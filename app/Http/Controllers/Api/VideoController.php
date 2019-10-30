<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\GenreResource;
use App\Http\Resources\VideoResource;
use App\Model\Video;
use App\Rules\GenreHasCategoriesRule;
use Illuminate\Http\Request;

class VideoController extends BasicCrudController
{
    private  $rules;

    public function __construct()
    {
        $this->rules = [
            'title' => 'required|max:255',
            'description' => 'required',
            'year_launched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' => "required|in:" . implode(',', Video::RATING_LIST),
            'duration' => 'required|integer',
            'categories_id' => 'required|array|exists:categories,id,deleted_at,NULL',
            'video_file' => 'nullable|mimetypes:video/mp4|max:50000000',
            'thumb_file' => 'nullable|mimetypes:image/jpeg,image/png|max:5000',
            'banner_file' => 'nullable|mimetypes:image/jpeg,image/png|max:10000',
            'trailer_file' => 'nullable|mimetypes:video/mp4|max:1000000',
            'genres_id' => [
                'required',
                'array',
                'exists:genres,id,deleted_at,NULL'
            ]
        ];
    }

    public function store(Request $request)
    {
        $this->addRuleIfGenreHasCategories($request);
        $validation =  $this->validate($request, $this->rulesStore());

        $obj = $this->model()::create($validation);
        $obj->refresh();
        $resource = $this->resource();
        return new $resource($obj);

    }

    protected function addRuleIfGenreHasCategories(Request $request)
    {
        $categoriesId =  $request->get('categories_id');
        $categoriesId = is_array($categoriesId)?  $categoriesId : [];
        $this->rules['genres_id'][] = new GenreHasCategoriesRule(
            $categoriesId
        );
    }


    public function update(Request $request, $id)
    {
        $obj = $this->findOrFail($id);
        $this->addRuleIfGenreHasCategories($request);
        $validation = $this->validate($request, $this->rulesUpdate());
        $obj->update($validation);
        $resource = $this->resource();
        return new $resource($obj);
    }



    protected function model()
    {
        return Video::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
       return  $this->rules;
    }

    protected function resource()
    {
        return VideoResource::class;
    }

    protected function resourceCollection()
    {
        return $this->resource();
    }
}

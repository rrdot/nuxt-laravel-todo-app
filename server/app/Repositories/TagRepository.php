<?php

namespace App\Repositories;

use App\Contracts\TagContract;
use App\Models\Tag;

class TagRepository implements TagContract
{
    protected $model;

    public function __construct(Tag $model)
    {
        $this->model = $model;
    }

    public function getAllTags()
    {
        return $this->model->all();
    }

    public function addTag($tag)
    {
        $this->model->create([
            'title' => $tag->title
        ]);
    }
}

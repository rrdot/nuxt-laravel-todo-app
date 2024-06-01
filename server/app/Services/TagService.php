<?php

namespace App\Services;

use App\Repositories\TagRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TagService
{
    protected $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTags()
    {
        return $this->repository->getAllTags();
    }

    public function addTag($tag)
    {
        // $this->validateData($tag);

        return $this->repository->addTag($tag);
    }

    private function validateData($data)
    {
        $rules = [
            'title' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
    }
}

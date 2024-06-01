<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TaskService
{
    protected $repository;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTasks()
    {
        return $this->repository->getAllTasks();
    }

    public function addTask($task)
    {
        // $this->validateData($task);

        return $this->repository->addTask($task);
    }

    public function updateTask($task, $id)
    {
        return $this->repository->updateTask($task, $id);
    }

    private function validateData($data)
    {
        $rules = [
            'title' => 'required',
            'position' => 'required',
            'tags' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
    }
}

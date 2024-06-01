<?php

namespace App\Repositories;

use App\Contracts\TaskContract;
use App\Models\Task;

class TaskRepository implements TaskContract
{
    protected $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    public function getAllTasks()
    {
        return $this->model->all();
    }


    public function addTask($task)
    {
        $this->model->create([
            'title' => $task->title,
            'position' => $task->position,
            'tags' => json_encode($task->tags)
        ]);
    }

    public function updateTask($task, $id)
    {
        $this->model->where('id', $id)->update($task);
    }
}

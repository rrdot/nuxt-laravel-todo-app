<?php

namespace App\Contracts;

interface TaskContract
{
    public function getAllTasks();
    public function addTask($task);
    public function updateTask($task, $id);
}

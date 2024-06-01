<?php

namespace App\Contracts;

interface TagContract
{
    public function getAllTags();
    public function addTag($tag);
}

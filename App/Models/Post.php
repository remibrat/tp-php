<?php

namespace App\Models;

use App\Core\Helper;
use App\Models\User;

class Post extends \Model
{
    protected $id;

    public function setId($id): self
    {
        $this->id=$id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function initRelation(): array
    {
        return [
            'author' => User::class
        ];
    }
}

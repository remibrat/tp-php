<?php
namespace App\controllers;

use App\models\Post;
use App\Managers\PostManager;

class PostController
{
    public function defaultAction()
    {
        $postManager = new PostManager();

        $post = $postManager->getUserPost(1);

        var_dump($post);
    }
}
<?php 
namespace App\Managers;

use App\Core\Manager;
use App\Models\User;

class PostManager extends Manager {

    public function __construct()
    {
        parent::__construct(Post::class, 'posts');
    }

    public function getUserPost(int $id)
    {
       return (new QueryBuilder())
            ->select('p.*, u.*')
            ->from('nfoz_post', 'p')
            ->join('nfoz_users', 'u')
            ->where('p.author = :iduser')
            ->setParameter('iduser', $id)
            ->getQuery()
            ->getArrayResult(Post::class)
            ;
    }
}
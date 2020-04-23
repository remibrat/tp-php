<?php 
namespace App\Managers;

use App\Core\DB;
use App\Models\User;

class UserManager extends DB {

    public function __construct()
    {
        parent::__construct(User::class, 'users');
    }


}
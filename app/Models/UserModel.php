<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
        protected $useSoftDeletes = true;          // <-- enable
    protected $deletedField   = 'deleted_at';  // <-- column name

    protected $allowedFields = [
        'name', 'email', 'password', 'phone',
        'age', 'gender', 'dob', 'profile_pic', 'address',
    ];
}
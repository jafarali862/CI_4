<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table          = 'books';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $deletedField   = 'deleted_at';
    protected $allowedFields  = [
        'title', 'author', 'isbn', 'category',
        'published_year', 'published_date', 'price',
        'description', 'cover_photo',
    ];
}
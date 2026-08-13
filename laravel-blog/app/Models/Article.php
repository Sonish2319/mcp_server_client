<?php

namespace App\Models;

use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected static function newFactory(): ArticleFactory
    {
        return ArticleFactory::new();
    }

    protected $fillable = [
        'title',
        'content',
        'author',
    ];
}
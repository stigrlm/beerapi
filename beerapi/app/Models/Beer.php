<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'price', 'rating_avg', 'reviews', 'image'];
}

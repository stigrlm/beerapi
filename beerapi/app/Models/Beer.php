<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beer extends Model
{
    protected $fillable = ['id', 'name', 'price', 'rating_avg', 'reviews', 'image'];
}
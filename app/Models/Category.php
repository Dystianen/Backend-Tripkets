<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['id_category', 'transportation_type'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'id_category';
}

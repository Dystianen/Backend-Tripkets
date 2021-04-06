<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    use HasFactory;
    protected $table = 'transportations';
    protected $primary_key = 'id';
    protected $fillable = ['id_category'];

    public function category(){
        return $this->belongsTo('App\Models\Category', 'id_category', 'id_category');
    }
}

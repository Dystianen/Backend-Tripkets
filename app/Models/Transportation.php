<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    use HasFactory;
    protected $table = 'transportations';
    protected $primaryKey = 'id_transportation';
    protected $fillable = ['id_transportation','id_category','transportation_name','stasiun_keberangkatan','stasiun_tujuan','price','departure','till'];

    public function category(){
        return $this->belongsTo('App\Models\Category', 'id_category', 'id_category');
    }
}

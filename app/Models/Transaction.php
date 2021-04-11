<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $fillable = ['id','id_user','id_transportation', 'check_in', 'status', 'jumlah'];
    protected $hidden = ['created_at', 'upadated_at'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'id_user', 'id');
    }

    public function transportation(){
        return $this->belongsTo('App\Models\Transportation', 'id_transportation', 'id_transportation');
    }

}

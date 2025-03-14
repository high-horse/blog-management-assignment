<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;
    //
    protected $table = "blogs";
    protected $fillable = [
        "title",
        "description",
        "content",
        "user_id",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeAuther($query){
        if(auth()->user()->hasRole('admin')){
            return $query;
        }
        return $query->where('user_id', auth()->user()->id);
    }
}

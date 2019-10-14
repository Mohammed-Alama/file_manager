<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class File extends Model
{
    protected $guarded = [];
//    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_name(Request $request)
    {
        return strtolower(preg_replace('/\s+/', '_', $request->user()->name));

    }

    public function isImage($type){
     return  in_array($type , ['jpg','png','jpeg','bmp','gif']);
    }

    public function isVideo($type){
     return in_array($type , ['mp4','avi','mkv']);
    }
}

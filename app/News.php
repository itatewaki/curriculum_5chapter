<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded = array('id'); // ブラックリストの指定

    // Validationのルール指定
    public static $rules = array(
        'title' => 'required',
        'body' => 'required',
    );

    // Newsモデルに関連付けを行う
    // $news->histories()でアクセスできるようにする
    public function histories() {
        return $this->hasMany('App\History');
    }
}

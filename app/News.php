<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    // ブラックリスト形式による割り当て許可
    protected $guarded = array('id');

    // Validationルールの定義
    public static $rules = array(
        'title' => 'required',
        'body' => 'required',
    );

    // Newsモデルへの関連付け
    public function histories()
    {
      return $this->hasMany('App\History');

    }
}

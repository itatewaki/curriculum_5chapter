<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    // ブラックリスト形式による割り当て許可
    protected $guarded = array('id');

    public static $rules = array(
        'news_id' => 'required',
        'edited_at' => 'required',
    );
}

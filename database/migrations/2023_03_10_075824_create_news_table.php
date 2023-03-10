<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');              // 主キー
            $table->string('title');                  // ニュースのタイトルを保存
            $table->string('body');                   // ニュースの本文を保存
            $table->string('image_path')->nullable(); // 画像のパスを保存(空白でも保存可)
            $table->timestamps();                     // 登録時のタイムスタンプ
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}

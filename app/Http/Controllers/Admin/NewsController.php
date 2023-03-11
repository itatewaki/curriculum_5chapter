<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// 以下を追記することでNews Modelが扱えるようになる
use App\News;

class NewsController extends Controller
{
    // add アクション
    public function add() {
        return view('admin.news.create');
    }

    // create アクション
    public function create(Request $request) {
        // Validationを行う
        $this->validate($request, News::$rules);

        $news = new News;
        $form = $request->all();

        // フォームから画像が送信されてきたら、保存して、$news->image_pathに画像のパスを保存する
        if (isset($form['image'])) {
            $path = $request->file('image')->store('public/image');
            $news->image_path = basename($path);
        } else {
            $news->image_path = null;
        }

        // フォームから送信されてきた _tokenを削除する
        unset($form['_token']);
        // フォームから送信されてきた imageを削除する
        unset($form['image']);

        // データベースに保存する
        $news->fill($form);
        $news->save();

        // admin/news/createにリダイレクトする
        return redirect('admin/news/create');
    }

    // 一覧表示のための index アクション
    public function index(Request $request) {
        $cond_title = $request->cond_title; // ニュースを検索するためのタイトル（最初は空）
        if ($cond_title != '') {
            // 検索されたら検索結果を取得する
            $posts = News::where('title', $cond_title)->get();
        } else {
            // それ以外はすべてのニュースを取得する
            $posts = News::all();
        }
        return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }
}

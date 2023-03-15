<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// 以下を追記することでNews Modelが扱えるようになる
use App\News;
// 変更履歴のためのHistoryモデル
use App\History;

// 時刻を扱うためのライブラリ
use Carbon\Carbon;

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

        // admin/newsにリダイレクトする（カリキュラムにはないがこのほうが良いと思う）
        return redirect('admin/news/');
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

    // 登録済みニュースの編集のための edit アクション
    public function edit(Request $request) {
        // News Modelからデータを取得する
        $news = News::find($request->id);
        if (empty($news)) {
            about(404);
        }
        return view('admin.news.edit', ['news_form' => $news]);
    }

    // ニュースの更新のための update アクション
    public function update(Request $request) {
        // Validationをかける
        $this->validate($request, News::$rules);
        // News Modelからデータを取得する
        $news = News::find($request->input('id'));
        // 送信されてきたフォームデータを格納する
        $news_form = $request->all();
        if ($request->input('remove')) {
            $news_form['image_path'] = null;
        } elseif ($request->file('image')) {
            $path = $request->file('image')->store('public/image');
            $news_form['image_path'] = basename($path);
        } else {
            $news_form['image_path'] = $news->image_path;
        }

        unset($news_form['_token']); // _tokenの削除
        unset($news_form['image']);  // imageの削除
        unset($news_form['remove']); // removeの削除

        // 該当するデータを上書きして保存する
        $news->fill($news_form)->save(); // $news->fill(); $news->save()の短縮形

        // 変更履歴の実装
        $history = new History;
        $history->news_id = $news->id;
        $history->edited_at = Carbon::now();
        $history->save();

        return redirect('admin/news/');
    }

    // ニュースの削除をするための delete アクション
    public function delete(Request $request) {
        // 該当する News Modelを取得
        $news = News::find($request->id);
        // 削除する
        $news->delete();

        return redirect('admin/news/');
    }
}

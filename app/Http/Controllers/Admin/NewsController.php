<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\News;
use App\History;

// 日付操作ライブラリ
use Carbon\Carbon;

class NewsController extends Controller
{
    public function add()
    {
        return view('admin.news.create');
    }

    public function create(Request $request)
    {
        // Varidationを行う
        $this->validate($request, News::$rules);
        $news = new News;
        $form = $request->all();
        
        // formに画像があれば、保存する
        if (isset($form['image'])) {
            // $path = $request->file('image')->store('public/image');
            // S3にアップロードする画像のファイルパスを取得
            $path = $this->store($request, $request->user()->id);
            $news->image_path = basename($path);
        } else {
            $news->image_path = null;
        }
        
        // フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        // フォームから送信されてきたimageを削除する
        unset($form['image']);
        
        // データベースに保存する
        $news->fill($form);
        $news->save();
        
        return redirect('admin/news/create');
    }
    
    public function index(Request $request)
    {
        $cond_title = $request->cond_title;
        if ($cond_title != '') {
            // 検索されたら検索結果を取得する
            $posts = News::where('title', $cond_title)->get();
        } else {
            // それ以外はすべてのニュースを取得する
            $posts = News::all();
        }
        return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }
    
    public function edit(Request $request)
    {
        // News Modelからデータを取得する
        $news = News::find($request->id);
        if (empty($news)) {
            abort(404);
        }
        return view('admin.news.edit', ['news_form' => $news]);
    }
    
    public function update(Request $request)
    {
        // Validationをかける
        $this->validate($request, News::$rules);
        // News Modelからデータを取得する
        $news = News::find($request->id);
        // 送信されてきたフォームデータを格納する
        $news_form = $request->all();
        if ($request->remove == 'true') {
            $news_form['image_path'] = null;
        } elseif ($request->file('image')) {
            
            // $path = $request->file('image')->store('public/image');
            // s3にアップロード
            $path = $this->store($request, $request->user()->id);
            
            $news_form['image_path'] = basename($path);
        } else {
            $news_form['image_path'] = $news->image_path;
        }
        
        unset($news_form['_token']);
        unset($news_form['image']);
        unset($news_form['remove']);
        // 該当するデータを上書きして保存する
        $news->fill($news_form)->save();
        
        $history = new History;
        $history->news_id = $news->id;
        $history->edited_at = Carbon::now();
        $history->save();
        
        return redirect('admin/news/');
    }
    
    public function delete(Request $request)
    {
        // 該当するNews Modelを取得
        $news = News::find($request->id);
        // 削除する
        $news->delete();
        return redirect('admin/news/');
    }
    
    // S3に画像をアップロード
    public function store(Request $request, int $id)
    {
        $this->validate($request, ['image' => 'required|image']);

        $image = $request->file('image');

        /**
         * 自動生成されたファイル名が付与されてS3に保存される。
         * 第三引数に'public'を付与しないと外部からアクセスできないので注意。
         */
        // $path = Storage::disk('s3')->putFile('/', $image, 'public');

        /* 上記と同じ */
        // $path = $image->store('myprefix', 's3');

        /* 名前を付与してS3に保存する */
        $filename = $request->file('image')->getClientOriginalName();
        $filename = $id . '-' . $filename;
        $path = Storage::disk('s3')->putFileAs('/', $image, $filename, 'public');

        /* ファイルパスから参照するURLを生成する */
        $url = Storage::disk('s3')->url($path);

        // return redirect()->back()->with('s3url', $url);
    }
    
    // public function upload(Request $request, int $id)
    // {
    //     // formから送信されたimgファイルを読み込む
    //     $file = $request->file('image');
    //     $filename = $request->file('image')->getClientOriginalName();
        
    //     // 画像の名前を取得
    //     $path = $request->file('image')->storeAs('public', $filename);
        
        
    //     // var_dump($filename);die;
    //     // s3のuploadsファイルに追加
    //     Storage::disk('s3')->put('/', $file, 'public');
    //     // 画像のURLを参照
    //     $url = Storage::disk('s3')->url($filename);
        
        
    // }
    
}
<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            $path = $request->file('image')->store('public/image');
            $news->image_path = basename($path);
        } else {
            $news->image_path = null;
        }
        
        unset($form['_token']);
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
            $path = $request->file('image')->store('public/image');
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
    
    public function upload(Request $request, int $id)
    {
        $ext = substr($filename, strrpos($_FILES['img_path']['name'], '.') + 1);
        if(strtolower($ext) !== 'png' && strtolower($ext) !== 'jpg' && strtolower($ext) !== 'gif'){
            echo '画像以外のファイルが指定されています。画像ファイル(png/jpg/jpeg/gif)を指定してください';
            exit();
        }
        
        // 読み込みの際のキーとなるS3上のファイルパスを作る(作り方は色々あると思います)
        $tmpname = str_replace('/tmp/', '', $_FILES['img_path']['tmp_name']);
        $new_filename = 'profiles/'.$id.'-'.time().'-'.$tmpname.'.'.$ext;
        
        $s3client = S3Client::factory([
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
            'region' => 'us-east-2',
            'version' => 'latest',
        ]);
        // パケット名を指定
        $bucket = getenv('S3_BUCKET_NAME')?: die('No "S3_BUCKET_NAME" config var in found in env!');
        // アップロードするファイルを用意
        $image = fopen($_FILES['img_path']['tmp_name'],'rb');
        
        // 画像のアップロード（各項目の説明は後述）
        $result = $s3client->putObject([
            'ACL' => 'public-read',
            'Bucket' => $bucket,
            'Key' => $new_filename,
            'Body' => $iamge,
            'ContentType' => mime_content_type($_FILES['img_path']['tmp_name']),
        ]);
        
        // 読み取り専用のパスを返す
        $path = $resutl['ObjectURL'];
        
        // パスをDBに保村（ここの詳細処理は今回は記述しません）
        $this->userRepository->updateUserProfsById($id, 'img_path', $path);
    }
}
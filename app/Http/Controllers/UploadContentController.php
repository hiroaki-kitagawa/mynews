<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadContentController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        $this->validate($request, ['image' => 'required|image']);

        $image = $request->file('image');

        /**
         * 自動生成されたファイル名が付与されてS3に保存される。
         * 第三引数に'public'を付与しないと外部からアクセスできないので注意。
         */
        $path = Storage::disk('s3')->putFile('/', $image, 'public');

        /* 上記と同じ */
        // $path = $image->store('myprefix', 's3');

        /* 名前を付与してS3に保存する */
        // $filename = 'hoge.jpg';
        // $path = Storage::disk('s3')->putFileAs('myprefix', $image, $filename, 'public');

        /* ファイルパスから参照するURLを生成する */
        $url = Storage::disk('s3')->url($path);

        // return redirect()->back()->with('s3url', $url);
    }
}

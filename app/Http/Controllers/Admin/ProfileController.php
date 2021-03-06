<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Profile Modelを使えるようにする。書き忘れてエラーが出ていた！
use App\Profile;
use App\ProfileHistory;

// 日付操作ライブラリ
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function add()
    {
        return view('admin.profile.create');
    }
    
    public function create(Request $request)
    {
        // Varidation
        $this->validate($request, Profile::$rules);
        $profile = new Profile;
        $form = $request->all();
        
        // フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        
        // データベースに保存する
        $profile->fill($form);
        $profile->save();
        
        return redirect('admin/profile/create');
    }
    
    public function edit(Request $request)
    {
        // Profile Modelからデータを取得する
        // getで取得するため、URLにパラメータの指定が必要
        $profile = Profile::find($request->id);
        if (empty($profile)) {
            abort(404);
        }
        
        return view('admin.profile.edit', ['profile_form' => $profile]);
    }
    
    public function update(Request $request)
    {
        // Validationをかける
        $this->validate($request, Profile::$rules);
        // Profile Modelからデータを取得する
        $profile = Profile::find($request->id);
        // 送信されてきたフォームデータを格納する
        $profile_form = $request->all();
        
        unset($profile_form['_token']);
        // 該当するデータを上書きして保存する
        $profile->fill($profile_form)->save();
        
        // 編集履歴の記録と参照
        $profile_history = new ProfileHistory;
        $profile_history->profile_id = $profile->id;
        $profile_history->edited_at = Carbon::now();
        $profile_history->save();
        
        return redirect('admin/profile/edit' . '?id=' . $request->id);
    }
    
    
}

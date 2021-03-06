@extends('layouts.profile')
@section('title', 'マイプロフィール')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h2>プロフィール作成</h2>
                <form action="{{ action('Admin\ProfileController@create') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if (count($errors) > 0)
                        <ul>
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="form-group row">
                        <label class="col-md-2" for="name">氏名</label>
                        
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2" for="gender">性別</label>
                        <div class="col-md-10">
                        
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="gender" value="male">
                              <label class="form-check-label">男性</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="gender" value="female">
                              <label class="form-check-label">女性</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="gender" value="others">
                              <label class="form-check-label">その他</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2" for="hobby">趣味</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="hobby" value="{{ old('hobby') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2" for="introduction">自己紹介欄</label>
                        <div class="col-md-10">
                            <textarea class="form-control" name="introduction" rows="20">{{ old('introduction') }}</textarea>
                        </div>
                    </div>
                    
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary btn-block" value="送信">
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
@endsection
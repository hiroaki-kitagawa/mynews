@extends('layouts.front');

@section('content')
    <div class="container">
        <h2>プロフィール</h2>
        <hr color="#c0c0c0">
        
            <div class="row">
                <div class="headline col-md-10 mx-auto">
                    <div class="row">
                        <div class="col-md-10">
                            <dl>
                                <dt>名前</dt>
                                <dd>{{ $profile->name }}</dd>
                            </dl>
                            <dl>
                                <dt>性別</dt>
                                <dd>{{ $profile->gender }}</dd>
                            </dl>
                            <dl>
                                <dt>趣味</dt>
                                <dd>{{ $profile->hobby }}</dd>
                            </dl>
                            <dl>
                                <dt>自己紹介</dt>
                                <dd>{{ $profile->introduction }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        
        <hr color="#c0c0c0">
    </div>
    </div>
@endsection
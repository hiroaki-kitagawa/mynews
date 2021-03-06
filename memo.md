# Laravelメモ
## PHP/Laravel 08 ControllerとRoutingの関係について理解しよう
### Rootingの役割
WebサイトにアクセスしてきたURLに応じて、表示するページや実行する処理を振り分ける。

### Controllerの役割
HTTPリクエストに対応する処理を実行する。  
DBアクセスやサービスの中枢となる機能の実行やビュー表示などを制御する。

### ControllerとRoutingの役割
WebサイトにアクセスしてきたURlに応じて、Rootingで定義されたControllerのアクションを実行する。
DBアクセスやビュー表示などActionに実装された機能を実行する。

### 疑問・質問
Webで調べた特徴の中に大規模な開発には向かないというものがあったが、
Laravelが向かなくなる規模というのは、具体的にはどの程度のサービスになるのか。


## PHP/Laravel 09 Routingについて理解する
### URLとControllerやActionを紐付ける機能を何といいますか？
Routing

### あなたが考える、group化をすることのメリットを考えてみてください。
ユーザの種類によってアクセス可能なページや利用可能な機能を分けたい場合、
Rootingで管理しやすくなる。

## PHP/Laravel 10 ControllerとViewが連携できるようにしよう
### 1 Viewは何をするところでしょうか。簡潔に説明してみてください。
Controllerの指示に基づき、表示するページやデータを生成する。

### 2 プログラマーがhtmlを書かずにPHPなどのプログラミング言語やフレームワークを使う必要があるのはどういった理由でしょうか。
汎用的な機能や設計を簡単に作成できるため、コード量が減り、メンテナンスしやすくなることで生産性や品質が向上する。

## PHP/Laravel 11 Bladeテンプレートの継承とLaravel Mixの使い方を活用してみよう
### 1.Bladeテンプレートで、埋め込みたい箇所に利用するワードは何だったでしょうか？
@yield

### 2.Webpackで使われているBootstrapやSCSSはどういったものか、調べられる範囲で調べてみましょう。
#### Bootstrap
CSSフレームワーク。
汎用性の高いスタイルがあらかじめ定義されており、ルールに沿って記述することで一定品質のスタイルを記述できる。
デザインスキルに乏しいプログラマーやエンジニアでも綺麗な見た目のページを作れる。

#### SCSS
CSSを効率的に記述できるCSSプロプロセッサ。
インデント、カッコで括る、変数や関数など、プログラミングで冗長な記述を減らすことができる。


## PHP/Laravel 13 ニュース投稿画面を作成しよう
### 1. GETメソッドとPOSTメソッドについて調べ、どのような違いがあるか説明してください。
### 2. 【応用】 GET/POSTメソッド以外にどのようなメソッドがあるか、またどのように使われるかを説明してください。
HTTP通信でサーバから情報を取得する際に使用する。  
URLの後に送信する情報をテキストデータとして付加できる。
他人に見られたくない情報の送信には向いていない。

POSTはサーバへ情報を登録する際に使用する。
GETよりもデータ送信量制限が多く、バイナリデータも送信できる。
他人に見られたくない情報を送信する際にも使用する。


## PHP/Laravel 14 投稿データを保存しよう
### 1.データベースとテーブルの関係を説明してください。
テーブルは行と列の二次元情報で特定のデータを保存する領域。
データベースは、ひとつないし複数のテーブルで構成されている。


### 2.テーブルを作成するときに事前にしなければならないことはなんですか？
列毎にどのようなデータを保存するのかを事前に定義する必要がある。
主キー指定、データ形式、文字数、空白の可否など


### 3.Validationはどのような役目をしていますか？
Modelでデータを保存するが前に、フォームから送信されたデータが正しいかどうか検証する。


## PHP/Laravel 17 編集履歴を実装しよう
### 1.プロフィールの更新履歴を保存する仕組みを作る手順
1. プロファイル編集履歴テーブルを作成する
  `php artisan make:migration create_profile_histories_table`
2. Migrationファイルを編集する
  - id,profile_id,edited_atを定義する
  - `php artisan migrate`
3. ProfileHistory Modelを作成する
  - `php artisan make:model ProfileHistory`
  - idおよびprofile_id,edited_atの配列を定義する
4. Profile Modelとの関連を定義する
  ProfileモデルにhasManyで関連付けする  
  ```
  public function histories()
    {
      return $this->hasMany('App\ProfileHistory');

    }  
  ```

5. 編集履歴の記録と参照
  ProfileControllerのupdate Actionを編集する。  
  ```
  $history = new ProfileHistory;
  $history->profile_id = $profile->id;
  $history->edited_at = Carbon::now();
  $history->save();
  ```
6. ProfileControllerの編集
  - Profileモデルの使用を宣言 `use App\Profile` 追記
  - 日付操作ライブラリ `use Carbon\Carbon;` 追記
7. Profile編集画面で変更履歴を参照できるようadmin/profile/edit.blade.phpを編集する
  
8. 


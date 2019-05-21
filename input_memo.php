<?php
  require_once("../../lib/util.php");
  require_once("function.php");
 ?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>簡易的な掲示板</title>
<link href ="../../css/style.css" rel = "stylesheet">
</head>

<body>
<style>
  header{
    background-color: black;
    color: white;
  }
  img{
    width: 500px;
  }

</style>

<header>
  <h1>しょぼいけいじばん(~ω~)</h1>
</header>

<div>
<?php
$filename = "memo.txt";
try{
    //ファイルオブジェクトを作る（rb　読み込み専用）
    $fileObj = new SplFileObject($filename,"rb");
  }
  catch(Exception $e){
    echo'<span class="error">エラーがありました。</span>',"<br>";
    echo $e->message();
    exit();
  }
  //ファイルロック（共有ロック）
  $fileObj->flock(LOCK_SH);
  //ストリングを読む
  $readdata = $fileObj->fread($fileObj->getSize());
  //アンロック
  $fileObj->flock(LOCK_UN);

  if(!($readdata === false)){
  //htmlエスケープ（<br>を挿入する前に行う。）

  $readdata =es($readdata);
  //改行コードの前に<br>を挿入する。
  $readdata_br = nl2br($readdata,false);
  echo"{$filename}を読み込みました。","<br><hr>";

  $readdata_br_img = img_tag_decode($readdata_br);

    echo url2link($readdata_br_img),"<hr>";


}
  else{
    //ファイルエラー
    echo '<span class="error">ファイルを読み込みが出来ませんでした。<\span>';
  }

 ?>

</div>

<div>

<!-- 入力フォームを作る -->
<form method="POST" action="write_memofile.php"  enctype="multipart/form-data">
  <ul>
    <li><span>memo：</span>
      <textarea name="memo" cols="25" rows="4" maxlength="100" placeholder="メモを書く"></textarea>
    </li>

    <li><span>名前　：</span>
      <input name="name" type="text" placeholder="投稿者の名前" cols="25" value="名無し">
    </li>
    <li><span>画像　：</span>
      <input  type="file" name="upimg" accept="image/*">
    </li>
    <li><input type="submit" value="送信する"></li>
  </ul>
</form>

<?php
$url = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
echo"<p>{$url}</p>";
?>

</div>
</body>
</html>

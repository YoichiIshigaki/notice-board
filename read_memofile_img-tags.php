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
      margin-top: 0;
    }
    img{
      width: 500px;
      height: auto;
    }

  </style>

  <header>
    <h1>しょぼいけいじばん(~ω~)</h1>
  </header>

<div>
  <?php
    echo"aaa";
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
      echo"{$filename}を読み込みました。","<br>";


      $readdata_br_img = img_tag_decode($readdata_br);

        echo url2link($readdata_br_img),"<hr>";

      echo '<a href="input_memo.php">メモ入力ページへ。</a>';
    }
      else{
        //ファイルエラー
        echo '<span class="error">ファイルを読み込みが出来ませんでした。<\span>';
      }
  ?>

</div>
</body>
</html>

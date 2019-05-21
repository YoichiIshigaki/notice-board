<?php
//ポストされたテキスト文を取り出す。

if(empty($_POST["memo"]) && empty($_FILES["upimg"])){
  //POSTされた値がないとき（０の場合も含む）
  //リダイレクト（メモ入力ページへ戻る。）
  $url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"]);
  header("Location:".$url."/input_memo.php");
  exit();
}
if(empty($_POST["name"])){
  //名前の名無しを追加
 $_POST['name'] = "名無し";
}

$img_name = $_FILES['upimg']['name'];
$img_tmpname = $_FILES['upimg']['tmp_name'];

$allfile = glob('./upload/*');

if(in_array("./upload/".$img_name,$allfile)){

  $pattern = "/([\-_.!~*\'\(\)\[\]\{\}\.a-zA-Z0-9;\/?@&=\+\$,%　# ]+?)\.(jpg|jpeg|gif|png|svg|tiff|JPG|JPEG)/";
  $result = preg_match($pattern,$img_name,$match);

  $img_name = $match[1]."-".(string)mt_rand().".".$match[2];
}
   //画像を転送
   move_uploaded_file($img_tmpname, './upload/' . $img_name);

   if(! empty($_FILES['upimg']['name'])){
        $img_tag = 'upload/'.$img_name;
   }
   else{
      $img_tag = '';
   }


$memo = $_POST["memo"];
$name = $_POST["name"];

$date = date("Y/n/j G:i:s",time());
$writedata = "___\n".$date."\t名前：".$name."\n".$memo."\n".$comment."\n".$img_tag."\n";
//メモファイル
$filename = "memo.txt";

try{
  //ファイルオブジェクトを作る。（読み書き、追記モード）
  $fileObj = new SplFileObject($filename,"a+b");
}
catch(Exception $e){
  echo'<span class="error">エラーが出ました。</span>';
  echo $e->message();
  exit();
}

//ファイルロック
$fileObj->flock(LOCK_EX);
//メモを追記する
$fileObj->fwrite($writedata);
//アンロック
$fileObj->flock(LOCK_UN);

//リダイレクト（メモを読むページへ）
$url = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
header("Location:".$url."/read_memofile_img-tags.php");


// ?>

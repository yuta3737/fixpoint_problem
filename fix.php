<?php
// ファイルを変数に格納
$filename = 'log.txt';
// fopenでファイルを開く（'r'は読み込みモードで開く）
$fp = fopen($filename, 'r');
//  $txtの全体
$stock_all = array(); 
//  $txtの全体のうちIPアドレスのみ
$stock_part = array(); 
// whileで行末までループ処理
while (!feof($fp)) {
  // fgetsでファイルを読み込み、変数に格納
  $txt = fgets($fp);
  $error = '-';
  
  if ( strpos( $txt,$error) === false ) {
    $text_box = preg_split('/(,|-)/', $txt);
    if(in_array($text_box[1], $stock_part)) {
        //   エラーからの復帰
        // $stock_partに$text_box[1]が含まれるか検索
        $delete_key = array_search( $text_box[1], $stock_part);
        // 復帰したので$stock_partから$text_box[1]を消す
        unset($stock_part[$delete_key]);
        // $stock_allに$text_box[1]が含まれるか検索
        $num_key = array_search( $text_box[1], array_column( $stock_all, 1));
        // 故障時間計算
        $break_time = $text_box[0] - $stock_all[$num_key][0];
        // 故障時間表示
        echo '故障時間';
        echo '(YYYYMMDDhhmmssの形式。年＝YYYY（4桁の数字）、月＝MM（2桁の数字。以下同様）、日＝DD、時＝hh、分＝mm、秒＝ss)<br>';
        echo $break_time.'<br>';
        
        echo $text_box[1].'が直った。<br>';
    }else{
        //   正常　前回も正常
    }
  } else{
     echo "エラー";
    //  文字を時間とIPアドレスに分割する
     $text_box = preg_split('/(,|-)/', $txt);
    //  エラーが起きているIPアドレスを表示する
     echo $text_box[1].'<br>';
    //  エラーが起きたIPアドレスを格納する
     array_push($stock_part,$text_box[1]);
    // エラーのIPアドレスに重複がないかチェック
     $uniqueArray = array_unique($stock_part);
     if(count($uniqueArray) !== count($stock_part)) {
        // 重複があるので$txtの全体を追加しない
        // 重複したものを削除
        array_unique($stock_part);
    }else{
        //  重複がないので$txtの全体を追加
        array_push($stock_all,$text_box);
    }

  }
}
// fcloseでファイルを閉じる
fclose($fp);

<?php
if (isset($_GET["link"]) && preg_match('/^[a-zA-Z0-9\-\_]+$/', $_GET['link'])) {
  try {
    // DBへ接続
    $dbh = new PDO("sqlite:./r.db", null, null, array(PDO::ATTR_EMULATE_PREPARES=>false));
  
    // テーブルのデータを取得
    $sql = 'SELECT * FROM redirect WHERE rdName = ?';
    $param = array($_GET["link"]);
    $data = $dbh->prepare($sql);
    $data->execute($param);
  
    if( !empty($data) ) {
      foreach( $data as $value ) {
        //var_dump($value);
      }
    }

    // カウントアップ
    $countUp = (int)$value["rdPvCount"] + 1;
    $upSql = 'UPDATE redirect SET rdPvCount = ? WHERE rdId = ?';
    $upParam = array($countUp, (int)$value["rdId"]);
    $upData = $dbh->prepare($upSql);
    $upData->execute($upParam);
    
    // リンク
    header("Location:" . $value["rdUri"], 301);
  
  } catch(PDOException $e) {
    echo $e->getMessage();
    die();
  }
  
  // 接続を閉じる
  $dbh = null;
}

<?php
if (isset($_GET["link"]) && preg_match('/^[a-zA-Z0-9\-\_]+$/', $_GET['link'])) {
  try {
    // DBへ接続
    $dbh = new PDO("sqlite:./r.db", null, null, array(PDO::ATTR_EMULATE_PREPARES=>false));
  
    // テーブルのデータを取得
    $sql = 'SELECT * FROM redirect WHERE rdName = ? AND rdOpen = ?';
    $param = array($_GET["link"], "true");
    $data = $dbh->prepare($sql);
    $data->execute($param);
    $result = $data->fetchAll();

    if (count($result) > 0) {
      foreach($result as $value) {
        //var_dump($value);
        // カウントアップ
        $countUp = (int)$value["rdPvCount"] + 1;
        $upSql = 'UPDATE redirect SET rdPvCount = ? WHERE rdId = ?';
        $upParam = array($countUp, (int)$value["rdId"]);
        $upData = $dbh->prepare($upSql);
        $upData->execute($upParam);

        // 接続を閉じる
        $dbh = null;

        // リンク
        header("Location:" . $value["rdUri"], 301);
        exit;
      }
    } else {
      header("HTTP/1.1 404 Not Found");
      echo "<!DOCTYPE html>
<title>Not Found.</title>
<p>Not Found.</p>";
    }
  } catch(PDOException $e) {
    echo $e->getMessage();
    die();
  }
  
  // 接続を閉じる
  $dbh = null;
}

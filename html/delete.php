<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $csrf_token = $_POST['csrf_token'];
  // ワンタイムトークンの一致を確認
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    // トークンが一致しなかった場合
    die('データベースの接続に失敗しました。');
  }
}
try {
  $path = explode("/", $_SERVER['REQUEST_URI']); //分割処理
  $last = end($path); //最後の要素を取得

  //SQL インジェクション対策
  $mysqli = new mysqli('db', 'root', 'secret', 'sample');
  // SQL文を作成します（パラメータはありません）
  $stmt = $mysqli->prepare("DELETE FROM questionnaire WHERE id = ?");
  // ここでパラメータに実際の値となる変数を入れる。
  // iは、パラメータの型（int）を指定
  $stmt->bind_param('i', $last);
  /* プリペアドステートメントを実行 */
  $stmt->execute();
  /* ステートメントと接続を閉じる */
  $stmt->close();

  /* 接続を閉じる */
  $mysqli->close();

  // ホーム画面にリダイレクト
  header('Location: http://' . $_SERVER['HTTP_HOST']);
  exit;
} catch (Exception $e) {
  echo $e->getMessage();
  die();
}

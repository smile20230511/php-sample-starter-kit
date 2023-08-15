<?php
/* $id = $_GET['id'];

    $pdo = db_connect();

    $sql;

     */
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
  //echo "p";
  $path = explode("/", $_SERVER['REQUEST_URI']); //分割処理
  $last = end($path); //最後の要素を取得
  /*
        $sql = "DELETE FROM questionnaire WHERE id = $last";
        $link = mysqli_connect('db', 'root', 'secret', 'sample');
        if ($link == null) {
            die("データベースの接続に失敗しました。");
        }
        $data = mysqli_query($link, $sql);
        */

  /*
        // ワンタイムトークンの一致を確認
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // トークンが一致しなかった場合
        die('データベースの接続に失敗しました。');
        }
      */
  //SQL インジェクション対策
  $mysqli = new mysqli('db', 'root', 'secret', 'sample');
  // SQL文を作成します（パラメータはありません）
  $stmt = $mysqli->prepare("DELETE FROM questionnaire WHERE id = ?");
  // ここでパラメータに実際の値となる変数を入れます。
  // sssdのところは、それぞれパラメータの型（string, string, string, double）を指定しています。
  $stmt->bind_param('i', $last);
  /* プリペアドステートメントを実行します */
  $stmt->execute();
  /* ステートメントと接続を閉じます */
  $stmt->close();

  /* 接続を閉じます */
  $mysqli->close();

  // ホーム画面にリダイレクト
  header('Location: http://' . $_SERVER['HTTP_HOST']);
  exit;
} catch (Exception $e) {
  echo $e->getMessage();
  die();
}


/* try {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
      
        // index.phpにリダイレクト
        header("Location:index.php");
        exit;
      } catch (PDOException $e) {
        echo $e->getMessage();
        die();
      } */
/*
      try {
        $sql = "DELETE FROM questionnaire WHERE id = $last";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("$last", $id);
        $stmt->execute();
      
        // index.phpにリダイレクト
        header("Location:index.php");
        exit;
      } catch (PDOException $e) {
        echo $e->getMessage();
        die();
      }*/
?>
<!-- <!DOCTYPE html>
<html lang="ja">
  <body>
    <form action="index.php" method="post">

    <a href = "delete/<?= $_GET['id'] ?>">

    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />

    </form>
  </body>
</html> -->
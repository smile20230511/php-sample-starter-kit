<?php
// POST のときはデータの投入を実行
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // データベースへの接続
    $link = mysqli_connect('db', 'root', 'secret', 'sample');
    if ($link == null) {
        die("データベースの接続に失敗しました。");
    }
    
    // データの投入
    $sql = "INSERT INTO `questionnaire` (`username`, `participation_id`, `comment`) VALUES ('"
        . $_POST['username'] . "', " . $_POST['participation_id'] . ", '" . $_POST['comment'] . "');"; 
    mysqli_query($link, $sql);
    
    // ホーム画面にリダイレクト
    header('Location: http://' . $_SERVER['HTTP_HOST']);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケート入力</title>
</head>
<body>
    <h1 class="my-3">新人歓迎会参加アンケート</h1>
    <form method="POST" action="./add.php">
        <div>
            <label for="username">氏名</label>
            <input type="text" name="username" />
        </div>
        <div>
            <label for="participation_id">新人歓迎会に参加しますか？:</label>
            <select name="participation_id">
                <option value="1">参加！</option>
                <option value="2">不参加で。。。</optiohn>
            </select>
        </div>
        <div>
            <label for="comment">コメント:</label>
            <textarea name="comment"></textarea>
        </div>
        <div>
            <a href="/">戻る</a>
            <button type="submit">送信</button>
        </div>
    </form>
</body>
</html>
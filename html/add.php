<?php
// セッションの利用を開始
session_start();
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    // ワンタイムトークン生成
    $toke_byte = openssl_random_pseudo_bytes(16);
    $csrf_token = bin2hex($toke_byte);
    // トークンをセッションに保存
    $_SESSION['csrf_token'] = $csrf_token;
}

// POST のときはデータの投入を実行
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // エラー内容
    $name_error1 = "";
    $name_error2 = "";
    $comment_errors = "";

    $name_value = $_POST['name'];
    $participate_id_value =
        $_POST['participate_id'];
    $comment_value = $_POST['comment'];

    if (isset($_POST)) {
        //氏名
        if (empty($_POST['name'])) {
            $name_error1 = '氏名は必須項目です。';
        } elseif (mb_strlen($_POST['name']) > 20) {
            $name_error2 = 'ユーザー名は20文字以内で入力してください。';
        }
        //コメント
        if ($_POST['comment'] != null && mb_strlen($_POST['comment']) > 5) {
            $comment_errors = 'コメントは100文字以内で入力してください。';
        }
    }

    if (empty($name_error1) && empty($name_error2) && empty($comment_errors)) {
        //SQL インジェクション対策
        $mysqli = new mysqli('db', 'root', 'secret', 'sample');

        // ワンタイムトークンの一致を確認
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            echo $_POST['csrf_token'];
            // トークンが一致しなかった場合
            die('データベースの接続に失敗しました。');
        }
        // SQL文を作成（パラメータはなし）
        $stmt = $mysqli->prepare("INSERT INTO questionnaire VALUES (?, ?, ?, ?)");
        // ここでパラメータに実際の値となる変数を入れる。
        // isisは、それぞれパラメータの型（int, string, int, string）を指定。
        $stmt->bind_param('isis', $_POST['id'], $_POST['name'], $_POST['participate_id'], $_POST['comment']);
        /* プリペアドステートメントを実行 */
        $stmt->execute();
        /* ステートメントと接続を閉じる */
        $stmt->close();

        /* 接続を閉じる */
        $mysqli->close();
        //ホーム画面にリダイレクト
        header('Location: http://' . $_SERVER['HTTP_HOST']);
    }
    // ワンタイムトークン生成
    $toke_byte = openssl_random_pseudo_bytes(16);
    $csrf_token = bin2hex($toke_byte);
    // トークンをセッションに保存
    $_SESSION['csrf_token'] = $csrf_token;

    $name_value = $_POST['name'];
    $participate_id_value =
        $_POST['participate_id'];
    $comment_value = $_POST['comment'];
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケート入力!!!</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-3">
                <h1>新人歓迎会参加アンケート</h1>
                <form method="POST">
                    <div>
                        <label for="name" class="form-label">氏名</label>
                        <input type="text" name="name" class="form-control <?php if (!empty($name_error1) || !empty($name_error2)) echo 'is-invalid'; ?>" id="name" value="<?php if (!empty($name_value)) echo $name_value; ?>">
                        <div id=" commentFeedback" class="invalid-feedback">
                            <?php if (!empty($name_error1)) {
                                echo $name_error1;
                            }
                            if (!empty($name_error2)) {
                                echo $name_error2;
                            } ?>
                        </div>
                    </div>
                    <div>
                        <label for="participate_id" class="mt-3">新人歓迎会に参加しますか？:</label>
                        <select name=" participate_id" class="form-control mb-3">
                            <option value="0" <?php if (!empty($participate_id_value) && $participate_id_value === '0') echo 'selected'; ?>>参加！</option>
                            <option value="1" <?php if (!empty($participate_id_value) && $participate_id_value === '1') echo 'selected'; ?>>不参加で。。。</optiohn>
                        </select>
                    </div>
                    <div>
                        <label for="comment">コメント:</label>
                        <textarea name="comment" class="form-control <?php if (!empty($comment_errors)) echo 'is-invalid'; ?>" id="comment"><?php if (!empty($comment_errors)) echo $comment_value; ?></textarea>
                        <div id=" commentFeedback" class="invalid-feedback">
                            <?php if (!empty($comment_errors)) {
                                echo $comment_errors;
                            }
                            ?>
                        </div>
                        <!-- CSRF対策の追加 -->
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
                        <div>
                            <a href="/" class="btn btn-secondary mt-3">戻る</a>
                            <button type="submit" class="btn btn-secondary mt-3">送信</button>
                        </div>
                    </div>
            </div>
            </form>
            <p>
                <?php if (!empty($name_error1) && !empty($name_error2) && !empty($comment_errors)) {
                    $name_value = $_POST['name'];
                    $comment_value = $_POST['comment'];
                }
                ?>
            </p>
        </div>
    </div>
    </div>
</body>

</html>
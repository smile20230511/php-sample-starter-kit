<?php
// POST のときはデータの投入を実行
if ($_SERVER['REQUEST_METHOD'] === "GET") {

    // データベースへの接続
    $link = mysqli_connect('db', 'root', 'secret', 'sample');

    if ($link == null) {
        die("データベースの接続に失敗しました。");
    }
    // データの投入
    $sql = "SELECT * FROM questionnaire";

    $data = mysqli_query($link, $sql);
    session_start();
    $toke_byte = openssl_random_pseudo_bytes(16);
    $csrf_token = bin2hex($toke_byte);
    $_SESSION['csrf_token'] = $csrf_token;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケート結果!!!</title>
</head>

<body>
    <div class="col-md-8 offset-md-2 mt-3">
        <h1>新人歓迎会参加アンケート結果</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">氏名</th>
                    <th scope="col">参加するかどうか</th>
                    <th scope="col">コメント</th>
                    <th scope="col">　</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $_GET) : ?>
                    <tr>
                        <td><?php echo $_GET['id']; ?></td>
                        <td><?php echo $_GET['name']; ?>
                        </td>
                        <td><?php
                            if ($_GET['participate_id'] == 0) {
                                echo "参加";
                            } elseif ($_GET['participate_id'] == 1) {
                                echo "不参加";
                            } else {
                                echo $_GET['participate_id'];
                            }
                            ?></td>
                        <td><?php echo $_GET['comment']; ?></td>
                        <td>
                            <form action="delete/<?= $_GET['id'] ?>" method="post" name="a_form<?= $_GET['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
                            </form><a href="edit/<?= $_GET['id'] ?>">編集</a> <a href="#" onclick="document.a_form<?= $_GET['id'] ?>.submit();">削除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
        <div>
            <a href="add.php" class="btn btn-secondary">アンケートに回答する</a>
        </div>
    </div>

</body>

</html>
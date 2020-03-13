<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>掲示板</h1>

        <ul>
            <li><button>ログイン</button></li>

            <li><button>ログアウト</button></li>
            <li><a href="user-management.php"><button>ユーザー登録</button></a></li>
        </ul>
    </div>
    <form method="POST">

        タイトル: <input type="text" placeholder="タイトルを入力してください" name="title" value ="">
        <button type="submit">投稿する</button>
        <!-- データベースにアクセスする -->

    </form>

    <?php
    
            //投稿した値にデータが入っていたら、
            if(isset($_POST["title"])){
                $title = $_POST["title"];
            }
            

            // MySQLに接続するのに必要な情報を管理する変数
            $db_type = "mysql";	// データベースの種類
            $db_host = "localhost";	// ホスト名
            $db_name = "bss";	// データベース名
            $db_dsn = "$db_type:host=$db_host;dbname=$db_name;charset=utf8";// DSN
            $db_user = "sima";	// ユーザー名
            $db_pass = "0000";	// パスワード
            



            try{
                // MySQLへ接続
                $pdo = new PDO($db_dsn,$db_user,$db_pass,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => true
                ));
                // 処理
                print 'PDOによる接続に成功しました。';

            }
            catch (PDOException $e) {

                    $error = $e->getMessage();

            }
             $title = $_POST["title"];
            //  本来は、ログインしているユーザーのuser_id
            // FIXME リロードすると勝手に投稿される
            // TODO ログイン実装したら、動的に初期化する
             $user_id = 1;
             $stmt = $pdo->prepare("INSERT INTO posts (user_id,text) VALUES (:user_id,:title)");
             $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
             $stmt->bindValue(':title', $title, PDO::PARAM_STR);
             $stmt->execute();
      ?>

    <h2>投稿一覧</h2>

    <!-- データベースからデータを取ってきて、一覧を表示したい -->
    <?php
        $result = null;
        
        // SQL実行
        // TODO usersテーブルを表示
        $result = $pdo->query("select users.name, posts.text from users inner join posts on users.id = posts.user_id;");

        // 取得したデータを出力
        foreach( $result as $value ) {
          // TODO usersテーブルのユーザー名を表示
          echo "<p>$value[text]  :  ユーザー名→$value[name]</p>";
        }
    ?>

    <button>削除</button>

</body>

</html>
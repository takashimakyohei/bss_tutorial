<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>

<body>
  <h1>ユーザー登録</h1>
  <form method="POST">
    <p>ユーザー名：<input type="text" name="name" value=""></p>
    <p>メールアドレス：<input type="text" name="email" value=""></p>
    <p>PW：<input type="text" name="password" value=""></p>
    <p>PW(再確認)：<input type="text" name="password2" value=""></p>
    <button type="submit" name="signup">登録</button>
  </form>
    <?php
      try{
        // MySQLに接続するのに必要な情報を管理する変数
      $db_type = "mysql";	// データベースの種類
      $db_host = "localhost";	// ホスト名
      $db_name = "bss";	// データベース名
      $db_dsn = "$db_type:host=$db_host;dbname=$db_name;charset=utf8";// DSN
      $db_user = "sima";	// ユーザー名
      $db_pass = "0000";	// パスワード
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
            echo "データベースの接続に失敗しました。";
            $error = $e->getMessage();
            die();//スクリプトを終了する
      }
      // 入力された値が空で "ない"、１回目と２回目入力したPWが同じだったら、データベースにデータを登録する処理
      if (!empty($_POST["name"]) && !empty($_POST["password"]) && !empty($_POST["password2"])
       &&$_POST["password"] === $_POST["password2"]) {      
      $name = $_POST["name"];
      if (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',$_POST["email"])){
      $email = $_POST["email"];
      }
      else{
        echo "<p>メールアドレスが不正です</p>";
        return false;
      }
      // 入力したパスワードをハッシュ化して$passwordに格納する
      if(preg_match('/\A[a-z\d]{8,100}+\z/i',$_POST["password"])){
      $password = password_hash($_POST["password"],PASSWORD_DEFAULT);
      }
      else{
        echo "<p>パスワードは８文字以上で入力してください</p>";
        return false;
      }
      $stmt = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (:name,:email,:password)");
              $stmt->bindValue(':name', $name, PDO::PARAM_STR);
              $stmt->bindValue(':email', $email, PDO::PARAM_STR);
              $stmt->bindValue(':password', $password, PDO::PARAM_STR);
              $stmt->execute();
              //データベース接続を切断する
              $pdo = null;
       }
    ?>
  </body>

</html>
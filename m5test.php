<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_3</title>
    </head>
    <body>
        <form action="" method="post">
            <input type="text" name="name" placeholder="投稿名">
            <input type="text" name="str" placeholder="コメント">
            <input type="text" name="pass" placeholder="パスワード">
            <input type="submit" name="submit" value="投稿">
        </form>
        <form action="" method="post">   
            <input type="number" name="num" placeholder="削除番号を入力">
            <input type="text" name="delpass" placeholder="パスワードを入力">
            <input type="submit" name="delsub" value="削除">
        </form>
        <form action="" method="post">   
            <input type="number" name="editnum" placeholder="編集番号を入力">
            <input type="text" name="editpass" placeholder="パスワードを入力">
            <input type="text" name="editname"  placeholder="名前を編集">
            <input type="text" name="editstr" placeholder="コメントを編集">
            <input type="text" name="editpass1" placeholder="パスワードを編集">
            <input type="submit" name="editsub" value="編集">
        </form>
       <?php
         // DB接続設定
         $dsn = 'mysql:dbname=データベース名;host=localhost';
         $user = 'ユーザー名';
         $password = 'パスワード';
         $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
        
       // 初期状態で名前、コメント、日時を変数にする
        if(isset($_POST["str"]) && !empty($_POST["str"]) && !isset($_POST["delsub"]) && !isset($_POST["editsub"])){
           $str = $_POST["str"];
           $name = $_POST["name"];
           $date = date("Y/m/d H:i:s");
           $pass=$_POST["pass"]; 
        //これを保存するテーブルを作る
           $sql = "CREATE TABLE IF NOT EXISTS formtb"
           ." ("
           . "id INT AUTO_INCREMENT PRIMARY KEY,"
           . "name char(30),"
           . "comment TEXT,"
           . "date DATETIME,"
           . "pass TEXT"
           .");";
           $stmt = $pdo->query($sql);
        //POSTで取得したものをテーブルに挿入
           $sql = $pdo -> prepare("INSERT INTO formtb (name, comment,date,pass) VALUES (:name, :comment,:date,:pass)");
           $sql -> bindParam(':name', $name, PDO::PARAM_STR);
           $sql -> bindParam(':comment', $str, PDO::PARAM_STR);
           $sql -> bindparam(':date',$date,PDO::PARAM_STR);
           $sql -> bindparam(':pass',$pass,PDO::PARAM_STR);
           $sql -> execute();   
        //テーブルを表示
            $sql = 'SELECT * FROM formtb';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
             echo $row['id'].',';
             echo $row['name'].',';
             echo $row['comment'].',';
             echo $row['date'].'<br>';
             echo "<hr>";
            }
        //削除処理　番号の一致するものを削除
        }elseif(isset($_POST["delsub"]) && !empty($_POST["num"]) ) {
            $id = $_POST["num"];
            $pass=$_POST["delpass"];
            $sql = 'delete from formtb where id=:id AND pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
            $stmt->execute();
            //削除後のものを表示
            $sql = 'SELECT * FROM formtb';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
             echo $row['id'].',';
             echo $row['name'].',';
             echo $row['comment'].',';
             echo $row['date'].'<br>';
             echo "<hr>";
            }
        //編集処理 番号の一致するもの編集
        }elseif(isset($_POST["editsub"] )&& !empty($_POST["editnum"])){
            $id = $_POST["editnum"]; 
            $name = $_POST["editname"];
            $comment = $_POST["editstr"];
            $editpass=$_POST["editpass1"];
            $pass=$_POST["editpass"];
            $sql = 'UPDATE formtb SET name=:name,comment=:comment,pass=:editpass WHERE id=:id AND pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
            $stmt->bindParam(':editpass', $editpass, PDO::PARAM_INT);
            $stmt->execute();
            //編集後のものを表示
            $sql = 'SELECT * FROM formtb';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
             echo $row['id'].',';
             echo $row['name'].',';
             echo $row['comment'].',';
             echo $row['date'].'<br>';
             echo "<hr>";
            }
        }
    ?>
    </body>
</html>
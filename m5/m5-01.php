<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" action="">
        名前：<input type="text" name="name" value="">
        /コメント：<input type="text" name="comment" value="">
        /パスワード：<input type="text" name="pass" value="">
        <input type="submit" value="送信"><br>
        
        削除番号：<input type="number" name="del" value="">
        <input type="submit" value="削除"><br>
        
        編集番号：<input type="number" name="edit" value="">
        /新しいパスワード:<input type="text" name="new_pass" value="">
        <input type="submit" value="編集"><br>
    </form>
    
    <?php 
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    $sql = "CREATE TABLE IF NOT EXISTS m5db"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date date,"
    . "password TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y/m/d H:i:s");
    $del = $_POST["del"];
    $edit = $_POST["edit"];
    $pass = $_POST["pass"];
    $new_pass = $_POST["new_pass"];
    $file_name = "m3-01.txt";
    $pass_file = "m3-pass.txt";
    
    // index探索
    // if(file_exists($file_name)){
    //         $fp = fopen($file_name, "r");
    //         $array = array();
    //         while ($line = fgets($fp)){
    //             $array[] = $line; 
    //         }
    //         fclose($fp);
            
    //         $indexs = array();
    //         foreach($array as $content){
    //             $data = explode("<>", $content);
    //             $indexs[] = $data[0];
    //         }
    // }else{
    //     $indexs[] = 0;
    // }
    
    // pass探索
    // if(file_exists($pass_file)){
    //         $fp2 = fopen($pass_file, "r");
    //         $pass_array = array();
    //         while ($line = fgets($fp2)){
    //             $pass_array[] = $line; 
    //         }
    //         fclose($fp2);
            
    //         $passes = array();
    //         foreach($pass_array as $content){
    //             $data = explode("<>", $content);
    //             $passes[] = $data[1];
    //         }
    // }
    
    if($name!="" && $comment!="" && $del=="" && $edit=="" && $pass!=""){
        $sql = $pdo -> prepare("INSERT INTO m5db (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
        $sql -> execute();
        // $fp = fopen($file_name, "a");
        // $fp2 = fopen($pass_file, "a");
        // $i = (int)array_pop($indexs) + 1;
        // $object = $i."<>".$name."<>".$comment."<>".$date;
        // $pass_object = $i."<>".$pass."<>";
        // fwrite($fp, $object.PHP_EOL);
        // fwrite($fp2, $pass_object.PHP_EOL);
        // fclose($fp);
        // fclose($fp2);
    }elseif($del!="" && $edit=="" && $pass!=""){
        $id = $del;
        $sql = 'SELECT * FROM m5db';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($id==$row['id']){
                $db_pass = $row['password'];
                break;
            }
        }
        if($db_pass==$pass){
            $sql = 'delete from m5db where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        // $counter = 0;
        // $flag = false;
        // foreach($indexs as $index){
        //     if($del==$index && $pass==$passes[$counter]){
        //         array_splice($array, $counter, 1);
        //         $save_file = file($pass_file);
        //         unset($save_file[$counter]);
        //         file_put_contents($pass_file, $save_file);
        //         $flag = true;
        //         break;
        //     } 
        //     $counter++;
        // }
        // if($flag){
        //     $fp = fopen($file_name, "w");
        //     foreach($array as $object){
        //         fwrite($fp, "$object");
        //     }
        //     fclose($fp);
        // }
    }elseif($name!="" && $comment!="" && $del=="" && $edit!="" && $pass!=""){
        $id = $edit;
        $sql = 'SELECT * FROM m5db';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($id==$row['id']){
                $db_pass = $row['password'];
                break;
            }
        }
        if($db_pass==$pass){
            $sql = 'UPDATE m5db SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            if($new_pass!=""){
                $stmt->bindParam(':password', $new_pass, PDO::PARAM_STR);
            }else{
                $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
            }
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        // $counter = 0;
        // $flag = true;
        // foreach($indexs as $index){
        //     if($edit==$index && $pass==$passes[$counter]){
        //         $object = $edit."<>".$name."<>".$comment."<>".$date.PHP_EOL;
        //         $array[$counter] = $object;
        //         $pass_array[$counter] = $edit."<>".$new_pass."<>".PHP_EOL;
        //         $flag = false;
        //         break;
        //     } 
        //     $counter++;
        // }
        // if($flag){
        //     $i = (int)array_pop($indexs) + 1;
        //     $object = $i."<>".$name."<>".$comment."<>".$date;
        //     $array[] = $object;
        //     $pass_array[] = $i."<>".$pass."<>";
        // }
        // $fp = fopen($file_name, "w");
        // foreach($array as $object){
        //     fwrite($fp, $object);
        // }
        // fclose($fp);
        // $fp2 = fopen($pass_file, "w");
        // foreach($pass_array as $object){
        //     fwrite($fp2, $object);
        // }
        // fclose($fp2);
    }
    
    // 画面出力用
    $sql = 'SELECT * FROM m5db';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
    echo "<hr>";
    }
    // if(file_exists($file_name)){
    //     $fp = fopen($file_name, "r");
    //     $array = array();
    //     while ($line = fgets($fp)){
    //         $array[] = $line; 
    //     }
    //     fclose($fp);
    // }
    
    // foreach($array as $content){
    //     $data = explode("<>", $content);
    //     echo "index:".$data[0]."<br>";
    //     echo "name:".$data[1]."<br>";
    //     echo "comment:".$data[2]."<br>";
    //     echo "date:".$data[3]."<br>";
    //     echo "--------------------------------"."<br>";
    // }
    ?>
</body>
</html>
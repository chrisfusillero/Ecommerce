<?php
 $username = $_POST["username"];
 $password = $_POST["password"];


 session_start();


 if($_SERVER["REQUEST_METHOD"] == "POST"){

 
    

    
    $host = "localhost";
    $database = "ecomm";
    $dbusername = "root";
    $dbpassword = "";

    $dsn = "mysql: host=$host;dbname=$database;";
try {
    $conn = new PDO($dsn, $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare('SELECT * FROM `users` WHERE username = :p_username' );
    $stmt->bindParam(':p_username',$username);
    $stmt->execute();
    $users = $stmt->fetchAll();
    if ($users) {
        if(password_verify($password, $users[0]["password"])){
            echo "login successful";
            $_SESSION["fullname"] = $users[0]["fullname"];
        } else {
            echo "password did not match";
        }


    } else {
        echo "user not exist";
    }

    $password = password_hash(trim($password),PASSWORD_BCRYPT);

        exit;
    }


    catch (Exception $e){
    echo "Connection Failed: " . $e->getMessage();
}



    //insert record



 }


?>
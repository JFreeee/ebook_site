<?php
session_start();
//连接数据库
include('../inc/db.php');

$email = $_POST['email'];
$password = $_POST['password'];



$sql = "select * from users where email = '$email'";

$is = $con -> query($sql);

if($is && $is -> num_rows > 0){
    $row = $is -> fetch_assoc();
    $db_password = $row['password'];
    $admin = $row['is_admin'];
    if($password == $db_password){
        // 登录成功后，设置 session 保存登录状态
        $_SESSION['email'] = $email;
        $_SESSION['is_admin'] = $admin;
        if($admin == 1){header("Location: ../admin.html");
            exit;}
        header("Location: ../index.php");
        exit;
    }else{
        echo '密码不正确<br><a href="../login.html">重新登陆</a>';
    }
}else{
    echo '账号有误<br><a href="../login.html">重新登陆</a>';
}
?>
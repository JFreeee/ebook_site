<?php
// 设置返回为 JSON 格式
header('Content-Type: application/json');

session_start();

//连接数据库
include('../inc/db.php');

//接受并清理用户输入内容
$email = trim($_POST['email']);
$password = trim($_POST['password']);

//查询数据库是否存在这个邮箱
$sql = "select * from users where email = '$email'";
$is = $con -> query($sql);

if($is && $is -> num_rows > 0){
    $row = $is -> fetch_assoc();   //fetch_assoc(),从查询结果中取出一行数据，并以关联数组的形式返回
    $db_password = $row['password'];
    $admin = $row['is_admin'];

    if($password == $db_password){
        // 登录成功后，设置 session 保存登录状态
        $_SESSION['email'] = $email;
        $_SESSION['is_admin'] = $admin;

        //返回JSON，让前端决定跳转
        echo json_encode([
            'code' => 0,
            'msg' => "登陆成功",
            'is_admin' => $admin
        ]);
        exit;
        }else{
        echo json_encode(['code'=>2,'msg'=>"密码不正确"]);
        exit;
        }
    }else{
    echo json_encode(['code'=>1,'msg'=>"邮箱不存在"]);
    exit;
}
?>
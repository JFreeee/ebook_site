<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ebook_site';
//创建数据库连接
$con = new mysqli($host,$username,$password,$dbname);
//验证链接成功与否
if($con -> connect_errno <> 0){
    echo "连接失败，";
    echo $con -> connect_error;
}
$con->set_charset("utf8mb4");

// var_dump($con);

//PDO方式
// try{
//     $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$username,$password);
//     //设置错误模式为异常
//     $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//     // echo "连接成功";
// }catch(PDOException $e){
//     die("数据库连接失败：".$e->getMessage());
// }
?>
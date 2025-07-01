<?php
include('../inc/db.php');

$email = $_POST['email'];
$password = $_POST['password'];

//判断密码是否为空
if(empty($password)){
    echo "密码不能为空";
    exit;
}


$sql = "select * from users where email = '$email'";
$zhuce = "INSERT INTO users(email,password,is_admin) values('$email','$password',0)";

$is = $con -> query($sql);

//判断邮箱是否被注册
if($is && $is -> num_rows > 0){
    echo "邮箱已被注册";
}else{
    $con -> query($zhuce);
    echo '注册成功!3秒后跳转到<a href="../index.html">登录页面</a>...';
    echo '<script>
        setTimeout(function(){
            window.location.href = "../index.html";
            },3000);
            </script>';
}

?>
<?php
// 设置返回为 JSON 格式
header('Content-Type: application/json');

//连接数据库
include('../inc/db.php');

$email = trim($_POST['email']);
$password = trim($_POST['password']);
$password2 = trim($_POST['password2']);

//判断邮箱格式
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    echo json_encode(['code'=>0,'msg'=>"请输入正确的邮箱格式"]);
    exit;
}
//判断邮箱长度
if(strlen($email)<6||strlen($email)>50){
    echo json_encode(['code'=>0,'msg'=>"邮箱长度在6~50位之间"]);
    exit;
}
//判断邮箱字符
if(!preg_match("/^[a-zA-Z0-9_.@]+$/",$email)){
    echo json_encode(['code'=>0,'msg'=>"邮箱只能包含字母、数字、下划线、点、@"]);
    exit;
}
//判断密码是否为空
if($password == ''||$password2 == ''){
    echo json_encode(['code'=>1, 'msg'=>"密码不能为空" ]);
    exit;
}
//判断密码长度
if(strlen($password)<6||strlen($password)>50){
    echo json_encode(['code'=>1,'msg'=>"密码长度在6~50位之间"]);
    exit;
}
//判断密码字符
if(!preg_match("/^[a-zA-Z0-9_.]+$/",$password)){
    echo json_encode(['code'=>1,'msg'=>"密码只能包含字母、数字、下划线、点"]);
    exit;
}
//判断密码是否一致
if($password !== $password2){
    echo json_encode(['code'=>2,'msg'=>"密码输入不一致"]);
    exit;
}



$sql = "select * from users where email = '$email'";
$zhuce = "INSERT INTO users(email,password,is_admin) values('$email','$password',0)";

$is = $con -> query($sql);

//判断邮箱是否被注册
if($is && $is -> num_rows > 0){
    echo json_encode(['code'=>0,'msg'=>"邮箱已被注册"]);
}else{
    $con -> query($zhuce);
echo json_encode([
    'code' => 100,
    'msg' => "注册成功! 3秒后自动跳转到登录页面"
]);
exit;
}

?>
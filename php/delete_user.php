<?php
session_start();
header('Content-Type: application/json');
include('../inc/db.php');

if (!isset($_POST['id'])) {
    echo json_encode(['code' => 0, 'msg' => '参数错误']);
    exit;
}

$id = intval($_POST['id']);

//查出该用户
$sql = "SELECT email, is_admin FROM users WHERE id = ?";
$stmt = $con ->prepare($sql);
$stmt ->bind_param("i",$id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if(!$user){
    echo json_encode(['code'=>0,'msg'=>'用户不存在']);
    exit;
}

//特殊账号保护
if($user['email'] === "870041522@qq.com"){
    echo json_encode(['code' => 0,'msg' => '该账号为超级管理员，无法删除']);
    exit;
}

//删除用户
$stmt = $con->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i",$id);
$res = $stmt->execute();

if($res){
    echo json_encode(['code'=>1, 'msg' => '删除成功']);
}else{
    echo json_encode(['code'=>0, 'msg' => '删除用户失败']);
}

$stmt->close();
$con->close();

?>
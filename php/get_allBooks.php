<?php
session_start();
header('Content-Type: application/json');
include('../inc/db.php');

//检查是否登录
if(!isset($_SESSION['email'])){
    echo json_encode(['code' =>0, 'msg' => '未登录']);
    exit;
}



$sql="SELECT id, email, title, pic, upload_time FROM ebooks ORDER BY upload_time DESC";
$res = $con->query($sql);

if(!$res){
    echo json_encode(['code'=>0,'msg'=>'SQL查询失败: '.$con->error]);
    exit;
}

$books = [];
while($row = $res->fetch_assoc()){
    $books[]=$row;
}

echo json_encode([
'code' => 1,
'data' => [
    'books' => $books  //返回数组
    ]   
]);
exit;

?>
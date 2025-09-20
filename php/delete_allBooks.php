<?php
session_start();
header('Content-Type: application/json');

include('../inc/db.php');

if(!isset($_SESSION['email'])){
    echo json_encode(['code' => 0, 'msg' => '请先登录！']);
    exit;
}

$id = intval($_POST['id'] ?? 0);

if($id <= 0){
    echo json_encode(['code'=>0,'msg'=>'参数错误','id'=>$id]);
    exit;
}

//查询图书
$stmt = $con->prepare("SELECT * FROM ebooks WHERE id = ?");
$stmt->bind_param("i",$id);  //绑定参数
$stmt->execute();  //执行 SQL 查询。
$result = $stmt->get_result();  //把执行结果转换成可以像普通查询一样操作的对象
$book = $result->fetch_assoc();

if(!$book){
    echo json_encode(['code' => 0, 'msg' => '没有找到该图书，或者您没有权限删除', 'id' => $id]);
    exit;
}

// 删除图书
$stmt = $con->prepare("DELETE FROM ebooks WHERE id = ?");
$stmt->bind_param("i", $id);
$res = $stmt->execute();

if($res){
    $filepath = "../ziyuan/books/" . $book['file_path'];
    if (file_exists($filepath) && is_file($filepath)) unlink($filepath);

    $filepic = "../ziyuan/images/" . $book['pic'];
    if (file_exists($filepic) && is_file($filepic)) unlink($filepic);

    echo json_encode(['code' => 1, 'msg' => '删除成功', 'id' => $id]);
} else {
    echo json_encode(['code' => 0, 'msg' => '删除失败，请重试', 'id' => $id]);
}
?>
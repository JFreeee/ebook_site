<?php
session_start();
header('Content-Type: application/json');

include('../inc/db.php');  

if (!isset($_SESSION['email'])) {
    echo json_encode(['code' => 0, 'msg' => '请先登录！']);
    exit;
}

$email = $_SESSION['email'];
$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['code' => 0, 'msg' => '参数错误', 'id' => $id, 'email' => $email]);
    exit;
}

//  查询图书
$stmt = $con->prepare("SELECT * FROM ebooks WHERE id = ? AND email = ?");
$stmt->bind_param("is", $id, $email);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    echo json_encode(['code' => 0, 'msg' => '没有找到该图书，或者您没有权限删除', 'id' => $id, 'email' => $email]);
    exit;
}

// 删除数据库记录
$stmt = $con->prepare("DELETE FROM ebooks WHERE id = ? AND email = ?");
$stmt->bind_param("is", $id, $email);
$res = $stmt->execute();

if ($res) {
    $filepath = "../ziyuan/books/" . $book['file_path'];
    if (file_exists($filepath) && is_file($filepath)) unlink($filepath);

    $filepic = "../ziyuan/images/" . $book['pic'];
    if (file_exists($filepic) && is_file($filepic)) unlink($filepic);

    echo json_encode(['code' => 1, 'msg' => '删除成功', 'id' => $id, 'email' => $email]);
} else {
    echo json_encode(['code' => 0, 'msg' => '删除失败，请重试', 'id' => $id, 'email' => $email]);
}

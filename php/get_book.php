<?php
header('Content-Type: application/json');
session_start();
include('../inc/db.php');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success'=>false,'msg'=>'无效的书籍ID']);
    exit;
}

$sql = "SELECT id, title, pic, file_path AS file FROM ebooks WHERE id=$id LIMIT 1";
$res = $con->query($sql);

if ($res && $row = $res->fetch_assoc()) {
    echo json_encode(['success'=>true,'book'=>$row]);
} else {
    echo json_encode(['success'=>false,'msg'=>'未找到书籍']);
}
exit;
?>

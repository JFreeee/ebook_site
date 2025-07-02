<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['is_admin'] != 1) {
    die("无权访问");
}

include('../inc/db.php');

$title = $_POST['title'];
$pic = $_FILES['pic'];
$book = $_FILES['bookfile'];

if ($pic['error'] || $book['error']) {
    die("上传失败");
}

// 移动上传文件
move_uploaded_file($pic['tmp_name'], "../ziyuan/images/" . $pic['name']);
move_uploaded_file($book['tmp_name'], "../ziyuan/books/" . $book['name']);

// 插入数据库
$sql = "INSERT INTO ebooks (title, pic, file_path) 
        VALUES ('$title', '{$pic['name']}', '{$book['name']}')";
mysqli_query($con, $sql);

echo "<script>alert('上传成功');location.href='../upload.html';</script>";
?>

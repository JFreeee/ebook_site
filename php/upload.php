<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['email']) || $_SESSION['is_admin'] != 1) {
    die("无权访问");
}

include('../inc/db.php');

// 获取字段
$title = $_POST['title'];

// 处理封面图
$pic_name =$_FILES['pic']['name'];
$pic_tmp = $_FILES['pic']['tmp_name'];
move_uploaded_file($pic_tmp, "../ziyuan/images/" . $pic_name);

// 处理图书文件
$book_name =$_FILES['bookfile']['name'];
$book_tmp = $_FILES['bookfile']['tmp_name'];
move_uploaded_file($book_tmp, "../ziyuan/books/" . $book_name);

// 写入数据库
$stmt = $con->prepare("INSERT INTO ebooks (title, pic, file_path) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $pic_name, $book_name);
$stmt->execute();

echo "<script>alert('上传成功');location.href='../upload.html';</script>";

?>

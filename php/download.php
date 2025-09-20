<?php
session_start();
if (!isset($_SESSION['email'])) {
    echo "<script>alert('请先登录'); window.location.href='index.html';</script>";
    exit;
}

include('../inc/db.php');

$id = intval($_GET['id']);
//防止SQL注入
$stmt = $con->prepare("SELECT file_path FROM ebooks WHERE id = ?");   //预处理SQL语句
$stmt->bind_param("i", $id);  //绑定参数，把$id绑定到上边的 ？上
$stmt->execute();  //执行SQL
$result = $stmt->get_result();   //返回select查询结果
if ($result->num_rows === 0) {
    die("文件不存在");
  }

$book = $result->fetch_assoc();   //取出查询结果中的一行，并返回为“关联数组”
$file = "../ziyuan/books/" . $book['file_path'];   //拼接出文件的完整路径

if (!file_exists($file)) {    //file_exists($file) 会检查这个路径下的文件是否存在。
    die("文件不存在"); 
  }   

//下载头，必须的
header('Content-Type: application/octet-stream');   //表示是通用二进制流，浏览器不会尝试打开它
header('Content-Disposition: attachment; filename="' . basename($file) . '"');  //告诉浏览器下载这个文件，并指定名称
readfile($file);
exit;

?>
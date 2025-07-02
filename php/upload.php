<?php
session_start();
if (!isset($_SESSION['email'])) {
    die("请先登录");
}

include('../inc/db.php');

$title = $_POST['title'];
$pic = $_FILES['pic'];
$book = $_FILES['bookfile'];

function fileUploadErrorMessage($error_code) {
    $errors = [
        UPLOAD_ERR_INI_SIZE   => "文件超过了 php.ini 中 upload_max_filesize 限制",
        UPLOAD_ERR_FORM_SIZE  => "文件超过了 HTML 表单中 MAX_FILE_SIZE 限制",
        UPLOAD_ERR_PARTIAL    => "文件只有部分被上传",
        UPLOAD_ERR_NO_FILE    => "没有文件被上传",
        UPLOAD_ERR_NO_TMP_DIR => "找不到临时文件夹",
        UPLOAD_ERR_CANT_WRITE => "文件写入失败",
        UPLOAD_ERR_EXTENSION  => "PHP 扩展阻止了文件上传",
    ];
    return $errors[$error_code] ?? "未知错误";
}

if ($pic['error'] !== UPLOAD_ERR_OK || $book['error'] !== UPLOAD_ERR_OK) {
    $picErrorMsg  = $pic['error'] !== UPLOAD_ERR_OK  ? "封面图上传失败：" . fileUploadErrorMessage($pic['error']) : "";
    $bookErrorMsg = $book['error'] !== UPLOAD_ERR_OK ? "图书文件上传失败：" . fileUploadErrorMessage($book['error']) : "";
    die($picErrorMsg . "<br>" . $bookErrorMsg);
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

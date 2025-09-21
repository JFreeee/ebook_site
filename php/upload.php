<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['email'])) {
    echo json_encode(["code" => 0, "msg" => "请先登录"]);
    exit;
}

include('../inc/db.php');

$email = $_SESSION['email'];

$title = $_POST['title'] ?? '';

$pic   = $_FILES['pic'] ?? null;
$book  = $_FILES['bookfile'] ?? null;

function uploaderror($error_code) {
    $errors = [
        UPLOAD_ERR_INI_SIZE   => "文件超过了 php.ini 中 upload_max_filesize 限制",
        UPLOAD_ERR_FORM_SIZE  => "文件超过了表单中 MAX_FILE_SIZE 限制",
        UPLOAD_ERR_PARTIAL    => "文件只有部分被上传",
        UPLOAD_ERR_NO_FILE    => "没有文件被上传",
        UPLOAD_ERR_NO_TMP_DIR => "找不到临时文件夹",
        UPLOAD_ERR_CANT_WRITE => "文件写入失败",
        UPLOAD_ERR_EXTENSION  => "PHP 扩展阻止了文件上传",
    ];
    return $errors[$error_code] ?? "未知错误";
}

// 检查必要字段
if (empty($title) || !$pic || !$book) {
    echo json_encode(["code" => 0, "msg" => "请填写完整信息"]);
    exit;
}

// 检查数据库是否已存在该书名
$checkStmt = $con->prepare("SELECT id FROM ebooks WHERE title = ?");
$checkStmt->bind_param("s", $title);
$checkStmt->execute();
$checkStmt->store_result();  //在执行了 SELECT 查询之后，把查询结果缓存到 PHP里
if ($checkStmt->num_rows > 0) {
    echo json_encode(["code" => 0, "msg" => "图书已存在"]);
    $checkStmt->close();
    $con->close();
    exit;
}
$checkStmt->close();

// 检查上传错误
if ($pic['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["code" => 0, "msg" => "封面图上传失败：" . uploaderror($pic['error'])]);
    exit;
}
if ($book['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["code" => 0, "msg" => "图书文件上传失败：" . uploaderror($book['error'])]);
    exit;
}

// 校验文件名前缀必须和书名一致
$picName  = pathinfo($pic['name'], PATHINFO_FILENAME);
$bookName = pathinfo($book['name'], PATHINFO_FILENAME);

// 检查合法字符（中文/英文/数字/下划线/短横线）
function isValidName($name) {
    return preg_match('/^[\w\-]+$/u', $name);
}

if (!isValidName($title) || !isValidName($picName) || !isValidName($bookName)) {
    echo json_encode(["code" => 0, "msg" => "书名、图片名、文件名不能包含空格或特殊字符"]);
    exit;
}

if ($title !== $picName|| $title !== $bookName) {
    echo json_encode(["code" => 0, "msg" => "书名、图片名、图书文件名必须一致"]);
    exit;
}

// 目标路径
$picPath  = "../ziyuan/images/" . basename($pic['name']);
$bookPath = "../ziyuan/books/" . basename($book['name']);

// 移动文件
if (!move_uploaded_file($pic['tmp_name'], $picPath)) {
    echo json_encode(["code" => 0,
     "msg" => "封面图保存失败",
    'error' => error_get_last(),
        'debug' => [
            'tmp_name' => $_FILES['pic']['tmp_name'],
            'target' => $picPath,
            'is_writable' => is_writable(dirname($picPath))]]);
    exit;
}
if (!move_uploaded_file($book['tmp_name'], $bookPath)) {
    echo json_encode(["code" => 0, "msg" => "图书文件保存失败"]);
    exit;
}

// 插入数据库（注意防止 SQL 注入）
$stmt = $con->prepare("INSERT INTO ebooks (email, title, pic, file_path) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $email, $title, $pic['name'], $book['name']);
if ($stmt->execute()) {
    echo json_encode(["code" => 1, "msg" => "上传成功"]);
} else {
    echo json_encode(["code" => 0, "msg" => "数据库写入失败: " . $con->error]);
}
$stmt->close();
$con->close();
?>

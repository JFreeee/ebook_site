<?php
header('Content-Type: application/json');
session_start();
include('../inc/db.php'); // 数据库连接

// 获取搜索关键字
$keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';

// 构建 SQL
if ($keyword !== '') {
    $keyword = $con->real_escape_string($keyword);
    $sql = "SELECT id, title, pic FROM ebooks WHERE title LIKE '%$keyword%'";
} else {
    $sql = "SELECT id, title, pic FROM ebooks ORDER BY upload_time DESC";
}

$result = $con->query($sql);

// 构造图书列表
$books = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'pic' => $row['pic']
        ];
    }
}

// 检查登录状态
$loggedIn = isset($_SESSION['email']);
$email = $loggedIn ? $_SESSION['email'] : '';
$is_admin = 0;

if($loggedIn){
    $stmt = $con->prepare("SELECT is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($is_admin);
    $stmt->fetch();
    $stmt->close();
}
// 返回 JSON
echo json_encode([
    'loggedIn' => $loggedIn,
    'email' => $email,
    'is_admin' => $is_admin,  // ✅ 把管理员字段传给前端
    'books' => $books
]);
exit;
?>

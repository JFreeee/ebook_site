<?php
session_start();
header('Content-Type: application/json');
include('../inc/db.php');

if (!isset($_POST['id'])) {
    echo json_encode(['code' => 0, 'msg' => '参数错误']);
    exit;
}

$id = intval($_POST['id']);

// 先查出该用户
$sql = "SELECT email, is_admin FROM users WHERE id=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(['code' => 0, 'msg' => '用户不存在']);
    exit;
}

// ⚠️ 特殊账号保护：超级管理员不能取消
if ($user['email'] === "870041522@qq.com" && $user['is_admin'] == 1) {
    echo json_encode(['code' => 0, 'msg' => '该账号为超级管理员，无法取消权限']);
    exit;
}
//切换权限
if ($user['is_admin'] == 1) {
    $new_status = 0;  // 如果原本是管理员，就取消（变成普通用户）
} else {
    $new_status = 1;  // 如果原本不是管理员，就设置为管理员
}
// 如三元运算符写法：
// $new_status = $is_admin == 1 ? 0 : 1;  


$sql = "UPDATE users SET is_admin = ? WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $new_status, $id);

if ($stmt->execute()) {
    $msg = $new_status == 1 ? '设置成功，该用户现在是管理员' : '已取消管理员权限';
    echo json_encode([
        'code' => 1,
        'msg' => $msg,
        'new_status' => $new_status  // ✅ 返回新状态
    ]);
} else {
    echo json_encode(['code' => 0, 'msg' => '操作失败，请重试']);
}

$stmt->close();
$con->close();

?>
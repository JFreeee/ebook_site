<?php
session_start();
include("../inc/db.php");

if (!isset($_SESSION['email'])) {
    echo json_encode(["code" => 0, "msg" => "请先登录"]);
    exit;
}

$email   = $_SESSION['email'];  // 当前登录账号
$oldPwd  = trim($_POST['oldPwd'] ?? '');
$newPwd  = trim($_POST['newPwd'] ?? '');
$newPwd2 = trim($_POST['newPwd2'] ?? '');

// 1. 检查两次新密码是否一致
if ($newPwd !== $newPwd2) {
    echo json_encode(["code" => 0, "msg" => "两次新密码不一致"]);
    exit;
}

// 2. 查询旧密码
$sql = "SELECT password FROM users WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($dbPwd);
$stmt->fetch();
$stmt->close();

// 3. 校验旧密码
if ($dbPwd !== $oldPwd) {
    echo json_encode(["code" => 0, "msg" => "旧密码错误"]);
    exit;
}

// 4. 更新新密码
$sql = "UPDATE users SET password = ? WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $newPwd, $email);

if ($stmt->execute()) {
    echo json_encode(["code" => 1, "msg" => "密码修改成功"]);
} else {
    echo json_encode(["code" => 0, "msg" => "修改失败"]);
}

$stmt->close();
$con->close();

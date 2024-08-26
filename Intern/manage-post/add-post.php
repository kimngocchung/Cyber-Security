<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id']; 

    if($title != "" && $content != "") {
        $sql = "INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if($stmt === false) {
            die("Chuẩn bị thất bại: " . $conn->error);
        }
        $stmt->bind_param("ssi", $title, $content, $user_id);

        if($stmt->execute()) {
            // Thiết lập thông báo trong session
            $_SESSION['message'] = "Bài viết mới đã được thêm thành công";
            $_SESSION['message_type'] = "success";
            // Chuyển hướng về trang home.php
            header("Location: ../home.php");
            exit();
        } else {
            echo "Lỗi: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Tiêu đề và nội dung không được để trống.";
    }
} else {
    echo "Không nhận được dữ liệu POST.";
}
?>

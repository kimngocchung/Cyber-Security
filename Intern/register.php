<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); // Bắt đầu phiên làm việc

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'dbconnect.php'; // Import kết nối cơ sở dữ liệu

    // Lấy dữ liệu từ form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Kiểm tra xem người dùng hoặc email đã tồn tại chưa
    $check_user_query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_user_query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Người dùng hoặc email đã tồn tại";
        $_SESSION['message_type'] = "error";
    } else {
        // Thêm người dùng mới vào cơ sở dữ liệu
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Đăng kí tài khoản thành công";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
            $_SESSION['message_type'] = "error";
        }
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();

    // Chuyển hướng sau khi xử lý biểu mẫu
    header("Location: register.php");
    exit();
}

// Hiển thị thông báo từ session và xóa session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <form method="post" action="register.php">
            <h2>Register</h2>
            <?php
            if (!empty($message)) {
                echo "<div class='$message_type-message'>$message</div>";
            }
            ?>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>

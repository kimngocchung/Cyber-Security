<?php
session_start(); 

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'dbconnect.php'; 

    // Lấy dữ liệu từ form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Đăng nhập thành công
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $result->fetch_assoc()['id']; // Lưu ID người dùng vào session
        $_SESSION['message'] = "Đăng nhập thành công";
        $_SESSION['message_type'] = "success";
        // Chuyển hướng người dùng đến trang home hoặc trang dashboard
        header("Location: home.php");
        exit();
    } else {
        // Đăng nhập thất bại
        $message = "Tên đăng nhập hoặc mật khẩu không chính xác";
        $message_type = "error";
    }

    // Đóng kết nối
    $conn->close();
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
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <form method="post" action="login.php">
            <h2>Login</h2>
            <?php
            if (!empty($message)) {
                echo "<div class='$message_type-message'>$message</div>";
            }
            ?>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

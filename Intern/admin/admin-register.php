<?php
require '../dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Kiểm tra xem tên đăng nhập hoặc email đã tồn tại chưa
    $sql = "SELECT * FROM admin WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Tài khoản chưa tồn tại, chèn tài khoản mới
        $sql = "INSERT INTO admin (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);

        if ($stmt->execute()) {
            echo "Admin account created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Username or email already exists";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Register Admin</h1>
        <form method="POST" action="admin-register.php">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Đăng ký</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();

// Kiểm tra xem admin đã đăng nhập chưa
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

require '../dbconnect.php';

// Lấy danh sách người dùng và sắp xếp theo ID tăng dần
$sql = "SELECT * FROM users ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        .delete-btn:hover {
            background-color: #e53935;
        }
        .edit-btn {
            background-color:dodgerblue;
            color: white;
        }
        .edit-btn:hover {
            background-color: #45a049;
        }
        .table-cell {
            vertical-align: middle;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            color: #333;
        }
    </style>
    <script>
        // JavaScript để ẩn thông báo sau 3 giây
        function hideMessage() {
            setTimeout(function() {
                var messageDiv = document.getElementById("message");
                if (messageDiv) {
                    messageDiv.style.display = "none";
                }
            }, 3000);
        }
    </script>
</head>
<body onload="hideMessage()">
    <div class="container">
        <h1>Quản lý người dùng</h1>
        <a href="add-user.php" class="show-add-post-btn">Thêm Người Dùng</a>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<div id='message' class='message'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>

        <h2>Danh sách người dùng</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <form action="edit-user.php" method="POST">
                                <td class="table-cell"><?php echo $row['id']; ?></td>
                                <td class="table-cell"><input type="text" name="username" value="<?php echo $row['username']; ?>" required></td>
                                <td class="table-cell"><input type="email" name="email" value="<?php echo $row['email']; ?>" required></td>
                                <td class="table-cell"><input type="text" name="password" value="<?php echo $row['password']; ?>" required></td>
                                <td class="table-cell">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="edit-btn">Sửa</button>
                                </td>
                            </form>
                            <td class="table-cell">
                                <form action="delete-user.php" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="delete-btn">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Không có người dùng nào.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>
</body>
</html>

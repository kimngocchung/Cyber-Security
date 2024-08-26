<?php
session_start(); // Bắt đầu phiên làm việc

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'dbconnect.php';

$user_id = $_SESSION['user_id'];
$message = "";
$message_type = "";

// Hiển thị thông báo từ session và xóa session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Lấy danh sách bài đăng của người dùng
$sql = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);

// Đóng kết nối
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .success-message {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #4CAF50;
            background-color: #DFF2BF;
            color: #4CAF50;
        }
        .error-message {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #f44336;
            background-color: #FFBABA;
            color: #f44336;
        }
        .hidden {
            display: none;
        }
    </style>
    <script>
        // JavaScript để ẩn thông báo sau 3 giây
        function hideMessage() {
            setTimeout(function() {
                var messageDiv = document.getElementById("message-box");
                if (messageDiv) {
                    messageDiv.style.display = "none";
                }
            }, 3000);
        }
    </script>
</head>
<body onload="hideMessage()">
    <div class="container">
        <div class="header">
            <h2>Chào mừng, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <a href="login.php">Đăng xuất</a>
        </div>
        
        <button class="show-add-post-btn" onclick="document.getElementById('addPostForm').style.display='block';">Thêm bài đăng</button>
        
        <div class="form-container hidden" id="addPostForm">
            <form action="manage-post/add-post.php" method="POST">
                <h2>Thêm Bài Viết</h2>
                <label for="title">Tiêu đề:</label>
                <input type="text" id="title" name="title" required>
                <label for="content">Nội dung:</label>
                <textarea id="content" name="content" required></textarea>
                <button type="submit" name="submit">Thêm</button>
                <button type="button" onclick="window.location.href='home.php'">Hủy</button>
            </form>
        </div>

        <form method="GET" action="manage-post/search.php">
            <input type="text" name="search" placeholder="Tìm kiếm bài viết..." onkeypress="if(event.keyCode == 13) { this.form.submit(); }">
        </form>

        <div class="content">
            <?php if (!empty($message)): ?>
                <div id="message-box" class="<?php echo $message_type; ?>-message"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="posts-container">
                <h2>Bài Viết Của Bạn</h2>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p><?php echo htmlspecialchars($post['content']); ?></p>
                        <a href="manage-post/edit-post.php?id=<?php echo $post['id']; ?>">Chỉnh Sửa</a>
                        <a href="manage-post/delete-post.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">Xóa</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>

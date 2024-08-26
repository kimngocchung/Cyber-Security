<?php
require '../dbconnect.php';

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM posts WHERE title LIKE '%$search%' OR content LIKE '%$search%'";
    $result = mysqli_query($conn, $sql);
    ?>

    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Kết quả tìm kiếm</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container">
            <h1>Kết quả tìm kiếm</h1>
            <button onclick="location.href='../home.php'">Trở về trang chủ</button>
            <div id="posts">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="post">';
                        echo '<h2><a href="view-post.php?id=' . $row['id'] . '">' . $row['title'] . '</a></h2>';
                        echo '<p>' . substr($row['content'], 0, 100) . '...</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Không tìm thấy bài viết nào.</p>';
                }
                ?>
            </div>
        </div>
    </body>
    </html>

    <?php
    mysqli_close($conn);
} else {
    echo '<p>Truy vấn tìm kiếm không hợp lệ.</p>';
}
?>

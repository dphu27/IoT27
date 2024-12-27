<?php
include 'db_connect.php';

// Nhận giá trị từ URL (nếu không có, dùng giá trị mặc định)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$pageSize = isset($_GET['pageSize']) ? intval($_GET['pageSize']) : 5;

// Tính toán giá trị bắt đầu (offset)
$offset = ($page - 1) * $pageSize;

// Đếm tổng số bản ghi
$totalRowsResult = $conn->query("SELECT COUNT(*) as total FROM sensordatas");
$totalRows = $totalRowsResult->fetch_assoc()['total'];

// Tính tổng số trang
$totalPages = ceil($totalRows / $pageSize);

// Lấy dữ liệu từ cơ sở dữ liệu
$sql = "SELECT * FROM sensordatas ORDER BY id ASC LIMIT $offset, $pageSize";
$result = $conn->query($sql);

echo "<table>
        <tr>
            <th>ID</th>
            <th>Temperature</th>
            <th>Humidity</th>
            <th>Light</th>
            <th>Timestamp</th>
        </tr>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . $row['temperature'] . "</td>
                <td>" . $row['humidity'] . "</td>
                <td>" . $row['light'] . "</td>
                <td>" . $row['timestamp'] . "</td>
              </tr>";
    }
}
echo "</table>";

// Hiển thị phân trang dưới dạng dropdown
echo "<div class='pagination-container'>";
echo "<label for='pageSelect'>Page:</label>";
echo "<select id='pageSelect' onchange='changePage(this.value)'>";
for ($i = 1; $i <= $totalPages; $i++) {
    $selected = $i == $page ? 'selected' : '';
    echo "<option value='$i' $selected>$i</option>";
}
echo "</select>";
echo "</div>";

// Thêm mã JavaScript để xử lý sự kiện chọn trang
echo "<script>
function changePage(page) {
    const pageSize = $pageSize; // Lấy kích thước trang hiện tại
    window.location.href = '?page=' + page + '&pageSize=' + pageSize; // Chuyển hướng đến trang đã chọn
}
</script>";

$conn->close();
?>
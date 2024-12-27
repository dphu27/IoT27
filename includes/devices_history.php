<?php
include 'db_connect.php'; // Kết nối đến cơ sở dữ liệu

// Nhận giá trị từ URL (nếu không có, dùng giá trị mặc định)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$pageSize = isset($_GET['pageSize']) ? intval($_GET['pageSize']) : 5;

// Nhận giá trị tìm kiếm từ URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Tạo điều kiện tìm kiếm
$searchCondition = '';
if (!empty($search)) {
    $search = $conn->real_escape_string($search); // Bảo vệ khỏi SQL Injection
    $searchCondition = "WHERE id LIKE '%$search%' OR device LIKE '%$search%' OR state LIKE '%$search%' OR timestamp LIKE '%$search%'"; // Tìm kiếm theo tất cả các cột
}

// Tính toán giá trị bắt đầu (offset)
$offset = ($page - 1) * $pageSize;

// Đếm tổng số bản ghi
$totalRowsResult = $conn->query("SELECT COUNT(*) as total FROM device_states $searchCondition");
$totalRows = $totalRowsResult->fetch_assoc()['total'];

// Tính tổng số trang
$totalPages = ceil($totalRows / $pageSize);

// Lấy dữ liệu từ cơ sở dữ liệu, sắp xếp theo ID tăng dần
$sql = "SELECT id, device, state, timestamp FROM device_states $searchCondition ORDER BY id ASC LIMIT $offset, $pageSize";
$result = $conn->query($sql);

// Hiển thị form tìm kiếm
// echo '<form method="GET" action="">
//         <input type="text" name="search" placeholder="Tìm kiếm theo thiết bị..." value="' . htmlspecialchars($search) . '">
//         <input type="submit" value="Tìm kiếm">
//       </form>';
// Hiển thị bảng
echo "<table>
        <tr>
            <th>ID</th> <!-- Thêm cột ID -->
            <th>Device</th>
            <th>Status</th>
            <th>Timestamp</th>
        </tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td> <!-- Hiển thị ID -->
                <td>" . htmlspecialchars($row['device']) . "</td>
                <td>" . htmlspecialchars($row['state']) . "</td>
                <td>" . htmlspecialchars($row['timestamp']) . "</td>
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
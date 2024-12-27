<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IoT</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/sensor.css">
    <link rel="stylesheet" href="/assets/css/devices.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="sidebar">
        <ul>
            <li id="dashboardTab"><a href="#" onclick="showPage('dashboard')">Dashboard</a></li>
            <li id="dashboard2Tab"><a href="#" onclick="showPage('dashboard2')">Dashboard 2</a></li>
            <li id="sensorHistoryTab"><a href="#" onclick="showPage('sensorHistory')">Sensor History</a></li>
            <li id="devicesTab"><a href="#" onclick="showPage('devicesHistory')">Devices</a></li>
            <li id="profileTab"><a href="#" onclick="showPage('profile')">Profile</a></li>
        </ul>
    </div>

    <div class="main-content">
        <!-- Dashboard Page -->
        <div id="dashboardPage" class="page" style="display:none;">

            <div class="stats">
                <div class="stat-box" id="temperatureBox">
                    <div class="stat-icon temp-icon">
                        <i class="fas fa-thermometer-half"></i>
                    </div>
                    <div class="stat-content">
                        <p>Temperature</p>
                        <h3></h3>
                    </div>
                </div>
                <div class="stat-box" id="humidityBox">
                    <div class="stat-icon humidity-icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stat-content">
                        <p>Humidity</p>
                        <h3></h3>
                    </div>
                </div>
                <div class="stat-box" id="lightBox">
                    <div class="stat-icon light-icon">
                        <i class="fas fa-sun"></i>
                    </div>
                    <div class="stat-content">
                        <p>Light</p>
                        <h3></h3>
                    </div>
                </div>

            </div>

            <div class="chart-container">
                <div class="chart">
                    <canvas id="environmentHourlyChart" width="600" height="400"></canvas>
                </div>

                <div class="controls">
                    <div class="control-box">
                        <p>Fan</p>
                        <img id="fanImage" src="/assets/images/fanoff.png" alt="Fan" class="fan-icon">
                        <label class="switch">
                            <input type="checkbox" id="quatSwitch" onchange="toggleDevice('quat')">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="control-box">
                        <p>AC</p>
                        <img id="acImage" src="/assets/images/acoff.png" alt="AC" class="ac-icon">
                        <label class="switch">
                            <input type="checkbox" id="dieuhoaSwitch" onchange="toggleDevice('dieuhoa')">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="control-box">
                        <p>Light</p>
                        <img id="lightImage" src="/assets/images/light-off.png" alt="Light" class="light-icon">
                        <label class="switch">
                            <input type="checkbox" id="denSwitch" onchange="toggleDevice('den')">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>


            </div>
        </div>

        <!-- db2 -->
        <div id="dashboard2Page" class="page" style="display:none;">
            <div class="stats" style="display: flex; justify-content: center; gap: 20px;">
                <!-- Hộp trạng thái đèn 1 -->
                <div class="stat-box" id="light1Box" data-value="0">
                    <div class="stat-icon light-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="stat-content">
                        <p>Đèn 1</p>
                        <h3></h3>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="light1Switch" onchange="toggleLight('light1')">
                        <span class="slider"></span>
                    </label>
                </div>
                <!-- Hộp trạng thái đèn 2 -->
                <div class="stat-box" id="light2Box" data-value="0">
                    <div class="stat-icon light-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="stat-content">
                        <p>Đèn 2</p>
                        <h3></h3>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="light2Switch" onchange="toggleLight('light2')">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            <div class="chart-container">
                <h3>Sức gió (theo giờ)</h3>
                <canvas id="windSpeedHourlyChart" width="600" height="400"></canvas>
            </div>
        </div>



        <!-- Sensor History Page -->
        <div id="sensorHistoryPage" class="page" style="display:none;">
            <h2>Sensor History</h2>
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Tìm kiếm theo cảm biến..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <input type="submit" value="Tìm kiếm">
            </form>
            <form method="GET" action="">
                <label for="sort">Sắp xếp theo:</label>
                <select id="sort" name="sort" onchange="this.form.submit()">
                    <option value="timestamp"
                        <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'timestamp') ? 'selected' : ''; ?>>Thời
                        gian</option>
                    <option value="temperature"
                        <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'temperature') ? 'selected' : ''; ?>>Nhiệt
                        độ</option>
                    <option value="humidity"
                        <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'humidity') ? 'selected' : ''; ?>>Độ ẩm
                    </option>
                    <option value="light"
                        <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'light') ? 'selected' : ''; ?>>Ánh sáng
                    </option>
                </select>
            </form>
            <?php include '../includes/display_data.php'; ?>

            <div class="pagination-container">
                <form method="get" action="">
                    <label for="pageSize">Page size:</label>
                    <select id="pageSize" name="pageSize" onchange="this.form.submit()">
                        <option value="5" <?php echo $pageSize == 5 ? 'selected' : ''; ?>>5</option>
                        <option value="10" <?php echo $pageSize == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="20" <?php echo $pageSize == 20 ? 'selected' : ''; ?>>20</option>
                        <option value="50" <?php echo $pageSize == 50 ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?php echo $pageSize == 100 ? 'selected' : ''; ?>>100</option>
                    </select>
                </form>
            </div>
        </div>

        <div id="devicesHistoryPage" class="page" style="display:none;">
            <h2>Devices History</h2>
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Tìm kiếm theo thiết bị..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <input type="submit" value="Tìm kiếm">
            </form>
            <?php include '../includes/devices_history.php'; ?>
            <div class="pagination-container">
                <form method="get" action="">
                    <label for="pageSize">Page size:</label>
                    <select id="pageSize" name="pageSize" onchange="this.form.submit()">
                        <option value="5" <?php echo $pageSize == 5 ? 'selected' : ''; ?>>5</option>
                        <option value="10" <?php echo $pageSize == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="20" <?php echo $pageSize == 20 ? 'selected' : ''; ?>>20</option>
                        <option value="50" <?php echo $pageSize == 50 ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?php echo $pageSize == 100 ? 'selected' : ''; ?>>100</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Profile Page -->
        <div id="profilePage" class="page" style="display:none;">
            <div class="profile-container">
                <div class="profile-box">
                    <div class="profile-image">
                        <img src="/assets/images/avt.jpg" alt="Avatar">
                    </div>
                    <div class="profile-details">
                        <h3>Hoàng Đình Phú</h3>
                        <p>Mã sinh viên: B21DCAT149</p>
                        <p>Lớp: D21CQAT01-B</p>
                        <p>Nhóm lớp: 06 </p>
                        <p>Email: hoangdinhphu2712@gmail.com </p>
                        <p>Github: <a href="https://github.com/dphu27" target="_blank">github.com/dphu27</a></p>
                        <p>File PDF: <a href="pdf" target="_blank">pdf</a></p>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script src="/assets/js/script.js"></script>
    <script src="/assets/js/chart.js"></script>
    <script src="/assets/js/chart2.js"></script>
    <script src="/assets/js/dashboard.js"></script>
    <script src="/assets/js/sensorhistory.js"></script>
    <script src="/assets/js/devices_history.js"></script>
    <script src="/assets/js/action.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>
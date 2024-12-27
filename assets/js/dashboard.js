async function updateDashboard() {
    try {
        const response = await fetch('http://localhost/IOT/api/get_latest_sensor_data.php');
        const data = await response.json();

        if (!data.error) {
            // Lấy giá trị cảm biến
            const temperature = parseFloat(data.temperature);
            const humidity = parseFloat(data.humidity);
            const light = parseFloat(data.light);

            // Hiển thị giá trị
            document.querySelector('#temperatureBox h3').textContent = `${temperature} °C`;
            document.querySelector('#humidityBox h3').textContent = `${humidity} %`;
            document.querySelector('#lightBox h3').textContent = `${light} lx`;

            // Cập nhật màu sắc
            updateBoxStyles('temperatureBox', temperature, { warning: 30, danger: 40 }, 'temperature');
            updateBoxStyles('humidityBox', humidity, { warning: 60, danger: 80 }, 'humidity');
            updateBoxStyles('lightBox', light, { warning: 1000, danger: 2000 }, 'light');

            console.log("Dashboard updated successfully:", data);
        } else {
            console.error("Error fetching dashboard data:", data.error);
        }
    } catch (error) {
        console.error("Error updating dashboard:", error);
    }
}


function updateBoxStyles(id, value, thresholds, type) {
    const box = document.querySelector(`#${id}`);
    if (!box) return;

    // Xóa tất cả các lớp màu trước đó
    box.classList.remove('normal', 'warning', 'danger');

    // Thay đổi màu sắc dựa trên giá trị
    if (value < thresholds.warning) {
        box.classList.add('normal', type);
    } else if (value < thresholds.danger) {
        box.classList.add('warning', type);
    } else {
        box.classList.add('danger', type);
    }
}


// Cập nhật Dashboard mỗi 10 giây
setInterval(updateDashboard, 10000);

// Cập nhật ngay khi trang tải
document.addEventListener('DOMContentLoaded', updateDashboard);

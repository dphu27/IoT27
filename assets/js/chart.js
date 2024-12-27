let chartInstance; // Biến toàn cục để lưu biểu đồ

document.addEventListener('DOMContentLoaded', () => {
    drawChart(); // Vẽ biểu đồ lần đầu
    setInterval(updateChart, 2000); // Tự động cập nhật biểu đồ mỗi 2 giây
});

// Hàm vẽ biểu đồ lần đầu
async function drawChart() {
    try {
        const response = await fetch('http://localhost/IOT/api/get_sensor_history.php');
        const data = await response.json();

        if (data.length === 0) {
            console.error("No data available for chart");
            return;
        }

        const timestamps = data.map(item => item.timestamp).reverse();
        const temperatures = data.map(item => parseFloat(item.temperature)).reverse();
        const humidities = data.map(item => parseFloat(item.humidity)).reverse();
        const lights = data.map(item => parseFloat(item.light)).reverse();

        const ctx = document.getElementById('environmentHourlyChart').getContext('2d');
        
        // Tạo biểu đồ và gán vào chartInstance
        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [
                    {
                        label: 'Temperature (°C)',
                        data: temperatures,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: true,
                    },
                    {
                        label: 'Humidity (%)',
                        data: humidities,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                    },
                    {
                        label: 'Light (lx)',
                        data: lights,
                        borderColor: 'rgba(255, 206, 86, 1)',
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true, // Đảm bảo biểu đồ tự động điều chỉnh kích thước
                maintainAspectRatio: false, // Tắt tỷ lệ mặc định để phù hợp container
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Timestamp',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Value',
                        },
                    },
                },
            },
        });
    } catch (error) {
        console.error("Error drawing chart:", error);
    }
}

// Hàm cập nhật biểu đồ
async function updateChart() {
    try {
        const response = await fetch('http://localhost/IOT/api/get_sensor_history.php');
        const data = await response.json();

        if (data.length === 0) {
            console.error("No data available for chart");
            return;
        }

        const timestamps = data.map(item => item.timestamp).reverse();
        const temperatures = data.map(item => parseFloat(item.temperature)).reverse();
        const humidities = data.map(item => parseFloat(item.humidity)).reverse();
        const lights = data.map(item => parseFloat(item.light)).reverse();

        // Kiểm tra nếu chartInstance chưa được tạo
        if (!chartInstance) {
            console.error("Chart instance is not initialized");
            return;
        }

        // Cập nhật dữ liệu của biểu đồ
        chartInstance.data.labels = timestamps;
        chartInstance.data.datasets[0].data = temperatures;
        chartInstance.data.datasets[1].data = humidities;
        chartInstance.data.datasets[2].data = lights;
        chartInstance.update(); // Áp dụng thay đổi
    } catch (error) {
        console.error("Error updating chart:", error);
    }
}

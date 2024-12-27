// Tạo dữ liệu ngẫu nhiên cho sức gió
const labels = Array.from({ length: 10 }, (_, i) => `Giờ ${i + 1}`);
const windSpeeds = Array.from({ length: 10 }, () => Math.floor(Math.random() * 100)); // Sức gió ngẫu nhiên từ 0 đến 100

const ctx = document.getElementById('windSpeedHourlyChart').getContext('2d');
const windSpeedChart = new Chart(ctx, {
    type: 'line', // Loại biểu đồ
    data: {
        labels: labels,
        datasets: [{
            label: 'Sức gió (km/h)',
            data: windSpeeds,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill: true,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Sức gió (km/h)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Thời gian'
                }
            }
        }
    }
});
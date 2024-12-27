// chuyển trang
function showPage(page) {
    // Ẩn tất cả các trang
    const pages = document.querySelectorAll('.page');
    pages.forEach((pageElement) => {
        pageElement.style.display = 'none';
    });

    // Hiển thị trang được chọn
    const selectedPage = document.getElementById(page + 'Page');
    selectedPage.style.display = 'block';

    // Xóa lớp 'active' khỏi tất cả các tab
    const tabs = document.querySelectorAll('.sidebar ul li');
    tabs.forEach((tab) => {
        tab.classList.remove('active');
    });

    // Thêm lớp 'active' vào tab được chọn
    const selectedTab = document.getElementById(page + 'Tab') || document.getElementById('devicesTab');
    selectedTab.classList.add('active');
}

// Hiển thị trang Dashboard khi tải trang
document.addEventListener('DOMContentLoaded', () => {
    showPage('dashboard');
});


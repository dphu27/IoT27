function changePageSize(pageSize) {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('pageSize', pageSize);
    currentUrl.searchParams.set('page', 1); // Quay về trang đầu
    window.location.href = currentUrl.href;
}

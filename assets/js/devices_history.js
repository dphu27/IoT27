function toggleDevice(device) {
    const switchInput = document.getElementById(device + "Switch");
    const state = switchInput.checked ? "ON" : "OFF";

    // Gửi yêu cầu lưu trạng thái
    fetch("http://localhost/IoT/includes/save_device_state.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({ device, state }),
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message);
    })
    .catch(error => {
        console.error("Error saving device state:", error);
    });
}

function changePage(page) {
    const search = document.querySelector('input[name="search"]').value;
    const url = new URL(window.location.href);
    url.searchParams.set('page', page);
    if (search) {
        url.searchParams.set('search', search);
    } else {
        url.searchParams.delete('search');
    }
    window.location.href = url.toString();
}
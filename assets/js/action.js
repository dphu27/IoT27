function toggleDevice(device) {
  const switchInput = document.getElementById(device + "Switch");
  const state = switchInput.checked ? "ON" : "OFF";

  fetch("http://localhost:4000/control", {
      method: "POST",
      headers: {
          "Content-Type": "application/json",
      },
      body: JSON.stringify({ device, state }),
  })
  .then(response => response.json())
  .then(data => {
      console.log(data.message);
  })
  .catch(error => {
      console.error("Error sending device state:", error);
  });
}
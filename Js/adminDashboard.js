// Bar Chart
new Chart(document.getElementById("barChart"), {
    type: "bar",
    data: {
      labels: ["Tortoises", "Breeding", "Feeding", "Health"],
      datasets: [{
        label: "Activities",
        backgroundColor: "#198754",
        data: [120, 15, 30, 10],
      }],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false }
      }
    },
  });
  
  // Pie Chart
  new Chart(document.getElementById("pieChart"), {
    type: "pie",
    data: {
      labels: ["Healthy", "Sick", "Quarantined"],
      datasets: [{
        label: "Health Status",
        data: [90, 20, 13],
        backgroundColor: ["#198754", "#ffc107", "#dc3545"]
      }]
    },
    options: {
      responsive: true
    }
  });
  
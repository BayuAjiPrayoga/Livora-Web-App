// Admin Dashboard JavaScript
document.addEventListener("DOMContentLoaded", function () {
    // Get data from JSON script tag
    const dataScript = document.getElementById("dashboard-data");
    let data = null;

    if (dataScript) {
        try {
            data = JSON.parse(dataScript.textContent);
        } catch (e) {
            console.warn("Could not parse dashboard data:", e);
        }
    }

    // Initialize charts if data is available
    if (data) {
        // Revenue Chart
        if (data.revenueData && data.revenueData.labels.length > 0) {
            initRevenueChart(data.revenueData);
        } else {
            showNoDataMessage("revenueChart", "No revenue data available");
        }

        // User Growth Chart
        if (data.userGrowthData && data.userGrowthData.labels.length > 0) {
            initUserGrowthChart(data.userGrowthData);
        } else {
            showNoDataMessage(
                "userGrowthChart",
                "No user growth data available"
            );
        }
    } else {
        // No data available at all
        showNoDataMessage("revenueChart", "Analytics data not available");
        showNoDataMessage("userGrowthChart", "Analytics data not available");
    }

    // Initialize progress bars with data attributes
    initializeProgressBars();
});

function initRevenueChart(data) {
    const ctx = document.getElementById("revenueChart");
    if (!ctx) return;

    new Chart(ctx.getContext("2d"), {
        type: "line",
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: "Revenue",
                    data: data.values,
                    borderColor: "rgb(59, 130, 246)",
                    backgroundColor: "rgba(59, 130, 246, 0.1)",
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return "Rp " + value.toLocaleString("id-ID");
                        },
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });
}

function initUserGrowthChart(data) {
    const ctx = document.getElementById("userGrowthChart");
    if (!ctx) return;

    new Chart(ctx.getContext("2d"), {
        type: "bar",
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: "New Users",
                    data: data.values,
                    backgroundColor: "rgba(34, 197, 94, 0.8)",
                    borderColor: "rgb(34, 197, 94)",
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });
}

function showNoDataMessage(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = `<div class="flex items-center justify-center h-full text-gray-500 text-sm">${message}</div>`;
    }
}

function initializeProgressBars() {
    // Find all progress bars with data-width attributes
    const progressBars = document.querySelectorAll(".progress-bar[data-width]");

    progressBars.forEach((bar) => {
        const width = bar.getAttribute("data-width");
        if (width) {
            // Set the width with animation
            setTimeout(() => {
                bar.style.width = width + "%";
            }, 100);
        }
    });
}

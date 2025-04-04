@extends('layouts.app')

@section('content')
<div style='background-color: lightgrey; width: 90%; border: 2px solid green;padding-left:1%; margin-top: 1%; margin-right: 5%;margin-left: 5%;'>
<div class="container mt-5">
    <h2 class="text-center mb-4">Order Type Summary</h2>

    <!-- Filter Options -->
    <div class="d-flex justify-content-center mb-4">
        <button class="btn mx-1 filter-btn active" data-filter="day">Day</button>
        <button class="btn mx-1 filter-btn" data-filter="week">Week</button>
        <button class="btn mx-1 filter-btn" data-filter="month">Month</button>
        <button class="btn mx-1 filter-btn" data-filter="year">Year</button>
        <button class="btn mx-1" id="custom-filter-btn">Custom</button>
    </div>

    <!-- Custom Date Range Selection -->
    <div class="row justify-content-center mb-3" id="custom-date-range" style="display: none;">
        <input type="date" id="start-date" class="form-control mx-1" style="width: 150px;">
        <input type="date" id="end-date" class="form-control mx-1" style="width: 150px;">
        <button class="btn" id="apply-custom-filter">Apply</button>
    </div>

    <!-- Chart and Table Layout -->
    <div class="row">
        <!-- Left Side - Chart -->
        <div class="col-md-6 mb-4">
            <div class="card rounded shadow h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Distribution <span id="chart-date-range"></span></h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 400px;">
                    <canvas id="orderChart" height="350" width="350"></canvas>
                </div>
            </div>
        </div>

        <!-- Right Side - Summary Table -->
        <div class="col-md-6 mb-4">
            <div class="card rounded shadow h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Summary <span id="table-date-range"></span></h5>
                </div>
                <div class="card-body" style="min-height: 400px;">
                    <div class="table-responsive">
                        <table class="table" id="summaryTable">
                            <thead>
                                <tr>
                                    <th>Order Type</th>
                                    <th class="text-right">Count</th>
                                </tr>
                            </thead>
                            <tbody id="summaryTableBody">
                                <!-- Table content will be added dynamically -->
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td>TOTAL</td>
                                    <td class="text-right" id="totalOrders">0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Include Chart.js -->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let orderChart;
        const ctx = document.getElementById("orderChart").getContext("2d");

        // Add styles for the active button and table
        const style = document.createElement('style');
        style.innerHTML = `
            .filter-btn, #custom-filter-btn, #apply-custom-filter {
                background-color: #3D6553;
                color: white;
                border-radius: 20px;
                padding: 8px 20px;
                border: none;
            }
            .filter-btn.active, #custom-filter-btn.active {
                background-color: #535350 !important;
                color: white !important;
            }
            .text-right {
                text-align: right;
            }
            .card {
                border-radius: 10px;
                border: none;
            }
            .card-header {
                background-color: #f8f9fa;
                border-bottom: 1px solid #eee;
            }
            #summaryTable tbody tr:nth-child(even) {
                background-color: #f8f9fa;
            }
            #summaryTable tfoot {
                border-top: 2px solid #dee2e6;
            }
            #summaryTable {
                margin-bottom: 0;
            }
            #chart-date-range, #table-date-range {
                font-size: 14px;
                color: #6c757d;
                font-weight: normal;
            }
        `;
        document.head.appendChild(style);

        // Function to format date
        function formatDate(date) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        // Function to update the date range in titles
        function updateDateRangeTitles(filter, startDate = '', endDate = '') {
            const chartDateRange = document.getElementById('chart-date-range');
            const tableDateRange = document.getElementById('table-date-range');
            let dateRangeText = '';

            const today = new Date();

            switch(filter) {
                case 'day':
                    dateRangeText = `(${formatDate(today)})`;
                    break;
                case 'week':
                    // Calculate first day of current week (Sunday)
                    const firstDayOfWeek = new Date(today);
                    const day = today.getDay(); // 0 for Sunday
                    firstDayOfWeek.setDate(today.getDate() - day);

                    dateRangeText = `(${formatDate(firstDayOfWeek)} - ${formatDate(today)})`;
                    break;
                case 'month':
                    // First day of current month
                    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                    dateRangeText = `(${formatDate(firstDayOfMonth)} - ${formatDate(today)})`;
                    break;
                case 'year':
                    // First day of current year
                    const firstDayOfYear = new Date(today.getFullYear(), 0, 1);
                    dateRangeText = `(${formatDate(firstDayOfYear)} - ${formatDate(today)})`;
                    break;
                case 'custom':
                    if (startDate && endDate) {
                        const start = new Date(startDate);
                        const end = new Date(endDate);
                        dateRangeText = `(${formatDate(start)} - ${formatDate(end)})`;
                    }
                    break;
            }

            chartDateRange.textContent = dateRangeText;
            tableDateRange.textContent = dateRangeText;
        }

        function fetchOrderSummary(filter, startDate = '', endDate = '') {
            let url = `/api/ordertypesummary?filter=${filter}`;
            if (filter === "custom") {
                url += `&start_date=${startDate}&end_date=${endDate}`;
            }

            // Update date range titles
            updateDateRangeTitles(filter, startDate, endDate);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const orderLabels = data.map(order => order.orderType);
                    const orderCounts = data.map(order => order.total);

                    // Update the chart
                    if (orderChart) {
                        orderChart.destroy();
                    }

                    orderChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: orderLabels,  // Order Type Names
                            datasets: [{
                                data: orderCounts, // Order Counts
                                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#9C27B0', '#FF9800', '#607D8B', '#795548'],
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (tooltipItem) {
                                            let value = tooltipItem.raw; // Get value of the segment
                                            let label = orderLabels[tooltipItem.dataIndex];
                                            return `${label}: ${value}`;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Update the summary table
                    updateSummaryTable(data);
                });
        }

        // Function to update the summary table
        function updateSummaryTable(data) {
            const tableBody = document.getElementById('summaryTableBody');
            tableBody.innerHTML = ''; // Clear existing data

            let totalOrders = 0;

            // Add each order type to the table
            data.forEach((order, index) => {
                const row = document.createElement('tr');

                // Add alternating background to rows
                if (index % 2 === 0) {
                    row.classList.add('bg-light');
                }

                const typeCell = document.createElement('td');
                typeCell.textContent = order.orderType;

                const countCell = document.createElement('td');
                countCell.className = 'text-right';
                countCell.textContent = order.total;

                row.appendChild(typeCell);
                row.appendChild(countCell);
                tableBody.appendChild(row);

                totalOrders += parseInt(order.total);
            });

            // Update the total count
            document.getElementById('totalOrders').textContent = totalOrders;
        }

        // Function to remove active class from all buttons
        function resetActiveButtons() {
            document.querySelectorAll(".filter-btn, #custom-filter-btn").forEach(btn => {
                btn.classList.remove("active");
            });
        }

        // Handle filter button clicks
        document.querySelectorAll(".filter-btn").forEach(button => {
            button.addEventListener("click", function () {
                resetActiveButtons();
                this.classList.add("active");

                const filter = this.getAttribute("data-filter");
                fetchOrderSummary(filter);
                document.getElementById("custom-date-range").style.display = "none";
            });
        });

        // Show custom date inputs
        document.getElementById("custom-filter-btn").addEventListener("click", function () {
            resetActiveButtons();
            this.classList.add("active");
            document.getElementById("custom-date-range").style.display = "flex";
        });

        // Apply custom date filter
        document.getElementById("apply-custom-filter").addEventListener("click", function () {
            const startDate = document.getElementById("start-date").value;
            const endDate = document.getElementById("end-date").value;
            if (startDate && endDate) {
                fetchOrderSummary("custom", startDate, endDate);
            }
        });

        // Load default data for today
        fetchOrderSummary("day");
    });
</script>
@endsection

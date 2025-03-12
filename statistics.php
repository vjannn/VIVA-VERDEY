<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Viva Verde - Admin Clothing Requests</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        input, .editable {
            text-align: center;
        }
        .editable:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }
        .deleteButton {
            background-color: transparent;
            color: red;
            cursor: pointer;
            border: none;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            loadDonationData();
            document.getElementById("saveButton").addEventListener("click", postData); // Button to save data
        });

        // Load data from the server
        function loadDonationData() {
            fetch("https://s2201585.helioho.st/VivaVerde/clothing_needs.php") // Replace with your actual domain
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Log the response data
                    const table = document.getElementById("donationTable");
                    table.innerHTML = ""; // Clear existing data
                    const donationCenters = [];
                    const clothingQuantities = [];

                    data.forEach((entry, index) => {
                        console.log(entry); // Log each entry to check the structure
                        const newRow = table.insertRow();
                        newRow.innerHTML = `
                            <td class='py-2 px-4 border text-center editable' contenteditable='true'>${entry.center || 'N/A'}</td>
                            <td class='py-2 px-4 border text-center editable' contenteditable='true'>${entry.category || 'N/A'} - ${entry.outfit_type || 'N/A'}</td>
                            <td class='py-2 px-4 border text-center editable' contenteditable='true'>${entry.amount || 'N/A'}</td>
                            <td class='py-2 px-4 border text-center'>
                                <button class="deleteButton" onclick="deleteRow(${index})">Delete</button>
                            </td>
                        `;
                        // Prepare data for the chart
                        donationCenters.push(entry.center + ' - ' + entry.category + ' - ' + entry.outfit_type); // Label: Donation center + Category + Clothing type
                        clothingQuantities.push(entry.amount || 0); // Data: Amount needed (default to 0 if undefined)
                    });

                    // Call the function to create the bar chart after the data is loaded
                    createBarChart(donationCenters, clothingQuantities);
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Function to delete a row
        function deleteRow(index) {
            const table = document.getElementById("donationTable");
            const row = table.rows[index];
            if (row) {
                row.remove();
                // Optionally: Make an API call to delete the data from the server if needed
                deleteRowFromServer(index);
            }
        }

        // Example: Function to delete the row from the server (optional, if you want to persist the deletion)
        function deleteRowFromServer(index) {
            fetch("https://s2201585.helioho.st/VivaVerde/clothing_needs.php", {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ index: index }) // Send the index to identify the row to delete
            })
            .then(response => response.json())
            .then(result => {
                alert("Row deleted successfully!");
                console.log(result);
            })
            .catch(error => {
                console.error('Error deleting row:', error);
            });
        }

        // Post data to the server
        function postData() {
            const table = document.getElementById("donationTable");
            const rows = table.rows;
            const updatedData = [];

            // Loop through the table and collect the data
            for (let i = 0; i < rows.length; i++) {
                const center = rows[i].cells[0].textContent.trim();
                const clothing = rows[i].cells[1].textContent.trim();
                const quantity = rows[i].cells[2].textContent.trim();

                updatedData.push({ center, clothing, quantity });
            }

            // Send the updated data via POST
            fetch("https://s2201585.helioho.st/VivaVerde/clothing_needs.php", { // Replace with your actual domain
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(updatedData) // Send the data as JSON
            })
            .then(response => response.json())
            .then(result => {
                alert("Data saved successfully!");
                console.log(result);
            })
            .catch(error => {
                console.error('Error posting data:', error);
            });
        }

        // Function to create the bar chart
        function createBarChart(labels, data) {
            const ctx = document.getElementById("donationChart").getContext("2d");
            const donationChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels, // Labels for the x-axis
                    datasets: [{
                        label: 'Clothing Quantity Needed',
                        data: data, // Data for the y-axis
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Bar color
                        borderColor: 'rgba(75, 192, 192, 1)', // Border color
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true // Start y-axis from 0
                        }
                    }
                }
            });
        }
    </script>
</head>
<body class="bg-gray-200 font-roboto">
    <!-- Navbar -->
    <nav class="bg-green-800 text-white p-4 flex justify-between items-center">
        <div class="text-3xl font-bold italic">Viva Verde</div>
        <div class="space-x-4">
            <a href="index.php" class="text-white px-4 py-2">Home</a>
            <a href="statistics.php" class="text-white px-4 py-2">Statistics</a>
            <a href="category.php" class="text-white px-4 py-2">Categories</a>
            <a href="#contact" class="text-white px-4 py-2">Contact Us</a>
        </div>
        <div class="text-2xl">
            <i class="fas fa-user-circle"></i>
        </div>
    </nav>

    <!-- Statistics Section -->
    <div class="container mx-auto p-4">
        <h1 class="text-green-800 font-bold text-2xl mb-4">Donation Center Statistics</h1>

        <!-- Bar Chart -->
        <canvas id="donationChart" width="400" height="200"></canvas>

        <!-- Table for Data -->
        <table class="min-w-full bg-white border border-gray-300 shadow-md rounded-lg overflow-hidden mt-6">
            <thead>
                <tr class="bg-green-600 text-white">
                    <th class="py-2 px-4 border">Donation Center</th>
                    <th class="py-2 px-4 border">Clothing Type</th>
                    <th class="py-2 px-4 border">Quantity Needed</th>
                    <th class="py-2 px-4 border">Actions</th> <!-- Added for delete button -->
                </tr>
            </thead>
            <tbody id="donationTable"></tbody>
        </table>

        <!-- Save Button -->
        <div class="container mx-auto p-4 text-center">
            <button id="saveButton" class="bg-green-600 text-white py-2 px-4 rounded-lg">Save Changes</button>
        </div>
    </div>
</body>
</html>

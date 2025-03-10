<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Viva Verde</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .chatbox {
            transition: all 0.3s ease;
        }
        /* Ensure the input field does not overflow */
        #chatbox-input {
            overflow: hidden; /* Prevent overflow */
            white-space: nowrap; /* Prevent text wrapping */
        }
        /* Set the size of the map */
        #map {
            height: 500px; /* Set a fixed height for the map */
            width: 100%; /* Full width */
        }
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-gray-200 font-roboto">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <div class="bg-green-800 text-white flex justify-between items-center p-4">
            <div class="text-3xl font-bold italic">Viva Verde</div>
            <div class="text-xl">We Grow</div>
            <div class="text-2xl">
                <i class="fas fa-user-circle"></i>
            </div>
        </div>
        <!-- Main Content -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            <div class="bg-green-800 text-white w-1/4 p-4 flex flex-col items-center space-y-4">
                <div class="bg-green-600 p-4 rounded-lg w-full flex justify-center">
                    <i class="fas fa-user text-3xl"></i>
                </div>
                <button class="bg-gray-200 text-green-800 p-4 rounded-lg w-full flex items-center justify-center space-x-2">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistics</span>
                </button>
                <button id="chatbox-toggle" class="bg-gray-200 text-green-800 p-4 rounded-lg w-full flex items-center justify-center space-x-2">
                    <i class="fas fa-comments"></i>
                    <span>Chatbox</span>
                </button>
                <button id="categories-toggle" class="bg-gray-200 text-green-800 p-4 rounded-lg w-full flex items-center justify-center space-x-2">
                    <i class="fas fa-list"></i>
                    <span>Categories</span>
                </button>
            </div>
            <!-- Main Panel -->
            <div class="flex-1 p-4 space-y-4">
                <!-- Buttons -->
                <div class="flex space-x-4">
                    <button class="bg-green-800 text-white p-4 rounded-lg flex items-center space-x-2 shadow-lg">
                        <i class="fas fa-hand-holding-heart"></i>
                        <span>Donors</span>
                    </button>
                </div>
                <!-- Map and List -->
                <div class="flex space-x-4">
                    <!-- Map -->
                    <div id="map" class="bg-white p-4 rounded-lg shadow-lg w-full">
                        <!-- Leaflet map will be rendered here -->
                    </div>
                    <!-- List -->
                    <div class="bg-green-800 text-white p-4 rounded-lg shadow-lg w-1/2 space-y-4">
                        <div class="bg-yellow-500 text-green-800 p-4 rounded-lg flex items-center justify-between">
                            <span>Jann Chester</span>
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="bg-yellow-500 text-green-800 p-4 rounded-lg flex items-center justify-between">
                            <span>Godfred</span>
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="bg-yellow-500 text-green-800 p-4 rounded-lg flex items-center justify-between">
                            <span>Ernest</span>
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                </div>
                <!-- Categories Index -->
                <div class="bg-white p-4 rounded-lg shadow-lg w-full space-y-4">
                    <h2 class="text-green-800 font-bold text-lg">Categories</h2>
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="border-b-2 border-green-800 p-2">ID</th>
                                <th class="border-b-2 border-green-800 p-2">Categories</th>
                                <th class="border-b-2 border-green-800 p-2">QTY</th>
                                <th class="border-b-2 border-green-800 p-2">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border-b p-2">1</td>
                                <td class="border-b p-2">Plants</td>
                                <td class="border-b p-2">50</td>
                                <td class="border-b p-2">Various types of plants</td>
                            </tr>
                            <tr>
                                <td class="border-b p-2">2</td>
                                <td class="border-b p-2">Seeds</td>
                                <td class="border-b p-2">100</td>
                                <td class="border-b p-2">Seed packets for planting</td>
                            </tr>
                            <!-- More categories can be added here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Chatbox -->
        <div id="chatbox" class="fixed bottom-0 right-0 bg-white w-80 h-96 shadow-lg rounded-t-lg flex flex-col chatbox hidden">
            <div class="bg-green-800 text-white p-4 flex justify-between items-center rounded-t-lg">
                <span>Chatbox</span>
                <button id="chatbox-close" class="text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="chatbox-messages" class="flex-1 p-4 overflow-y-auto">
                <!-- Chat messages will go here -->
            </div>
            <div class="p-4">
                <input id="chatbox-input" type="text" maxlength="2000" class="w-full p-2 border rounded-lg" placeholder="Type a message..." style="resize: none;"/>
                <div id="char-count" class="text-right text-gray-500 text-sm mt-1">0/2000</div>
            </div>
        </div>
        <!-- Categories Modal -->
        <div id="categories-modal" class="modal">
            <div class="modal-content">
                <span id="modal-close" class="float-right cursor-pointer text-red-500">&times;</span>
                <h2 class="text-green-800 font-bold text-lg">Categories</h2>
                <table class="min-w-full mt-4">
                    <thead>
                        <tr>
                            <th class="border-b-2 border-green-800 p-2">ID</th>
                            <th class="border-b-2 border-green-800 p-2">Categories</th>
                            <th class="border-b-2 border-green-800 p-2">QTY</th>
                            <th class="border-b-2 border-green-800 p-2">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border-b p-2">1</td>
                            <td class="border-b p-2">Plants</td>
                            <td class="border-b p-2">50</td>
                            <td class="border-b p-2">Various types of plants</td>
                        </tr>
                        <tr>
                            <td class="border-b p-2">2</td>
                            <td class="border-b p-2">Seeds</td>
                            <td class="border-b p-2">100</td>
                            <td class="border-b p-2">Seed packets for planting</td>
                        </tr>
                        <!-- More categories can be added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Initialize the map
        const map = L.map('map').setView([51.505, -0.09], 13); // Set initial view (latitude, longitude, zoom level)

        // Add a tile layer (background map)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Array to hold markers
        const markers = [];

        // Function to add a marker
        function addMarker(lat, lng) {
            if (markers.length >= 5) {
                alert("Maximum of 5 locations reached.");
                return;
            }

            const marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            markers.push(marker);

            // Bind a popup to the marker
            marker.bindPopup('New Location').openPopup();

            // Event listener for marker drag end
            marker.on('dragend', function (e) {
                const position = marker.getLatLng();
                marker.setLatLng(position, { draggable: true }).bindPopup('Location: ' + position.lat + ', ' + position.lng).openPopup();
            });

            // Event listener for marker click to delete or rename
            marker.on('click', function () {
                const action = prompt("Enter 'delete' to remove this pin or a new name to rename it:");
                if (action === 'delete') {
                    map.removeLayer(marker);
                    markers.splice(markers.indexOf(marker), 1); // Remove from markers array
                } else if (action) {
                    marker.bindPopup(action).openPopup(); // Rename the marker
                }
            });
        }

        // Event listener for map click to add a marker
        map.on('click', function (e) {
            addMarker(e.latlng.lat, e.latlng.lng);
        });

        // Chatbox functionality
        const chatboxToggle = document.getElementById('chatbox-toggle');
        const chatbox = document.getElementById('chatbox');
        const chatboxClose = document.getElementById('chatbox-close');
        const chatboxMessages = document.getElementById('chatbox-messages');
        const chatboxInput = document.getElementById('chatbox-input');
        const charCount = document.getElementById('char-count');

        chatboxToggle.addEventListener('click', () => {
            chatbox.classList.toggle('hidden');
        });

        chatboxClose.addEventListener('click', () => {
            chatbox.classList.add('hidden');
        });

        chatboxInput.addEventListener('input', () => {
            charCount.textContent = `${chatboxInput.value.length}/2000`;
        });

        chatboxInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && chatboxInput.value.trim() !== '') {
                const message = document.createElement('div');
                message.classList.add('bg-green-200', 'p-2', 'rounded-lg', 'mb-2', 'self-end', 'break-words'); // Added break-words for wrapping
                message.textContent = chatboxInput.value;
                chatboxMessages.appendChild(message);
                chatboxInput.value = '';
                charCount.textContent = `0/2000`; // Reset character count
                chatboxMessages.scrollTop = chatboxMessages.scrollHeight;
            }
        });

        // Categories modal functionality
        const categoriesToggle = document.getElementById('categories-toggle');
        const categoriesModal = document.getElementById('categories-modal');
        const modalClose = document.getElementById('modal-close');

        categoriesToggle.addEventListener('click', () => {
            categoriesModal.style.display = "block";
        });

        modalClose.addEventListener('click', () => {
            categoriesModal.style.display = "none";
        });

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target === categoriesModal) {
                categoriesModal.style.display = "none";
            }
        };
    </script>
</body>
</html>
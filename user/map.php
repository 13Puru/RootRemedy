<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/favicon.png" type="png" />
    <title>Plant Locations with Leaflet</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        /* Modern Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            line-height: 1.6;
            background-color: #f4f6f9;
            color: #333;
        }

        /* Header Styling */
        .header-container {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-container h1 {
            font-weight: 600;
            font-size: 2.25rem;
            letter-spacing: -0.05em;
            margin-bottom: 0.5rem;
        }

        .header-subtext {
            font-weight: 300;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
        }

        /* Map Container */
        #map-container {
            position: relative;
            width: 100%;
            height: calc(100vh - 120px); /* Adjust based on header height */
            border-radius: 12px;
            overflow: hidden;
            margin: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        #map {
            height: 100%;
            width: 100%;
            z-index: 1;
            transition: filter 0.3s ease;
        }

        /* Leaflet Popup Customization */
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .leaflet-popup-content {
            font-family: 'Inter', sans-serif;
            padding: 15px;
        }

        .leaflet-popup-content h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 600;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }

        .leaflet-popup-content p {
            color: #34495e;
            margin: 5px 0;
        }

        /* Responsive Adjustments */
        @media screen and (max-width: 768px) {
            .header-container h1 {
                font-size: 1.75rem;
            }

            #map-container {
                margin: 0.5rem;
                height: calc(100vh - 100px);
            }
        }

        /* Slight Hover Effect for Interaction */
        .leaflet-marker-icon {
            transition: transform 0.2s ease;
        }

        .leaflet-marker-icon:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="header-container">
        
        <h1>Plant Locations Map</h1>
        <div class="header-subtext">Explore Green Spaces Across the Region</div>
    </div>
    
    <div id="map-container">
        <div id="map"></div>
    </div>

    <script>
        // Previous JavaScript remains unchanged
        const map = L.map("map").setView([26.2006, 92.9376], 6);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        fetch("getlocations.php")
            .then(response => response.json())
            .then(data => {
                data.forEach(location => {
                    const marker = L.marker([location.lat, location.lng]).addTo(map);

                    marker.bindPopup(
                        `<h3>${location.name}</h3>
                         <p>${location.address}</p>
                         <p>Type: ${location.type}</p>`
                    );
                });
            })
            .catch(error => console.error("Error fetching location data:", error));
    </script>
</body>
</html>
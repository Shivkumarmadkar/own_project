<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <title>Popup Example</title>
    <style>
        .popup-modal {
            display: none;
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.6); /* Black w/ opacity */
        }

        .popup-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            max-width: 500px; /* Limit width */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header-container {
            display: flex;
            align-items: center; 
            margin-bottom: 15px; 
        }

        .welcome-text {
            flex: 1; 
            font-size: 24px; 
            font-weight: bold; 
            color: #333; 
        }

        .candidate-info {
            display: flex;
            flex-direction: column; 
            align-items: center; 
            margin-left: 15px; 
        }

        .candidate-info img {
            width: 60px; 
            height: 60px;
            border-radius: 50%; 
            margin-bottom: 5px; 
            border: 2px solid #4CAF50; 
        }

        .candidate-info span {
            font-weight: bold; 
            color: #333; 
            text-align: center; 
        }

        .popup-buttons {
            display: flex;
            justify-content: space-between; 
            margin-top: 20px;
        }

        .popup-buttons button {
            flex: 1; 
            margin: 0 5px; 
            padding: 10px;
            border: none;
            border-radius: 5px;
            background:linear-gradient(to right, #0575E6, #00F260); 
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .popup-buttons button:hover {
            background-color: #45a049; /* Darker green */
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .popup-buttons {
                flex-direction: column; 
            }

            .popup-buttons button {
                margin: 5px 0; /* Space between buttons */
            }
        }
    </style>
</head>
<body>
    <!-- Popup Modal -->
    <div id="popupModal" class="popup-modal">
        <div class="popup-content">
            <div class="header-container">
                <div class="welcome-text">Welcome!</div>
                <div class="candidate-info">
                    <img id="candidatePicture" src="" alt="Candidate Picture">
                    <span id="candidateName"></span>
                </div>
            </div>
            <p>Please choose an option:</p>
            <div class="popup-buttons">
                <button onclick="redirectTo('mainindex.php')">Raise Your Complaint</button>
                <button onclick="redirectTo('kypollingbooth.php')">Know Your Polling Booth</button>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            fetchCandidateData();
            document.getElementById('popupModal').style.display = 'block';
        };      

        function closePopup() {
            document.getElementById('popupModal').style.display = 'none';
        }

        function redirectTo(page) {
            window.location.href = page;
        }

        function fetchCandidateData() {
            fetch('fetch_candidate.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                    } else {
                        document.getElementById('candidateName').textContent = data.name;
                        document.getElementById('candidatePicture').src = data.picture; 
                    }
                })
                .catch(error => console.error('Error fetching candidate data:', error));
        }
    </script> <!-- Link to your JavaScript file -->
</body>
</html>

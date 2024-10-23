<?php
include 'db_connect.php';

?>
<!DOCTYPE html>
<html lang="mr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Form with Profile and Carousel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=1.0">
    <link rel="stylesheet" href="complaint.css?v=1.0">
    <link rel="stylesheet" href="complaintotp.css?v=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="script.js"></script> 
    <style>
        .hidden {
            display: none;
        }
    </style>

</head>

<body>

    <section class="container-fluid text-center mt-3">
        <div class="row justify-content-center">
            <div class="d-flex flex-row align-items-center border-0 justify-content-center ">
                <div class="col-md-12 col-sm-12 col-xs-12 p-3 tertury">
                    <h1><strong class="lang-marathi">धन्यवाद !</strong></h1>
                    <h1 class="hidden lang-english"><strong>Thank you!</strong></h1>
                </div>
            </div>
        </div>

      
    <div class="row justify-content-center" id="candidateProfileContainer">

    </div>

        <div class="row justify-content-center mt-5">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <p class="lang-marathi">आमची टीम तुमच्या तक्रारीची काळजीपूर्वक पाहणी करेल <br>
                    आणि तुमच्या समस्यांचे निराकरण करु.</p>
                <p class="hidden lang-english">Our team will carefully review your complaint <br>
                    and resolve your issues.</p>
            </div>
        </div>

        <div class="row justify-content-center mt-2">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <i class="fa-solid fa-circle-check checkmark-icon"></i>
            </div>
        </div>

        <div class="row justify-content-center mt-2">
    <div class="col-md-6 col-sm-6 col-xs-12 text-center">
        <p class="header-description lang-marathi">
            <i class="fab fa-whatsapp whatsapp-icon me-2"></i>
            आम्ही तुम्हाला व्हाट्सअप वर तपशील पाठवला आहे.
        </p>
        <p class="hidden header-description lang-english">
            <i class="fab fa-whatsapp whatsapp-icon me-2"></i>
            We have sent you the details via WhatsApp.
        </p>
    </div>
</div>



        <div class="row justify-content-center mt-1">
    <div class="col-md-4 col-sm-6 text-center">
        <a href="http://localhost/Mounarch/Election_task_COPY/preindex.php" 
           class="btn btn-outline-primary btn-sm home-button">
            <i class="fas fa-home me-1"></i> Home
        </a>
    </div>
</div>


    </section>

    <footer class="footer">
        <div class="container text-center d-flex flex-column justify-content-center align-items-center">
            <div class="social-icons py-1">
                <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <div class="copyright py-1">
                <p>&copy; 2024 Election App. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
      // Function to get URL parameters
function getUrlParameter(name) {
    const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    const results = regex.exec(location.search);
    return results ? decodeURIComponent(results[1].replace(/\+/g, ' ')) : null;
}

// Function to toggle language visibility
function toggleLanguageVisibility(langParam) {
    const langElementsMarathi = document.querySelectorAll('.lang-marathi');
    const langElementsEnglish = document.querySelectorAll('.lang-english');

    if (langParam === 'english') {
        langElementsMarathi.forEach(el => el.classList.add('hidden')); // Hide Marathi
        langElementsEnglish.forEach(el => el.classList.remove('hidden')); // Show English
    } else {
        langElementsMarathi.forEach(el => el.classList.remove('hidden')); // Show Marathi
        langElementsEnglish.forEach(el => el.classList.add('hidden')); // Hide English
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Check for language parameter in the URL
    const langParam = getUrlParameter('lang') || 'marathi'; // Default to 'marathi' if not found

    // Toggle visibility for static content
    toggleLanguageVisibility(langParam);

    // AJAX call to fetch candidate data
    $.ajax({
        url: 'fetch_candidate.php', // URL to the PHP file
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.error) {
                $('#candidateProfileContainer').html('<p>' + response.error + '</p>');
            } else {
                const profileHTML = `
                    <div class="col-md-12 col-sm-12 text-center">
                        <img src="${response.picture}" class="rounded-circle profile-image" id="candidate_profile" alt="Profile Image">
                    </div>
                    <div class="col-md-4 col-sm-4 mt-3">
                        <div class="card-body">
                            <h5 class="card-title" id="candidate_name"><strong>${response.name}</strong></h5>
                            <p class="card-text mt-3 header-description lang-marathi">हडपसर विधानसभा मतदारसंघ</p>
                            <p class="card-text mt-3 header-description hidden lang-english">Hadapsar Assembly Constituency</p>
                        </div>
                    </div>`;
                
                // Inject profile content
                $('#candidateProfileContainer').html(profileHTML);

                // Toggle visibility for dynamic content
                toggleLanguageVisibility(langParam);
            }
        },
        error: function (xhr, status, error) {
            console.error(`Error: ${error}`);
            $('#candidateProfileContainer').html('<p>Failed to fetch candidate data. Please try again later.</p>');
        }
    });
});

        </script>


</body>

</html>

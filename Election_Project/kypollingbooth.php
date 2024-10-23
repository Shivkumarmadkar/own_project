
<?php
include 'db_connect.php';
include 'fetch_categories.php';

?>

<!DOCTYPE html>
<html lang="mr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELECTION APP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=1.0">
    <link rel="stylesheet" href="complaint.css?v=1.0">
    <link rel="stylesheet" href="complaintotp.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Include SweetAlert CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>

<body>
    

    <header class="header text-center text-white">
        <h6 class="h6">तुमची समस्या लवकर सोडवणे हा आमचा उद्देश आहे.</h6>
    </header>


    <section class="container-fluid text-center">
    <div class="row justify-content-center" id="candidateProfileContainer">
      
    </div>
</section>


<section class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="bg5.jpg" class="" alt="Image 1" height="400px" width="100%" style='object-fit:initial;'>
                        </div>
                        <div class="carousel-item">
                            <img src="pg9.avif" class="" alt="Image 2" height="400px" width="100%">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <form id="MainForm" action="thankyou.php" method="POST"> -->

    <form id="MainForm"> 


<div class="container-fluid text-center ">
        <!-- <h5 class="header-description">Choose Language:</h5> -->
        <div class="language-selection d-flex justify-content-center ">
            <label class="me-3">
                <input type="radio" name="language" value="marathi" checked onchange="switchLanguage()">
                <span class="language-label">मराठी</span>
            </label>
            <label>
                <input type="radio" name="language" value="english" onchange="switchLanguage()">
                <span class="language-label">English</span>
            </label>
        </div>
    </div>


        <!-- index section start -->
        <section id="indexSection" >
        <div class="text-center ">
                <h5 class="header-description  header-desc">तुमची मतदान संदर्भातील माहिती जाणून घेण्यासाठी कृपया वैध मतदार, वार्ड क्रमांक <br>आणि मोबाइल क्रमांक प्रविष्ट करा.</h5>
            </div>
            <div class="container form-section ">
          
            <div class="row justify-content-center">
                <div class="col-md-4 col-sm-8 col-12">
                  
                    <div class=" text-center">
    <label for="voterId" class="form-label">मतदार ओळखपत्र क्रमांक :</label>
    <input type="text" class="form-control text-center votertextarea" id="voterId" name="voterId" maxlength="10">
    <small class="text-danger" id="voterIdError" style="display: none;">Voter ID must be exactly 10 characters long.</small>
    </div>

                    <div class="form-group text-center">
                    <label for="ward_no" class="form-label">वार्ड क्रमांक :</label>

                        <div class="dropdown-container ">
                            <select id="ward_no" name="ward_no" class="text-center form-control ">
                                <option value="">वार्ड क्रमांक निवडा</option>
                            </select>
                        </div>
                    </div>
                    <div class=" text-center">
                        <label for="mo_no" class="form-label">मोबाइल क्रमांक :</label>
                        <input type="text" class="form-control text-center " id="mo_no"
                            name="mo_no" maxlength="10"  oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                    </div>
                    <div class="d-flex justify-content-center text-center">
                        <button type="button" class="btn next" id="nextToComplaint">पुढे</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-4 ">
            <button id="stepButton1"   class="step-btn one active" disabled>1</button>
           
            <span class="arrow two">→</span>
            <button id="stepButton3" class="step-btn" disabled>2</button>
        </div>
        </section>

        
        <!-- index section end -->
         

        <!-- complaint section start -->
        <!-- <section id="complaintSection" style="display: none;">
       
            <div class="container complaint-section mt-3 p-3">
            <div class="row justify-content-center">
            <div class="label-container prob_sector mb-3">
                        <label for="label" class="label">समस्या विभाग निवडा:</label>
                    </div>
                <div class="col-md-6 col-sm-8 col-12 mt-3">
                    <div class="form-group">
                        <div class="radio-group">
                           
                            <div class="category-container" id="category-container">
</div>

                        </div>
                    </div>


                    <div class="label-container perticular_reason mt-3">
                        <label for="label" class="label">विशिष्ट कारण निवडा:</label>
                    </div>
                
    <div class="form-group text-center mt-2">
        <div class="dropdown-container">
            <select id="problem" name="sub_cat_id" class="form-control">
            </select>
        </div>
    </div>

                    <div class="label-container prob_desc">
                        <label for="label" class="label">तुमच्या समस्येचे वर्णन करा:</label>
                    </div>

                    <div class="form-group text-center">
                        <textarea class="form-control probtextarea" id="problem_description" name="problem_description" placeholder="तुमच्या समस्येचे येथे वर्णन करा" rows="4"></textarea>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <button type="button" class="btn next" id="nextToComplaintOtp">पुढे</button>
                    </div>
                </div>
            </div>
        </div>

            <div class="d-flex justify-content-center mt-3">
                <button id="stepButton4" class="step-btn oneone"disabled >1</button>
                <span class="arrow first active ">→</span>
                <button id="stepButton5" class="step-btn second active" disabled>2</button>
                <span class="arrow second">→</span>
                <button id="stepButton6" class="step-btn three" disabled>3</button>
            </div>
    
        </section> -->
        <!-- complaint section end -->

        <!-- complaint otp section start -->
        <section id="complaintOtpSection" style="display: none;">
            <div  class="container complaint-section mt-3 p-3" >
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10">
                  

    <div class="container">
    <div class="row responsive-labels mb-3 align-items-center">
        <div class="col-md-3 text-start col-6">
            <label for="namelable" class="namelable fw-bold">नाव:</label>
        </div>
        <div class="col-md-3 col-6">
            <label for="votername" class="fw-bold" id="votername"></label>
        </div>
        <div class="col-md-3 text-start col-6">
            <label for="mobilelable" class="mobilelable fw-bold">मोबाईल नं:</label>
        </div>
        <div class="col-md-3 col-6">
            <label for="votermobile" class="fw-bold" id="votermobile"></label>
        </div>
    </div>
</div>


                        <div class="form-group mb-3">
                            <h5 class="text-center info_desc ">तुमची माहिती</h5>
                            <textarea class="form-control textarea"
                                id="info_description" name="info_description" rows="8"
                                placeholder="तुमची समस्या इथे लिहा..."
                                style="resize: none;"></textarea>
                        </div>

        <div id="input-fields-container">
        <div class="form-group">
        <input type="hidden" id="pb_no" class="form-control">
    </div>
    <div class="form-group">
        <input type="hidden" id="fathers_husbands_name" class="form-control">
    </div>
    <div class="form-group">
        <input type="hidden" id="age" class="form-control">
    </div>
    <div class="form-group">
        <input type="hidden" id="gender" class="form-control" >
    </div>
    <div class="form-group">
        <input type="hidden" id="pb_name" class="form-control" >
    </div>
    <div class="form-group">
        <input type="hidden" id="pb_address" class="form-control" >
    </div>
  
</div>

                    <div class="form-row row g-3 text-center">
                        <div class="col-6">
                            <label for="otplable"
                                class="col-form-label" style="font-size: medium;">ओ.टी.पी.:</label>
                        </div>
                        <div class="col-6 ">
                            <input type="text"
                                class="form-control text-center otptext " id="voterotp"
                                name="voterotp" placeholder="ओटीपी प्रविष्ट करा">
                        </div>
                    </div>

                    <div class="form-row row g-3 text-center">
                        <div class="col-12 resendotpdiv">
                            <a href="#" class="text-white resendotp" style="font-size: medium;">ओ. टी. पी. पुन्हा पाठवा</a>
                        </div>
                    </div>
                  

    <div class="form-check-box mb-3">
        <!-- WhatsApp Icon -->
        <i class="fab fa-whatsapp whatsapp-icon"></i>

        <!-- Checkbox Input -->
        <input type="checkbox" class="form-checkbox-input" id="ischeckhelp" name="ischeckhelp" checked required>

        <!-- Label -->
        <label class="form-checkbox-label" for="ischeckhelp">
            तुम्हाला ही माहिती तुमच्या व्हाट्सअ‍ॅपवर हवी आहे का?
        </label>
    </div>


                    <div class="d-flex justify-content-center">
                        <button type="submit" id="submit_mainform" class="btn next">पाठवा</button>
                    </div>
                </div>
            </div>
        </div>


            
        <div class="d-flex justify-content-center mt-3">
            <button id="stepButton7" class="step-btn lastone" disabled>1</button>
          
            <span class="arrow lastsecond">→</span>
            <button id="stepButton9" class="step-btn lastthree active" disabled>2</button>
        </div>
        </section>
        <!-- complaint otp section end -->

    </form>
    <br>
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



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="kypbscript.js"></script> 



</body>

</html>

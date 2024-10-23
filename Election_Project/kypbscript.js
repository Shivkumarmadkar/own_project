
//TO FETCH WARD NO FOR DROPDOWN

document.addEventListener('DOMContentLoaded', function () {
    fetchWards();
});

function fetchWards() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_ward_no.php', true); // Make a request to your PHP file
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('ward_no').innerHTML += xhr.responseText;
        } else {
            console.error('Failed to fetch wards.');
        }
    };
    xhr.send();
}

// WARD NO FETCH END  

//voterid validationn for uppercase and 10 digit start

document.getElementById('voterId').addEventListener('input', function() {
    // Convert input to uppercase
    this.value = this.value.toUpperCase();

    // Check if the length is exactly 10 digits
    const errorMessage = document.getElementById('voterIdError');
    if (this.value.length > 10 || !/^[A-Z0-9]*$/.test(this.value)) {
        errorMessage.style.display = 'block';
    } else {
        errorMessage.style.display = 'none';
    }
});

//voterid validationn for uppercase and 10 digit end


//fetch voter details from voterid and wardno 

document.addEventListener('DOMContentLoaded', () => {
    const voterIdInput = document.getElementById('voterId');
    const wardNoSelect = document.getElementById('ward_no');
    const mo_no = document.getElementById('mo_no');


    function fetchVoterData() {
        const voterId = voterIdInput.value.trim();
        const wardNo = wardNoSelect.value;

        if (voterId && wardNo) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'fetch_voter_detail_data.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {


                            const mo_no = document.getElementById('mo_no');
                            const voterNameElement = document.getElementById('votername');
                            const voterContactElement = document.getElementById('votermobile');

                            if (voterNameElement) {
                                voterNameElement.textContent = response.data.name || 'N/A';
                            }

                      

                            document.getElementById('pb_no').value = response.data.pb_no || '';
                            document.getElementById('pb_name').value = response.data.pb_name || '';
                            document.getElementById('pb_address').value = response.data.pb_address || '';
                            document.getElementById('fathers_husbands_name').value = response.data.fathers_husbands_name || '';
                            document.getElementById('age').value = response.data.age || '';
                            document.getElementById('gender').value = response.data.gender || '';
                          
                        } else {
                            toastr.error(getAlertMessage('invalidVoterId'), 'Error');
                            voterIdInput.value = '';
                            mo_no.value='';
                            wardNoSelect.selectedIndex = 0;
                        }
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        toastr.error('Unexpected response format. Please try again.', 'Error');
                    }
                } else {
                    console.error('Error fetching data:', xhr.statusText);
                    toastr.error('Failed to fetch data. Please try again later.', 'Error');
                }
            };

            xhr.onerror = function () {
                console.error('Request error');
                toastr.error('Network error. Please try again.', 'Network Error');
            };

            xhr.send(`voterId=${encodeURIComponent(voterId)}&wardNo=${encodeURIComponent(wardNo)}`);
        }
    }

    voterIdInput.addEventListener('input', fetchVoterData);
    wardNoSelect.addEventListener('change', fetchVoterData);
});

// TO FETCH VOTER DETAIL LOGIC END 


// START LOGIC TO SEND OTP AGAINST MOBILE NO 


$(document).ready(function () {

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right", // Adjust position
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000", // Auto-hide duration
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    function validateInputs() {
        const mobileNo = $('#mo_no').val().trim();
        const voterId = $('#voterId').val().trim();
        const wardNo = $('#ward_no').val(); //

        if (voterId === '') {
            toastr.warning(getAlertMessage('emptyVoterId'), 'Warning'); 
            return false;
        }

        // Check if the voter ID is invalid
    if (!/^[A-Z0-9]{10}$/.test(voterId)) { // Assuming valid voter IDs are uppercase letters and numbers, exactly 10 characters long
        toastr.error(getAlertMessage('invalidVoterId'), 'Error');
        return false;
    }
       
        if (wardNo === '') { // Validate if ward_no is selected
            toastr.warning(getAlertMessage('emptyWardNo'), 'Warning'); // Use Toastr for warning
            return false;
        }
    
        if (mobileNo === '') {
            toastr.warning(getAlertMessage('emptyMobile'), 'Warning'); // Use Toastr for warning
            return false;
        }
    
        if (mobileNo.length !== 10) {
            toastr.error(getAlertMessage('Mobilevalidation'), 'Error'); // Use Toastr for error
            $('#mo_no').val('');
            return false;
        }

        return true;
    }

    $('#nextToComplaint').on('click', function () {
        const isValid = validateInputs();

        if (isValid) {
            sendOtp();
            $('#indexSection').hide();
            $('#complaintOtpSection').show();
        }
    });

    function sendOtp() {
        const mobileNo = $('#mo_no').val().trim();
        const voterId = $('#voterId').val().trim();

        if (!validateInputs()) return;

        $.ajax({
            url: 'send_mo_otp.php',
            type: 'POST',
            data: { mobile: mobileNo, voterId: voterId },
            success: function (response) {
                console.log(response);

                if (response.error) {
                    toastr.error(getAlertMessage('otpError'), 'Error'); // Use Toastr for error messages
                } else {
                    toastr.success(getAlertMessage('otpSent'), 'Success'); // Use Toastr for success messages
                    sessionStorage.setItem('sentOtp', response.otp);
                }
            },
            error: function (xhr, status, error) {
                console.error(`Error: ${error}`);
                toastr.error(getAlertMessage('otpError'), 'Error'); // Use Toastr for network errors
            }
        });
    }

    $('.resendotp').on('click', function (e) {
        e.preventDefault();  // Prevent default anchor tag behavior

        // Enable the input box and clear its value
        $('#voterotp').prop('disabled', false).val('');

        sendOtp();  // Resend OTP
    });

});

let otpVerified = false;

$(document).ready(function () {
    $('#voterotp').on('input', function () {
        const mobileNo = $('#mo_no').val().trim(); // Get the mobile number
        const enteredOtp = $(this).val().trim();   // Get the entered OTP

        if (enteredOtp.length === 6) {
            // AJAX request to verify OTP
            $.ajax({
                url: 'verify_otp.php',
                type: 'POST',
                data: { mobile: mobileNo, otp: enteredOtp },
                success: function (response) {
                    if (response.error) {
                        toastr.error(response.message, 'Error'); // Toastr error message
                        $('#voterotp').val('');
                        otpVerified = false; 
                    } else {
                        toastr.success(response.message, 'Success'); // Toastr success message
                        $('#voterotp').prop('disabled', true);
                        otpVerified = true;

                        // Disable resend button styles
                        $('.resendotpdiv').css({
                            'cursor': 'not-allowed',
                            'pointer-events': 'none',
                            'display': 'none'
                        });

                        $('.resendotp').css({
                            'cursor': 'not-allowed',
                            'color': 'gray',
                            'text-decoration': 'none',
                            'display': 'none'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error(`Error: ${error}`);
                    toastr.error('Failed to verify OTP. Please try again.', 'Error'); // Toastr error
                    $('#voterotp').val('');
                    otpVerified = false; 
                }
            });
        }
    });
});



//verify enter otp logic end 



const translations = {
    marathi: {
        h6:"तुमची समस्या लवकर सोडवणे हा आमचा उद्देश आहे.",
        head_description:"हडपसर विधानसभा मतदारसंघ",
        indexHeader: "तुमची मतदान संदर्भातील माहिती जाणून घेण्यासाठी कृपया वैध मतदार, वार्ड क्रमांक आणि मोबाइल क्रमांक प्रविष्ट करा.",
        voterIdLabel: "मतदार ओळखपत्र क्रमांक :",
        voterinput: "वैध मतदार क्रमांक प्रविष्ट करा",
        wardLabel: "वार्ड क्रमांक :",
        wardPlaceholder: "वार्ड क्रमांक निवडा", // Ward Number in Marathi
        mobileLabel: "मोबाइल क्रमांक :",
        nextButton: "पुढे",
        complaintSelectLabel: "समस्या विभाग निवडा:",
        specificReasonLabel: "विशिष्ट कारण निवडा:",
        emptyCategory: 'कृपया समस्या विभाग निवडा!', // Marathi for "Please select a category!"
        emptySubCategory: 'कृपया विशिष्ट समस्या निवडा!', // Marathi for "Please select a subcategory!"

        namelable: "नाव:",
        mobilelable: "मोबाईल:",
        info_desc: "तुमची माहिती",
        otpLabel: "ओ.टी.पी.:", // OTP label
        resendOtp: "ओ. टी. पी. पुन्हा पाठवा", // Resend OTP text
        sendButton: "पाठवा", // Send button text
        textareaPlaceholder: "तुमची माहिती...", // Textarea placeholder
        checkHelpLabel: "तुम्हाला ही माहिती तुमच्या व्हाट्सअ‍ॅपवर हवी आहे का?",

        // Alert messages
        Mobilevalidation:'कृपया 10 अंकी मोबाईल क्रमांक प्रविष्ट करा',
        emptyMobile: 'कृपया मोबाइल क्रमांक प्रविष्ट करा!',
        emptyVoterId: 'कृपया मतदार ओळखपत्र क्रमांक प्रविष्ट करा!',
        emptyWardNo: 'कृपया वार्ड क्रमांक निवडा!',
        otpError: 'OTP पाठवण्यात अडचण आली. कृपया पुन्हा प्रयत्न करा.',
        otpSent: 'OTP तुमच्या मोबाईल नं. वर यशस्वीरित्या पाठवण्यात आला आहे!.',
        complaintSuccess: 'तुमची माहिती यशस्वीरित्या नोंदवली गेली आहे!',
        complaintError: 'फॉर्म सबमिट करण्यात अडचण आली. कृपया पुन्हा प्रयत्न करा.',
        mandatoryFields: 'सर्व फील्ड अनिवार्य आहेत. कृपया ते भरा.',
        invalidVoterId: 'कृपया एक वैध मतदार ओळखपत्र क्रमांक प्रविष्ट करा किंवा योग्य वार्ड क्रमांक निवडा.',
        verifyotpmsg :'कृपया मोबाइल ओटीपी प्रविष्ट करून फॉर्म सबमिट करण्यापूर्वी त्याची पडताळणी करा',

    },
    english: {
        h6:"Our goal is to resolve your issue promptly",
        head_description:"Hadapsar Assembly Constituency",
        indexHeader: "Please enter a Valid Voter ID, Ward No. and Mobile No. to know your polling information.",
        voterIdLabel: "Voter ID :",
        voterinput: "Enter valid voter id",
        wardLabel: "Ward No:",
        wardPlaceholder: "Select Ward No. ",
        mobileLabel: "Mobile No :",
        nextButton: "Next",
        complaintSelectLabel: "Select Complaint Department:",
        specificReasonLabel: "Select Specific Reason:",
        emptyCategory: 'Please select category department', // Marathi for "Please select a category!"
        emptySubCategory: 'please select subcategory', // Marathi for "Please select a subcategory!"

        // risingCosts: "Rising Healthcare Costs",
        namelable: "Name:",
        mobilelable: "Mobile:",
        info_desc: "Your Information",
        otpLabel: "OTP:", // OTP label
        resendOtp: "Resend OTP", // Resend OTP text
        sendButton: "Submit", // Send button text
        textareaPlaceholder: "your info.", // Textarea placeholder
        checkHelpLabel: "Would you like to have this information on your WhatsApp?",

        Mobilevalidation:'please Enter 10 digit Mobile No.',
        emptyMobile: 'Please enter your mobile number!',
        emptyVoterId: 'Please enter your voter ID number!',
        emptyWardNo: 'Please select a ward number!',
        otpError: 'Error sending OTP. Please try again.',
        otpSent: 'OTP sent successfully on your Mobile No.',
        complaintSuccess: 'form has been submitted successfully!',
        complaintError: 'Failed to submit the form. Please try again.',
        mandatoryFields: 'All fields are mandatory. Please fill them out.',
        invalidVoterId: 'Please enter a valid voter ID or select the correct ward number.',
        verifyotpmsg :'Please verify your OTP before submitting the form.',

    }
};

function switchLanguage() {
    const selectedLanguage = document.querySelector('input[name="language"]:checked').value;
    const selectedWard = document.getElementById('ward_no').value;
    // Update index section
    document.querySelector('.h6').textContent = translations[selectedLanguage].h6;
    document.querySelector('.header-description').textContent = translations[selectedLanguage].head_description;

    
    document.querySelector('.header-desc').textContent = translations[selectedLanguage].indexHeader;
    document.querySelector('label[for="voterId"]').textContent = translations[selectedLanguage].voterIdLabel;
    document.querySelector('#voterId').placeholder = translations[selectedLanguage].voterinput;

    document.querySelector('label[for="ward_no"]').textContent = translations[selectedLanguage].wardLabel;
    document.querySelector('label[for="mo_no"]').textContent = translations[selectedLanguage].mobileLabel;
    document.querySelector('#nextToComplaint').textContent = translations[selectedLanguage].nextButton;

    const wardSelect = document.getElementById('ward_no');
    wardSelect.options[0].text = translations[selectedLanguage].wardPlaceholder;
    // Reset the selected value to the first option (placeholder)
    wardSelect.selectedIndex = 0;

    // Update complaint section labels and other texts
    document.querySelector('.prob_sector label').textContent = translations[selectedLanguage].complaintSelectLabel;
    document.querySelector('.perticular_reason label').textContent = translations[selectedLanguage].specificReasonLabel;

    // Update dropdown option
    // document.querySelector('#problem option').textContent = translations[selectedLanguage].risingCosts;

    // Update button


    document.querySelector('.namelable').textContent = translations[selectedLanguage].namelable;
    document.querySelector('.mobilelable').textContent = translations[selectedLanguage].mobilelable;

    document.querySelector('.info_desc').textContent = translations[selectedLanguage].info_desc;

    // Update textarea placeholder
    document.querySelector('#info_description').placeholder = translations[selectedLanguage].textareaPlaceholder;

    // Update OTP section
    document.querySelector('label[for="otplable"]').textContent = translations[selectedLanguage].otpLabel; // Update OTP label
    document.querySelector('#voterotp').placeholder = translations[selectedLanguage].otpLabel === "ओ.टी.पी.:"
        ? "ओटीपी प्रविष्ट करा"
        : "Enter OTP"; // Placeholder for OTP input
    document.querySelector('.resendotp').textContent = translations[selectedLanguage].resendOtp; // Update resend OTP text

    document.querySelector('label[for="ischeckhelp"]').textContent = translations[selectedLanguage].checkHelpLabel;

    document.querySelector('#submit_mainform').textContent = translations[selectedLanguage].sendButton; // Update send button text

    document.getElementById('ward_no').value = selectedWard;
}
function getAlertMessage(key) {
    const selectedLanguage = document.querySelector('input[name="language"]:checked').value;
    return translations[selectedLanguage][key];
}

document.addEventListener('DOMContentLoaded', function () {
    switchLanguage(); 
});

function collectAndShowInfoInOtpSection() {
    const voterId = document.getElementById('voterId').value || 'N/A';
    const wardNo = document.getElementById('ward_no').value || 'N/A';
    const mobileNo = document.getElementById('mo_no').value || 'N/A';
    const pb_no = document.getElementById('pb_no').value || 'N/A';
      let pb_name = document.getElementById('pb_name').value || 'N/A';
      pb_name = pb_name.replace(/\n/g, '').trim(); 

    let pb_address = document.getElementById('pb_address').value || 'N/A';
    pb_address = pb_address.replace(/\n/g, '').trim(); 

    const fathers_husbands_name = document.getElementById('fathers_husbands_name').value || 'N/A';
    const age = document.getElementById('age').value || 'N/A';
    const gender = document.getElementById('gender').value || 'N/A';
    const votername = document.getElementById('votername').innerText || 'N/A';

    function alignText(label, value) {
        const maxLabelLength = 20; // Adjust this value if needed
        return `${label.padEnd(maxLabelLength)} : ${value}`;
    }

//     // Format data with proper alignment
//     const infoText = `
// ${alignText('Voter ID', voterId)}      ${alignText('Ward No', wardNo)}
// ${alignText('Mobile No', mobileNo)}      ${alignText('PB No', pb_no)}
// ${alignText('Father/Husband', fathers_husbands_name)}          ${alignText('Age', age)}
// ${alignText('Gender', gender)}            ${alignText('Category', categoryName)}
// ${alignText('Subcategory', subCategoryName)}
// ${alignText('pb_name', pb_name)}
// ${alignText('Problem Description', problemDescription)}
//     `;

infoText = `
Voter ID             : ${voterId}
votername            : ${votername}
Father/Husband       : ${fathers_husbands_name}
Ward No              : ${wardNo}
Mobile No            : ${mobileNo}
PB No                : ${pb_no}
pb name              : ${pb_name}
pb address           : ${pb_address}
Age                  : ${age}
Gender               : ${gender}
`.trim();

    // Set the formatted text in the textarea with trimmed text
    document.getElementById('info_description').value = infoText.trim();
}
// Function to attach input change listeners to all relevant fields
function attachInputListeners() {
    // Attach input listener to each relevant input field
    document.getElementById('voterId').addEventListener('input', collectAndShowInfoInOtpSection);
    document.getElementById('ward_no').addEventListener('input', collectAndShowInfoInOtpSection);
    document.getElementById('mo_no').addEventListener('input', collectAndShowInfoInOtpSection);
    
    const mobileInput = document.getElementById('mo_no');
    mobileInput.addEventListener('input', function() {
        // Update votermobile label with the current mobile number
        document.getElementById('votermobile').innerText = mobileInput.value;
        // Call the info collection function to update the info_description
        collectAndShowInfoInOtpSection();
    });
    // Attach change listener for subcategory dropdown
}

// Call this function to set up listeners on page load
attachInputListeners();


$(document).ready(function () {
    $('#MainForm').on('submit', function (event) {
        event.preventDefault();

        if (!otpVerified) {
            toastr.warning(getAlertMessage('verifyotpmsg'), 'Warning');
            return; // Stop the form submission
        }

        const formData = {
            voterId: $('#voterId').val().trim(),
            mo_no: $('#mo_no').val().trim(),
            pb_no: $('#pb_no').val().trim(),
            pb_name: $('#pb_name').val().trim(), 
            pb_address: $('#pb_address').val().trim(), 
            fathers_husbands_name: $('#fathers_husbands_name').val().trim(),
            age: $('#age').val(), 
            gender: $('#gender').val().trim(),
            votername: $('#votername').text().trim(),   
        };

        console.log('Form data being sent:', formData); // Log form data

        $.ajax({
            url: 'post_kypollingbooth_data.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log('Raw response:', response); // Log the raw response
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        toastr.success(getAlertMessage('complaintSuccess'), 'Success'); // Toastr for success
                        console.log('Success:', result.message);
                        const selectedLanguage = $('input[name="language"]:checked').val();
                     setTimeout(function () {
                     window.location.href = "thankyou.php?lang=" + selectedLanguage; 
                    }, 2000);           
                                              
                    } else {
                        toastr.error(result.message, 'Error'); // Display error message if success is false
                        console.log('Server Error:', result.message); // Log server error
                    }
                } catch (e) {
                    console.error('Parsing error:', e);
                    toastr.error('Failed to parse response: ' + e.message, 'Error'); // Toastr for parsing error
                }
            },
            error: function (xhr, status, error) {
                console.error(`Error: ${error}`);
                toastr.error(getAlertMessage('complaintError'), 'Error'); // Toastr for request error
            }
        });
    });
});


//candidate details fetch logic

$(document).ready(function () {
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
                            <div class="col-md-8 col-sm-12">
                                <div class="card d-flex flex-column flex-md-row align-items-center border-0 justify-content-center profile-card">
                                    <div class="col-md-4 col-sm-12 text-center">
                                        <img src="${response.picture}" class="rounded-circle profile-image" id="candidate_profile" alt="Profile Image">
                                    </div>
                                    <div class="col-md-6 col-sm-12 "> 
                                        <div class="card-body">
                                            <h5 class="card-title" id="candidate_name"><strong>${response.name}</strong></h5>
                                            <p class="card-text header-description">हडपसर विधानसभा मतदारसंघ</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                $('#candidateProfileContainer').html(profileHTML);
            }
        },
        error: function (xhr, status, error) {
            console.error(`Error: ${error}`);
            $('#candidateProfileContainer').html('<p>Failed to fetch candidate data. Please try again later.</p>');
        }
    });
});


//candidate details logic end


document.querySelectorAll('.carousel-indicators button').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default scrolling behavior
    });
});

// for fix bug of auto scrolling on top due to carasol

document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.getElementById('carouselExampleControls');
    const formSection = document.getElementById('indexsection');

    // Prevent default behavior of carousel indicators and controls
    document.querySelectorAll('.carousel-indicators button, .carousel-control-prev, .carousel-control-next').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default action
            event.stopPropagation(); // Stop the event from bubbling
        });
    });

    carousel.addEventListener('slide.bs.carousel', function () {
        // Before sliding starts
    });

    carousel.addEventListener('slid.bs.carousel', function () {
        // After sliding ends, keep the focus on the form section
        formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    // Disable auto-focus on the carousel
    carousel.setAttribute('tabindex', '-1'); // Make it non-focusable

    // Prevent any scrolling caused by focus
    window.addEventListener('focusin', function (event) {
        const activeElement = document.activeElement;
        if (activeElement && activeElement !== formSection) {
            formSection.focus(); // Focus back to the form section
        }
    });
});







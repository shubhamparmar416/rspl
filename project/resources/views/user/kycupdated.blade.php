<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://uigaint.com/demo/html/anfraa/kyc-1/assets/css/bootstrap.min.css">
    <!-- External Css -->
    <link rel="stylesheet" href="https://uigaint.com/demo/html/anfraa/kyc-1/assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="https://uigaint.com/demo/html/anfraa/kyc-1/assets/css/niceCountryInput.css">
    <!-- Custom Css -->
    <link rel="stylesheet" type="text/css" href="https://uigaint.com/demo/html/anfraa/kyc-1/assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="https://uigaint.com/demo/html/anfraa/kyc-1/assets/css/kyc-1.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/favicon.png">
    <link rel="apple-touch-icon" href="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72"
        href="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114"
        href="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/icon-114x114.png">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

<body>
    <div class="ugf-bg ufg-main-container">
        <div class="ugf-progress">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 66.66%" aria-valuenow="66.66"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 p-sm-0">
                    <div class="ugf-form">
                        <form id="step_1_form" method="post" enctype="multipart/form-data">
                            <div class="steps1">
                                <!-- Step 1 content -->
                                <div class="input-block">
                                    <div class="steps1">
                                        <div class="input-block">
                                            <h4>Verify Aadhardd</h4>
                                            <div class="file-input-wrap">
                                                <div class="custom-file">
                                                    <!-- <input type="file" class="custom-file-input" id="nidf"> -->
                                                    <input type="file" name="fId" class="custom-file-input"
                                                        id="upload-input-1" accept="image/*"
                                                        onchange="previewImage(event, 1)" required>
                                                    <label class="custom-file-label" for="upload-input-1"><img src="#"
                                                            id="preview-1" alt="icon" class="img-fluid preview"><img
                                                            src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/cloud.png"
                                                            class="upload-icon"></label>
                                                    <span class="text">National ID Frontd</span>
                                                </div>
                                                <div class="custom-file">
                                                    <!-- <input type="file" class="custom-file-input" id="nidb"> -->
                                                    <input type="file" name="bId" class="custom-file-input"
                                                        id="upload-input-2" accept="image/*"
                                                        onchange="previewImage(event, 2)" required>
                                                    <label class="custom-file-label" for="upload-input-2"><img src="#"
                                                            id="preview-2" alt="icon" class="img-fluid preview"><img
                                                            src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/cloud.png"
                                                            class="upload-icon"></label>
                                                    <span class="text">National ID Back</span>
                                                </div>
                                            </div>
                                            <div class="conditions">
                                                <ul>
                                                    <li class="complete">File accepted: JPEG/JPG/PNG (Max size: 250 KB)
                                                    </li>
                                                    <li>Document should be good condition</li>
                                                    <li>Document must be valid period</li>
                                                    <li>Face must be clear visible</li>
                                                    <li>Note has today’s date</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="input-block">
                                            <h4>Aadhar Number</h4>
                                            <div class="form-group">
                                                <input type="text" name="aadhar_number" class="form-control"
                                                    id="aadhar_number" onblur="verifyDocApi('aadhaar')"
                                                    placeholder="e.g. 1234567890123456" required pattern="\d{16}">
                                                    <img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/check-green.svg" id="aadhaar_check_image" style="position: absolute; top: 25px;right: 15px;display: none;">
                                                <div class="invalid-feedback">
                                                    Please enter a valid 16-digit Aadhar number.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-block">
                                            <h4>Verify Pan</h4>
                                            <div class="file-input-wrap">
                                                <div class="custom-file">
                                                    <!-- <input type="file" class="custom-file-input" id="nidf"> -->
                                                    <input type="file" name="fIdp" class="custom-file-input"
                                                        id="upload-input-3" accept="image/*"
                                                        onchange="previewImage(event, 3)" required>
                                                    <label class="custom-file-label" for="upload-input-3"><img src="#"
                                                            id="preview-3" alt="icon" class="img-fluid preview"><img
                                                            src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/cloud.png"
                                                            class="upload-icon"></label>
                                                    <span class="text">National ID Front</span>
                                                </div>
                                                <div class="custom-file">
                                                    <!-- <input type="file" class="custom-file-input" id="nidb"> -->
                                                    <input type="file" name="bIdp" class="custom-file-input"
                                                        id="upload-input-4" accept="image/*"
                                                        onchange="previewImage(event, 4)" required>
                                                    <label class="custom-file-label" for="upload-input-4"><img src="#"
                                                            id="preview-4" alt="icon" class="img-fluid preview"><img
                                                            src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/cloud.png"
                                                            class="upload-icon"></label>
                                                    <span class="text">National ID Back</span>
                                                </div>
                                            </div>
                                            <div class="conditions">
                                                <ul>
                                                    <li class="complete">File accepted: JPEG/JPG/PNG (Max size: 250 KB)
                                                    </li>
                                                    <li>Document should be good condition</li>
                                                    <li>Document must be valid period</li>
                                                    <li>Face must be clear visible</li>
                                                    <li>Note has today’s date</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="input-block">
                                            <h4>Pan Number</h4>
                                            <div class="form-group">
                                                <input type="text" name="panNumber" class="form-control" id="pan-number"
                                                    onblur="verifyDocApi('pan')" placeholder="e.g. ABCDE1234F" required
                                                    pattern="[A-Z]{5}[0-9]{4}[A-Z]">
                                                    <img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/check-green.svg" id="pan_check_image" style="position: absolute; top: 25px;right: 15px;display: none;">
                                                <div class="invalid-feedback">
                                                    Please enter a valid PAN number (e.g., ABCDE1234F).
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form action="" id="step_2_form" method="post"  enctype="multipart/form-data">
                            <div class="steps2" style="display: none;">
                                <!-- Step 2 content -->
                                <div class="input-block">
                                    <div class="steps2">

                                        <div class="input-block">
                                            <h4>Select Identity Type</h4>
                                            <p>Should be your government issue photo identity</p>
                                            <fieldset>
                                                <div class="custom-form-group">
                                                    <input type="radio" name="package-plan" class="custom-form-control"
                                                        id="nid" required>
                                                    <label for="nid">
                                                        <span class="text">Bank Statement</span>
                                                        <span class="icon">
                                                            <img src="assets/images/nid.png" class="img-fluid" alt="">
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="custom-form-group">
                                                    <input type="radio" name="package-plan" class="custom-form-control"
                                                        id="passport" required>
                                                    <label for="passport">
                                                        <span class="text">Net Banking</span>
                                                        <span class="icon">
                                                            <img src="assets/images/passport.png" class="img-fluid"
                                                                alt="">
                                                        </span>
                                                    </label>
                                                </div>

                                            </fieldset>
                                        </div>
                                        <div class="input-block bank" id="bankSection" style="display:none;">
                                            <h4>Bank Statement</h4>
                                            <div class="file-input-wrap">
                                                <div class="custom-file">
                                                    <!-- <input type="file" class="custom-file-input" id="nidf"> -->
                                                    <input type="file" name="fId" class="-file-input" id="" required>
                                                    <!-- <span class="text">National ID Front</span> -->
                                                </div>
                                            </div>
                                            <div class="conditions">
                                                <ul>
                                                    <li class="complete">File accepted: JPEG/JPG/PNG (Max size: 250 KB)
                                                    </li>
                                                    <li>Document should be good condition</li>
                                                    <li>Document must be valid period</li>
                                                    <li>Face must be clear visible</li>
                                                    <li>Note has today’s date</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="netbank" id="netbankSection" style="display:none;">
                                            <h4>Select Identity Type</h4>
                                            <p>Should be your government issue photo identity</p>
                                            <div class="input-block">
                                                <h4>Username</h4>
                                                <div class="form-group">
                                                    <input type="text" name="nidNumber" class="form-control"
                                                        placeholder="e.g.  1234 0256 0145" id="nid-number" required>
                                                </div>
                                            </div>
                                            <div class="input-block">
                                                <h4>Password</h4>
                                                <div class="form-group">
                                                    <input type="text" name="nidNumber" class="form-control"
                                                        placeholder="e.g.  1234 0256 0145" id="nid-number" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-block">
                                <!-- Navigation buttons -->
                                <button type="button" class="btn prev-step" style="display: none;">&lt;
                                    Previous</button>
                                <button type="button" data-form_name="step_1_form" disabled class="btn next-step">Next &gt;</button><!--disabled-->
                            </div>
                        </form>
                        <!-- <a href="kyc.html" class="back-to-prev"><img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/arrow-left-grey.png" alt=""> Back to Previous</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/js/jquery.min.js"></script>
    <script src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/js/popper.min.js"></script>
    <script src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/js/bootstrap.min.js"></script>
    <script src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/js/niceCountryInput.js"></script>
    <!-- jquery-validate js -->
    <script src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/js/jquery.validate.min.js"></script>
    <script src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/js/custom.js"></script>

    <script>
        var isDocumentCorrect = 0;
        var isPanCorrect = 0;
        var isAadhaarCorrect = 0;
        $(document).ready(function () {
            var currentStep = 1;

            $('.next-step').on('click', function () {
                //var form_name = $(this).data('form_name');

                //var data = $("#"+form_name).serialize();

                var formData = new FormData();
                formData.append('username', 'Chris');
                formData.append('fId', document.getElementById('upload-input-1').files[0]);
                formData.append('bId', document.getElementById('upload-input-2').files[0]);
                formData.append('fIdp', document.getElementById('upload-input-3').files[0]);
                formData.append('bIdp', document.getElementById('upload-input-4').files[0]);
                formData.append('aadhar_number',document.getElementById('aadhar_number').value);
                formData.append('pan-number',document.getElementById('pan-number').value);

                console.log(formData);
                console.log(document.getElementById('upload-input-1').files[0]);
                console.log(document.getElementById('aadhar_number').value);
               
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: "{{URL::to('/user/kyc/step1_document')}}",
                    data: {data:formData},
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data){
                        alert(data.data);
                    }
                });

                // Hide current step
                $('.steps' + currentStep).hide();

                // Show next step
                currentStep++;
                $('.steps' + currentStep).show();

                // Display or hide navigation buttons based on the step
                $('.prev-step').toggle(currentStep > 1);
                $('.next-step').toggle(currentStep < 2);

                // Show/hide sections based on radio button selection
                if ($('#nid').prop('checked')) {
                    $('#bankSection').show();
                    $('#netbankSection').hide();
                } else if ($('#passport').prop('checked')) {
                    $('#bankSection').hide();
                    $('#netbankSection').show();
                }
            });

            $('.prev-step').on('click', function () {
                // Hide current step
                $('.steps' + currentStep).hide();

                // Show previous step
                currentStep--;
                $('.steps' + currentStep).show();

                // Display or hide navigation buttons based on the step
                $('.prev-step').toggle(currentStep > 1);
                $('.next-step').toggle(currentStep < 2);

                // Show/hide sections based on radio button selection
                if ($('#nid').prop('checked')) {
                    $('#bankSection').show();
                    $('#netbankSection').hide();
                } else if ($('#passport').prop('checked')) {
                    $('#bankSection').hide();
                    $('#netbankSection').show();
                }
            });

            // Handle radio button change event
            $('input[name="package-plan"]').on('change', function () {
                if ($('#nid').prop('checked')) {
                    $('#bankSection').show();
                    $('#netbankSection').hide();
                } else if ($('#passport').prop('checked')) {
                    $('#bankSection').hide();
                    $('#netbankSection').show();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            // Function to validate 16-digit Aadhar number using regex
            function validateAadhar(aadharNumber) {
                // Aadhar number regex pattern (16 digits)
                var aadharRegex = /^\d{16}$/;

                return aadharRegex.test(aadharNumber);
            }

            // Event handler for Aadhar number input
            $('#nid-number').on('input', function () {
                var aadharNumber = $(this).val();

                // Check if the entered Aadhar number is valid
                if (validateAadhar(aadharNumber)) {
                    // Valid Aadhar number
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    // Invalid Aadhar number
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            // Function to validate PAN number using regex
            function validatePan(panNumber) {
                // PAN number regex pattern
                var panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]$/;

                return panRegex.test(panNumber);
            }

            // Event handler for PAN number input
            $('#pan-number').on('input', function () {
                var panNumber = $(this).val().toUpperCase();

                // Check if the entered PAN number is valid
                if (validatePan(panNumber)) {
                    // Valid PAN number
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    // Invalid PAN number
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }
            });
        });

        function verifyDocApi(type) {
            var document = type == 'aadhaar' ? $('#aadhar_number').val() : $('#pan-number').val();
            
            if(document!=""){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{URL::to('/user/kyc/document_verify')}}",
                    data: {
                        'document': document,
                        'type' : type
                    },
                    success: function (response) {
                        if (response.status) {
                            var jsonData = JSON.parse(response.data);
                            if(jsonData.result_code==101){
                                if(type=='aadhaar'){
                                    $("#aadhaar_check_image").show();
                                    isAadhaarCorrect = 1;
                                }
                                if(type=='pan'){
                                    $("#pan_check_image").show();
                                    isPanCorrect = 1;
                                }
                                if(isPanCorrect==1 && isAadhaarCorrect==1){
                                    $('.next-step').removeAttr("disabled");
                                }else{
                                    $('.next-step').prop("disabled", true);
                                }
                            }else{
                                alert(jsonData.message);
                            }
                        } else {
                            if(type=='aadhaar'){
                                $("#aadhaar_check_image").hide();
                                isAadhaarCorrect = 0;
                            }
                            if(type=='pan'){
                                $("#pan_check_image").hide();
                                isPanCorrect = 0;
                            }
                            $('.next-step').prop("disabled", true);

                        }
                    },
                    error: function (error) {
                        console.log(error)
                    }
                });
            }
        }
    </script>
</body>

</html>
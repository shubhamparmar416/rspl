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
    <style>
        #backgroundimage
        {
            /*top: 0;*/
    right: 0;
    bottom: 0;
    /*left: 0;*/
    opacity: 0.5;
            text-align: center;
           height: auto;
           left: 50%;
           /*margin: 0;*/
           /*min-height: 100%;
           min-width: 674px;*/
           padding: 0;
           position: fixed;
           top: 50%;
           /*width: 100%;*/
           z-index: 1;

        }
    </style>
</head>

<body>
    @if (isset($_GET['status']) && $_GET['status'] == 'cancle')
        <script type="text/javascript">
          alert('Kyc not done, Please try again.');
        </script>
    @endif
    <img id="backgroundimage" src="{{asset('assets/images/33CiUFaI1641808748.gif')}}" style="display: none;">
    <div class="ugf-bg ufg-main-container">
        <div class="ugf-progress">
            <div class="progress">
                <div class="progress-bar pb1" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar pb2" role="progressbar" style="width: 50%; display: none;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 p-sm-0">
                    <div class="ugf-form">
                        <!-- <div class="Loader" style="background-image: url({{asset('assets/images/33CiUFaI1641808748.gif')}}) no-repeat scroll center center #FFF;"></div> -->
                        
                        <form id="step_1_form" method="post" enctype="multipart/form-data">
                            <div class="steps1">
                                <!-- Step 1 content -->
                                <div class="input-block">
                                    <div class="steps1">
                                        <div class="input-block">
                                            <h4>Select Identity Type</h4>
                                            <!-- <p>Should be your government issue photo identity</p> -->
                                            <fieldset>
                                                <div class="custom-form-group">
                                                    <input type="radio" name="digilocker_type" value="digilocaker" class="custom-form-control" id="digilid" required>
                                                    <label for="digilid">
                                                        <span class="text">DigiLocker</span>
                                                        <span class="icon">
                                                            <img src="assets/images/nid.png" class="img-fluid" alt="">
                                                            <input type="hidden" id="digiLockerTransactionId" name="digiLockerTransactionId" value="">
                                                        </span>
                                                    </label>
                                                </div>
                                                
                                                <div class="custom-form-group">
                                                    <input type="radio" name="digilocker_type" value="non_digilocker" class="custom-form-control" id="nondigilid" required>
                                                    <label for="nondigilid">
                                                        <span class="text">Non Digilocker</span>
                                                        <span class="icon">
                                                            <img src="assets/images/passport.png" class="img-fluid"
                                                                alt="">
                                                        </span>
                                                    </label>
                                                </div>

                                            </fieldset>
                                        </div>
                                        <div class="digilocker" id="digilocker" style="display:none;">
                                            <?php
                                            // $userName = explode(" ", $user->name);
                                            ?>
                                            <!-- <h4>First Name</h4> -->
                                            <div class="form-group">
                                                <input type="hidden" value="{{ $user->name }}" name="digilocker_fname" class="form-control"
                                                    id="digilocker_fname" placeholder="e.g. John" required>
                                            </div>
                                            <!-- <h4>Last Name</h4> -->
                                            <div class="form-group">
                                                <input type="hidden" value="{{ $user->last_name ?? $user->name}}" name="digilocker_lname" class="form-control"
                                                    id="digilocker_lname" placeholder="e.g. Doe" required>
                                            </div>
                                            <!-- <h4>Mobile</h4> -->
                                            <div class="form-group">
                                                <input type="hidden" value="{{ $user->phone }}" name="digilocker_mobile" class="form-control"
                                                    id="digilocker_mobile" placeholder="e.g. 9876543210" required pattern="\d{10}">
                                            </div>
                                            <!-- <h4>Email</h4> -->
                                            <div class="form-group">
                                                <input type="hidden" value="{{ $user->email }}" name="digilocker_email" class="form-control"
                                                    id="digilocker_email" placeholder="e.g. abcxxx@gmail.com" required>
                                            </div>
                                            <div class="custom-form-group">
                                                <button type="button" class="btn digilocker-step" onclick="verifyDocLocker();">Digilocker Verification</button>
                                            </div>
                                        </div>
                                        <div id="nonDigiProcess" style="display: none;">
                                            <div class="input-block">
                                                <h4>Verify Aadhar</h4>
                                                    <?php //print_r($institutionStatement);die?>
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

                                            <div class="input-block">
                                                <h4>Verify Voter </h4>
                                                <div class="file-input-wrap">
                                                    <div class="custom-file">
                                                        <!-- <input type="file" class="custom-file-input" id="nidf"> -->
                                                        <input type="file" name="v_front" class="custom-file-input"
                                                            id="upload-input-4" accept="image/*"
                                                            onchange="previewImage(event, 4)" required>
                                                        <label class="custom-file-label" for="upload-input-4"><img src="#"
                                                                id="preview-4" alt="icon" class="img-fluid preview"><img
                                                                src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/cloud.png"
                                                                class="upload-icon"></label>
                                                        <span class="text">National ID Front</span>
                                                    </div>
                                                    <div class="custom-file">
                                                        <!-- <input type="file" class="custom-file-input" id="nidb"> -->
                                                        <input type="file" name="v_back" class="custom-file-input"
                                                            id="upload-input-5" accept="image/*"
                                                            onchange="previewImage(event, 5)" required>
                                                        <label class="custom-file-label" for="upload-input-5"><img src="#"
                                                                id="preview-5" alt="icon" class="img-fluid preview"><img
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
                                                <h4>Voter ID</h4>
                                                <div class="form-group">
                                                    <input type="text" name="voter_number"  onblur="verifyDocApi('voter')" class="form-control" id="voter_number" placeholder="e.g. ABCDE1234F" required
                                                        pattern="[A-Z]{5}[0-9]{4}[A-Z]">
                                                        <img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/check-green.svg" id="voter_check_image" style="position: absolute; top: 25px;right: 15px;display: none;">
                                                    <div class="invalid-feedback">
                                                        Please enter a valid Voter number.
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="input-block">
                                                <h4>Current Address</h4>
                                                <div class="form-group">
                                                    <input type="textarea" name="currentAddress" class="form-control" id="current-address" value="jlkjlk" placeholder="e.g. Vijay Narag Indore" required >
                                                        <!-- <img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/check-green.svg" id="pan_check_image" style="position: absolute; top: 25px;right: 15px;display: none;"> -->
                                                    <div class="invalid-feedback">
                                                        Please enter a current address.
                                                    </div>
                                                </div>
                                            </div> --}}
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
                                                    <input type="radio" name="package-plan" value="bank_stetment" class="custom-form-control" onchange="urlGenerate('stetment')" id="nid" required>
                                                    <label for="nid">
                                                        <span class="text">Bank Statement</span>
                                                        <span class="icon">
                                                            <img src="assets/images/nid.png" class="img-fluid" alt="">
                                                        </span>
                                                    </label>
                                                </div>
                                                
                                                <input type="hidden" id="urlGenerateType" value="stetment">
                                                <div class="custom-form-group">
                                                    <input type="radio" name="package-plan" value="net_banking" class="custom-form-control" onchange="urlGenerate('netBanking')" id="passport" required>
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
                                            <h4>Select Bank for Statement</h4>
                                            <div class="custom-form-group">
                                                <select name="institutionStatement" id="institutionStatement" class="form-control">
                                                    @foreach($institutionStatement->data as $institutes_name)
                                                        <option value="{{ $institutes_name->id }}">{{ $institutes_name->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- <div class="file-input-wrap">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="nidf">
                                                    <input type="file" name="fId" class="-file-input" id="" required>
                                                    <span class="text">National ID Front</span>
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
                                            </div> -->
                                        </div>
                                        <div class="netbank" id="netbankSection" style="display:none;">
                                            <h4>Select Bank for Netbanking</h4>
                                            <div class="custom-form-group">
                                                <select name="institutionNetbanking" id="institutionNetbanking" class="form-control">
                                                    @foreach($institutionNetbanking->data as $institutes_name1)
                                                        <option value="{{ $institutes_name1->id }}">{{ $institutes_name1->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- <p>Should be your government issue photo identity</p>
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
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-block">
                                <!-- Navigation buttons -->
                                <button type="button" style="display: none;" class="btn upload-step" onclick="uploadStetmentApi('stetment');">Next</button><br><br>
                                <!-- <button type="button" class="btn prev-step" style="display: none;">&lt;
                                    Previous</button>     -->
                                <button type="button" style="display: none;" data-form_name="step_1_form" disabled class="btn next-step">Next &gt;</button><!--disabled-->
                            </div>
                        </form>
                        <!-- <a href="kyc.html" class="back-to-prev"><img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/arrow-left-grey.png" alt=""> Back to Previous</a> -->

                        {{-- 3rd FORM START --}}

                        {{-- 3rd FORM END --}}
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
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script>
        var win = '';
        function urlGenerate(urlGenerateType) {
            $("#urlGenerateType").val(urlGenerateType);
        }
        var isDocumentCorrect = 0;
        var isPanCorrect = 0;
        var isAadhaarCorrect = 0; var isVoterCorrect = 0;
        $(document).ready(function () {
            var currentStep = 1;
            $('#nondigilid').prop('checked', true);
            $('#digilocker').hide();
            $('#nonDigiProcess').show();
            $('.next-step').show();

            $('.next-step').on('click', function () {
                //var form_name = $(this).data('form_name');
                //var data = $("#"+form_name).serialize();

                var formData = new FormData();
                formData.append('username', 'Chris');
                formData.append('fId', document.getElementById('upload-input-1').files[0]);
                formData.append('bId', document.getElementById('upload-input-2').files[0]);
                formData.append('fIdp', document.getElementById('upload-input-3').files[0]);
                formData.append('v_front', document.getElementById('upload-input-4').files[0]);
                formData.append('v_back', document.getElementById('upload-input-5').files[0]);
                formData.append('aadhar_number',document.getElementById('aadhar_number').value);
                formData.append('pan-number',document.getElementById('pan-number').value);
                formData.append('voter_number',document.getElementById('voter_number').value);

                if (document.getElementById('upload-input-1').files[0] == undefined) {
                    alert('Please upload Aadhar Front');
                    return false;
                }
                if (document.getElementById('upload-input-2').files[0] == undefined) {
                    alert('Please upload Aadhar Back');
                    return false;
                }
                if (document.getElementById('upload-input-3').files[0] == undefined) {
                    alert('Please upload Pan Front');
                    return false;
                }
                if (document.getElementById('upload-input-4').files[0] == undefined) {
                    alert('Please upload Voter Front');
                    return false;
                }
                if (document.getElementById('upload-input-5').files[0] == undefined) {
                    alert('Please upload Voter Back');
                    return false;
                }

                /*console.log(formData);
                console.log(document.getElementById('aadhar_number').value);*/
                $("#backgroundimage").show(); $(".container").hide();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: "{{URL::to('/user/kyc/step1_document')}}",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data){
                        console.log(data);
                        console.log(JSON.stringify(data));
                        if(data.status == 1) {
                            // alert(data.message);
                            $("#backgroundimage").hide();
                            $(".container").show();
                            // Hide current step
                            $('.steps' + currentStep).hide();

                            // Show next step
                            currentStep++;
                            $('.steps' + currentStep).show();
                            $('.upload-step').show();
                            $('.pb1').hide();
                            $('.pb2').show();

                            // Display or hide navigation buttons based on the step
                            $('.prev-step').toggle(currentStep > 1);
                            $('.next-step').toggle(currentStep < 2);

                            // Show/hide sections based on radio button selection
                            if ($('#nid').prop('checked')) {
                                /*$('#bankSection').show();*/
                                $('#netbankSection').hide();
                            } else if ($('#passport').prop('checked')) {
                                $('#bankSection').hide();
                                $('#netbankSection').show();
                            }
                        } else {
                            alert(data.error);
                            $("#backgroundimage").hide();
                            $(".container").show();
                            return false;
                        }
                    }
                });
                
            });

            $('.prev-step').on('click', function () {
                // Hide current step
                $('.steps' + currentStep).hide();
                $('.upload-step').hide();

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

            $('input[name="digilocker_type"]').on('change', function () {
                if ($('#digilid').prop('checked')) {
                    $('#digilocker').show();
                    $('#nonDigiProcess').hide();
                    $('.next-step').hide();
                } else if ($('#nondigilid').prop('checked')) {
                    $('#digilocker').hide();
                    $('#nonDigiProcess').show();
                    $('.next-step').show();
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
                      /*window.onscroll = function () {
    var scrolll = document.documentElement.scrollTop || document.body.scrollTop;
    console.log(scrolll);
};*/
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
            var pageyscroll = window.pageYOffset;
            var document;
            //var document = type == 'aadhaar' ? $('#aadhar_number').val() : $('#pan-number').val();
            if (type == 'aadhaar' && $("#aadhar_number").val().length == 12) {
                $("#backgroundimage").show();
                $(".container").hide();
                document = $('#aadhar_number').val();
            } else if (type == 'pan' && $("#pan-number").val().length == 10) {
                $("#backgroundimage").show();
                $(".container").hide();
                document = $('#pan-number').val();
            } else if (type == 'voter' && $("#voter_number").val().length <= 23  && $("#voter_number").val().length >= 10) {
                $("#backgroundimage").show();
                $(".container").hide();
                document = $('#voter_number').val();
            } 

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
                                } else if(type=='pan'){
                                    $("#pan_check_image").show();
                                    isPanCorrect = 1;
                                } else if(type=='voter'){
                                    $("#voter_check_image").show();
                                    isVoterCorrect = 1;
                                }
                                
                                if(isPanCorrect==1 && isAadhaarCorrect==1 && isVoterCorrect==1){
                                    $('.next-step').removeAttr("disabled");
                                }else{
                                    $('.next-step').prop("disabled", true);
                                }
                            }else{
                                alert(jsonData.message);
                            }
                        } else {
                            if(type=='aadhaar'){
                                alert('Aadhar is invalid.');
                                $("#aadhaar_check_image").hide();
                                isAadhaarCorrect = 0;
                            }
                            if(type=='pan'){
                                alert('Pan is invalid.');
                                $("#pan_check_image").hide();
                                isPanCorrect = 0;
                            }
                            if(type=='voter'){
                                alert('Voter is invalid.');
                                $("#voter_check_image").hide();
                                isVoterCorrect = 0;
                            }
                            $('.next-step').prop("disabled", true);

                        }
                        $("#backgroundimage").hide();
                        $(".container").show();
                        window.scrollTo(0, pageyscroll);
                    },
                    error: function (error) {
                        alert('Wrong information.');
                        $("#backgroundimage").hide();
                        $(".container").show();
                        console.log(error)
                    }
                });
            }
        }

        function verifyDocLocker(type) {
            //var document = type == 'aadhaar' ? $('#aadhar_number').val() : $('#pan-number').val();
            var dfname = $('#digilocker_fname').val();
            var dlname = $('#digilocker_lname').val();
            var dmobile = $('#digilocker_mobile').val();
            var demail = $('#digilocker_email').val();

            if(dfname!="" && dlname!="" && dmobile!="" && demail!=""){
                $("#backgroundimage").show();
                $(".container").hide();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{URL::to('/user/kyc/digilocker_verify')}}",
                    data: {
                        'dfname' : dfname,
                        'dlname' : dlname,
                        'dmobile' : dmobile,
                        'demail' : demail
                    },
                    success: function (response) {
                        //console.log(response.data);
                        var jsonData = JSON.parse(response.data);
                        //console.log(jsonData.model);
                        console.log(jsonData.model.url);
                        //window.location.href = jsonData.url;
                        var transactionId = jsonData.model.transactionId;
                        //alert(transactionId);
                        $('#digiLockerTransactionId').val(transactionId);
                        var popupUrl = jsonData.model.url;
                        openPopup(jsonData.model.url);
                        //window.open(jsonData.url);
                    },
                    error: function (error) {
                        $("#backgroundimage").hide();
                        $(".container").show();
                        console.log(error);
                        var popupUrl = 'http://www.example.com';
                        openPopup(popupUrl);
                    }
                });
            } else {
                alert('Please fill all fields!');
            }
        }

        function openPopup(popupUrl) {
            var left = (screen.width/2);
            var top = (screen.height/2);
            var popWidth = 800;
            var popHeight = 600;
            var popTop = top - popHeight/2;
            var popLeft = left - popWidth/2;
            
            win = window.open('https://'+popupUrl, 'liveMatches', 'location=yes,height=' + popHeight + ',width=' + popWidth + ',resizeable=0, top=' + popTop + ', left=' + popLeft);
            win.focus();
            
        }

        function polling(){
            if (win && win.closed) {
                clearInterval(timer);
                var transactionId = $('#digiLockerTransactionId').val();
                $("#backgroundimage").show();
                $(".container").hide();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{URL::to('/user/kyc/digilocker_verify_check')}}",
                    data: {
                        'transactionId' : transactionId
                    },
                    success: function (response) {
                        $("#backgroundimage").hide();
                        $(".container").show();
                        console.log(response.data);
                        var jsonData = JSON.parse(response.data);
                        $('.steps1').hide();
                        $('.steps2').show();
                        $('.upload-step').show();
                        //alert(jsonData.url);
                        //window.location.href = jsonData.url;
                        //window.open(jsonData.url);
                    },
                    error: function (error) {
                        $("#backgroundimage").hide();
                        $(".container").show();
                        console.log(error);
                        alert(error);
                        /*var popupUrl = 'http://www.example.com';
                        openPopup(popupUrl);*/
                    }
                });
            }
        }

        timer = setInterval('polling()',1);

        var validNavigation = false;
        function endSession() {
            // Browser or broswer tab is closed
            // Do sth here ...
            win.close();
            alert("Browser window closed");
        }

        function wireUpEvents() {
            /*
            * For a list of events that triggers onbeforeunload on IE
            * check http://msdn.microsoft.com/en-us/library/ms536907(VS.85).aspx
            */
            window.onbeforeunload = function() {
                if (!validNavigation) {
                    endSession();
                }
            }
            // Attach the event keypress to exclude the F5 refresh
            $('html').bind('keypress', function(e) {
                if (e.keyCode == 116){
                    validNavigation = true;
                }
            });
            // Attach the event click for all links in the page
            $("a").bind("click", function() {
                validNavigation = true;
            });
            // Attach the event submit for all forms in the page
            $("form").bind("submit", function() {
                validNavigation = true;
            });
            // Attach the event click for all inputs in the page
            $("input[type=submit]").bind("click", function() {
                validNavigation = true;
            });

        }
        // Wire up the events as soon as the DOM tree is ready
        $(document).ready(function() {
            wireUpEvents();
        });

        function verifyCurrentAddress() {
            var curAdd = $("#current-address").val(); 
        }

        function uploadStetmentApi(type) {//alert(type);
            //var document = type == 'stetment' ? $('#nid').val() : $('#passport').val();
            $("#backgroundimage").show(); $(".container").hide();
            var document = type = $("#urlGenerateType").val();
            var institutionStatement = $("#institutionStatement").val();
            var institutionNetbanking = $("#institutionNetbanking").val();
            if(document!=""){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{URL::to('/user/kyc/upload_statement')}}",
                    data: {
                        'document': document,
                        'institutionStatement': institutionStatement,
                        'institutionNetbanking': institutionNetbanking,
                        'type' : type
                    },
                    success: function (response) {
                        //console.log(response.data);
                        $("#backgroundimage").hide(); $(".container").show();
                        var jsonData = JSON.parse(response.data);
                        window.location.href = jsonData.url;
                        //window.open(jsonData.url);
                    },
                    error: function (error) {
                        $("#backgroundimage").hide(); $(".container").show();
                        console.log(error);
                        alert(error);
                    }
                });
            }
        }
    </script>
</body>

</html>

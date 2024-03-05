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
    <img id="backgroundimage" src="{{asset('assets/images/33CiUFaI1641808748.gif')}}" style="display: none;">
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
                        <form id="step_3_form" method="post" enctype="multipart/form-data">
                            <div class="input-block">
                                <h4>Current Address</h4>
                                    <div class="form-group">
                                        <input type="text" name="house_no" class="form-control" id="house_no" placeholder="House No. e.g. BM-74" required="true">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="street" class="form-control" id="street" placeholder="Street Sukhliya" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="landmark" class="form-control" id="landmark" placeholder="Landmark e.g. Near Bharat Mata Mandir" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="district" class="form-control" id="district" placeholder="District e.g. Indore" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="city" class="form-control" id="city" placeholder="City e.g. Indore" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="pincode" class="form-control" id="pincode" placeholder="Pincode e.g. 452010" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="state" class="form-control" id="state" placeholder="State e.g Madhya Pradesh" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" value="India" name="country" class="form-control" id="country" placeholder="Country e.g. India" required>
                                    </div>
                                    <input type="hidden" name="latitude" id="latitude" value="" required>
                                    <input type="hidden" name="longitude" id="longitude" value="" required>

                            </div>
                            <div class="input-block">
                                <h4>Upload Address Proof</h4>
                                <div class="file-input-wrap">
                                    <div class="custom-file">
                                        <input type="file" name="fId" class="custom-file-input" id="upload-input-1" accept="image/*" onchange="previewImage(event, 1)" >
                                        <label class="custom-file-label" for="upload-input-1"><img src="#" id="preview-1" alt="icon" class="img-fluid preview"><img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/cloud.png" class="upload-icon"></label>
                                        <span class="text">Address Image</span>
                                    </div>  
                                </div>
                            </div>
                            <div class="input-block">
                                <button type="button" class="btn current-address" onclick="verifyCurrentAddress('stetment');">Submit</button><br><br>
                                <!-- <button type="button" style="display: none;" data-form_name="step_1_form" disabled class="btn next-step">Next &gt;</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else { 
                alert("Geolocation is not supported by this browser.");
            }
        });
        function previewImage(event, index) {
            var input = event.target;
            var reader = new FileReader();
            var imgElement = document.getElementById('preview-' + index);

            reader.onload = function () {
              imgElement.src = reader.result;
              imgElement.classList.add('preview-img'); // Add common class
            };

            reader.readAsDataURL(input.files[0]);
        }

        function showPosition(position) {
            $('#latitude').val(position.coords.latitude);
            $('#longitude').val(position.coords.longitude);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{URL::to('/user/kyc/get_address_lat_long')}}",
                data: {'latitude':position.coords.latitude, 'longitude':position.coords.longitude},
                success: function(response){
                    var dataRes = JSON.parse(response);
                    if(dataRes.code == '200') {
                        /*var dataRes = {
                            "code": "200",
                            "model": {
                                "address": "179, Abhinandan Nagar Rd, DDU Nagar, Sukhlia, Indore, Madhya Pradesh 452010, India",
                                "pincode": "452010",
                                "district": "Indore Division",
                                "state": "Madhya Pradesh"
                            }
                        };*/
                        /*console.log(data);*/
                        //console.log(JSON.stringify(data));
                        //console.log(dataRes);
                        //console.log(dataRes.model.address);
                        var str = dataRes.model.address;
                        var myarray = str.split(', ');
                        var dist = dataRes.model.district;
                        var distArray = dist.split(' ');
                        $("#house_no").val(myarray[0]);
                        $("#street").val(myarray[1]);
                        //$("#landmark").val(myarray[2]);
                        $("#district").val(distArray[0]);
                        $("#city").val(myarray[4]);
                        $("#pincode").val(dataRes.model.pincode);
                        $("#state").val(dataRes.model.state);
                    } else {
                        alert('Current address not found.');
                        /*$("#backgroundimage").hide();
                        $(".container").show();*/
                        window.location.href = "{{URL::to('/user/dashboard?kyc=0')}}";
                        return false;
                    }
                }
            });
        }

        function verifyCurrentAddress() {
            var formData = new FormData();
            formData.append('fId', document.getElementById('upload-input-1').files[0]);
            formData.append('house_no',document.getElementById('house_no').value);
            formData.append('street',document.getElementById('street').value);
            formData.append('landmark',document.getElementById('landmark').value);
            formData.append('district',document.getElementById('district').value);
            formData.append('pincode',document.getElementById('pincode').value);
            formData.append('city',document.getElementById('city').value);
            formData.append('state',document.getElementById('state').value);
            formData.append('country',document.getElementById('country').value);
            formData.append('latitude',document.getElementById('latitude').value);
            formData.append('longitude',document.getElementById('longitude').value);

            if ($("#house_no").val() == "") {
                $("#house_no").focus();
                alert("Please insert house number.");
                return false;
            }
            if ($("#street").val() == "") {
                $("#street").focus();
                alert("Please insert street.");
                return false;
            }
            if ($("#landmark").val() == "") {
                $("#landmark").focus();
                alert("Please insert landmark.");
                return false;
            }
            if ($("#district").val() == "") {
                $("#district").focus();
                alert("Please insert district.");
                return false;
            }
            if ($("#city").val() == "") {
                $("#city").focus();
                alert("Please insert city.");
                return false;
            }
            if ($("#pincode").val() == "") {
                $("#pincode").focus();
                alert("Please insert pincode.");
                return false;
            }
            if ($("#city").val() == "") {
                $("#city").focus();
                alert("Please insert city.");
                return false;
            }
            if ($("#state").val() == "") {
                $("#state").focus();
                alert("Please insert state.");
                return false;
            }
            if ($("#country").val() == "") {
                $("#country").focus();
                alert("Please insert country.");
                return false;
            }
            if ($("#latitude").val() == "") {
                $("#latitude").focus();
                alert("latitude is not found.");
                return false;
            }
            if ($("#longitude").val() == "") {
                $("#longitude").focus();
                alert("longitude is not found.");
                return false;
            }
            if (document.getElementById('upload-input-1').files[0] == undefined) {
                $("#upload-input-1").focus();
                alert('Please upload Address Proof');
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
                url: "{{URL::to('/user/kyc/step2_document')}}",
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(data){
                    console.log(JSON.stringify(data));
                    if(data.status == 1) {
                        alert(data.message);
                        /*$("#backgroundimage").hide();
                        $(".container").show();*/
                        window.location.href = "{{URL::to('/user/dashboard?kyc=1')}}";
                    } else {
                        alert(data.error);
                        /*$("#backgroundimage").hide();
                        $(".container").show();*/
                        window.location.href = "{{URL::to('/user/dashboard?kyc=0')}}";
                        return false;
                    }
                }
            });
        }
    </script>
</body>
</html>

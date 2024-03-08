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
            right: 0;
            bottom: 0;
            opacity: 0.5;
            text-align: center;
            height: auto;
            left: 50%;
            padding: 0;
            position: fixed;
            top: 50%;
            z-index: 1;
        }
    </style>
    </head>
<body>
    <img id="backgroundimage" src="{{asset('assets/images/33CiUFaI1641808748.gif')}}" style="display: none;">
    <div class="ugf-bg ufg-main-container">
        <div class="ugf-progress">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 offset-lg-0 p-sm-0">
                    <div class="ugf-form">
                        <form id="step_3_form" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
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
                                    <!-- <div class="input-block">
                                        <button type="button" class="btn current-address" onclick="verifyCurrentAddress('stetment');">Submit</button><br><br>
                                    </div> -->
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block">
                                        <h4>Bussiness(Office)/Residential same as Current Address <input type="checkbox" value="" name="filltoo" id="filltoo" onclick="filladd()" /></h4>
                                            <div class="form-group">
                                                <input type="text" name="house_no1" class="form-control" id="house_no1" placeholder="House No. e.g. BM-74" required="true">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="street1" class="form-control" id="street1" placeholder="Street Sukhliya" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="landmark1" class="form-control" id="landmark1" placeholder="Landmark e.g. Near Bharat Mata Mandir" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="district1" class="form-control" id="district1" placeholder="District e.g. Indore" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="city1" class="form-control" id="city1" placeholder="City e.g. Indore" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="pincode1" class="form-control" id="pincode1" placeholder="Pincode e.g. 452010" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="state1" class="form-control" id="state1" placeholder="State e.g Madhya Pradesh" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" value="India" name="country1" class="form-control" id="country1" placeholder="Country e.g. India" required>
                                            </div>
                                            <input type="hidden" name="latitude" id="latitude" value="" required>
                                            <input type="hidden" name="longitude" id="longitude" value="" required>

                                    </div>
                                    <!-- <div class="input-block">
                                        <h4>Upload Address Proof</h4>
                                        <div class="file-input-wrap">
                                            <div class="custom-file">
                                                <input type="file" name="fId" class="custom-file-input" id="upload-input-1" accept="image/*" onchange="previewImage(event, 1)" >
                                                <label class="custom-file-label" for="upload-input-1"><img src="#" id="preview-1" alt="icon" class="img-fluid preview"><img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/cloud.png" class="upload-icon"></label>
                                                <span class="text">Address Image</span>
                                            </div>  
                                        </div>
                                    </div> -->
                                    <div class="input-block">
                                        <button type="button" class="btn current-address" onclick="verifyCurrentAddress('stetment');">Submit</button><br><br>
                                    </div>
                                </div>
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
            $("#backgroundimage").show();
            $(".container").hide();
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
              imgElement.classList.add('preview-img');
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
                        $("#backgroundimage").hide();
                        $(".container").show();
                    } else {
                        alert('Current address not found.');
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

            if ($("#house_no1").val() == "") {
                $("#house_no1").focus();
                alert("Please insert house number.");
                return false;
            }
            if ($("#street1").val() == "") {
                $("#street1").focus();
                alert("Please insert street.");
                return false;
            }
            if ($("#landmark1").val() == "") {
                $("#landmark1").focus();
                alert("Please insert landmark.");
                return false;
            }
            if ($("#district1").val() == "") {
                $("#district1").focus();
                alert("Please insert district.");
                return false;
            }
            if ($("#city1").val() == "") {
                $("#city1").focus();
                alert("Please insert city.");
                return false;
            }
            if ($("#pincode1").val() == "") {
                $("#pincode1").focus();
                alert("Please insert pincode.");
                return false;
            }
            if ($("#city1").val() == "") {
                $("#city1").focus();
                alert("Please insert city.");
                return false;
            }
            if ($("#state1").val() == "") {
                $("#state1").focus();
                alert("Please insert state.");
                return false;
            }
            if ($("#country1").val() == "") {
                $("#country1").focus();
                alert("Please insert country.");
                return false;
            }

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
                        $("#backgroundimage").hide();
                        $(".container").show();
                        window.location.href = "{{URL::to('/user/dashboard?kyc=1')}}";
                    } else {
                        alert(data.message);
                        $("#backgroundimage").hide(); $(".container").show();
                        window.location.href = "{{URL::to('/user/dashboard?kyc=0')}}";
                        return false;
                    }
                }
            });
        }

        function filladd()
        {
             if(filltoo.checked == true) 
             {
                var house_no =document.getElementById("house_no").value;
                var street =document.getElementById("street").value;
                var landmark =document.getElementById("landmark").value;
                var district =document.getElementById("district").value;
                var city =document.getElementById("city").value;
                var pincode =document.getElementById("pincode").value;
                var state =document.getElementById("state").value;
                var country =document.getElementById("country").value;

                
                document.getElementById("house_no1").value = house_no;
                document.getElementById("street1").value = street;
                document.getElementById("landmark1").value = landmark;
                document.getElementById("district1").value = district;
                document.getElementById("city1").value = city;
                document.getElementById("pincode1").value = pincode;
                document.getElementById("state1").value = state;
                document.getElementById("country1").value = country;
             }
             else if(filltoo.checked == false)
             {
                document.getElementById("house_no1").value='';
                document.getElementById("street1").value='';
                document.getElementById("landmark1").value='';
                document.getElementById("district1").value = '';
                document.getElementById("city1").value = '';
                document.getElementById("pincode1").value = '';
                document.getElementById("state1").value = '';
                document.getElementById("country1").value = '';
             }
        }
    </script>
</body>
</html>
<a id="register-partner" href="#register-partner-modal" style="display: none;">Partner Modal</a>
<script> var countries = <?php echo json_encode($countries); ?></script> <!-- Variable is used in dial_code.js -->
<div id="register-partner-modal" class="modal-container">
    <!--"THIS IS IMPORTANT! to close the modal, the class name has to match the name given on the ID-->
    <div  id="btn-close-modal" class="close-register-partner-modal btn-close-modal"> 
        X
    </div>

    <div class="custom-modal-title">Register Partner</div>
    
    <div class="container" id="partner-content">
       <form id="register_partner_form" method="post"  style="margin-top: 50px;">
            {{ csrf_field() }}

            <div class="container-fluid">
                <div class="row">
                    <div class="col vertical-divider">
                        <div class="row pad-row">
                            <div class="form-group col">
                                <label for='title'>Title</label>
                                <select class="form-control" id="titleIdentifier" name="titleIdentifier">
                                    
                                    @foreach($titles as $title)
                                        <option selected value="{{$title['titleIdentifier']}}">{{$title['titleName']}}</option>
                                    @endforeach

                                </select>

                            </div>

                            <div class="form-group col" style="margin-left: 20px;">
                                <label for='fullname'>Full Name</label>
                                <input type="text" name="fullname" id="fullname" class="form-control">
                            </div>
                        </div>


                        <div class="row pad-row">
                            <div class="form-group col">
                                <label for='gender'>Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    @foreach(config('constants.genders') as $gender)
                                        <option value="{{$gender}}">{{ucfirst($gender)}}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                                    
                            <div class="form-group col" style="margin-left: 20px;">
                                <label for='birthDate'>Date of Birth</label>
                                <input class="form-control" type="date" name="birthDate" id="birthDate">
                                
                            </div>

                        </div>

                        <div class="row pad-row">
                            <div class="form-group col">
                                <label for='countryOfResidence'>Resident Country</label>
                                <select class="form-control" id="countryOfResidence" name="countryOfResidence">
                                    <option value="" selected>Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country['countryIdentifier']}}">{{$country['countryName']}}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group col">
                                <label for='countryOfBirth'>Birth Country</label>
                                <select class="form-control" id="countryOfBirth" name="countryOfBirth">
                                    <option selected value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country['countryIdentifier']}}">{{$country['countryName']}}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group col">
                                <label for='stateIdentifier'>State</label>
                                <select class="form-control" id="stateIdentifier" name="stateIdentifier">
                                    
                                    @foreach($states as $state)
                                        <option value="{{$state['stateIdentifier']}}">{{$state['stateName']}}</option>
                                    @endforeach

                                </select>
                            </div>


                        </div>


                        <div class="row pad-row">
                            <div class="form-group col">
                                <label for='emailAddress'>Email</label>
                                <input type="email" name="emailAddress" id="emailAddress" class="form-control">
                                
                            </div>

                            <div class="form-group col" style="margin-left: 20px";>
                                <label for='secondaryEmailAddress'>Alternative Email</label>
                                <input type="email" name="secondaryEmailAddress" id="secondaryEmailAddress" class="form-control">
                            </div>
                        </div>

                        <div class="row pad-row">
                            <div class="form-group col">
                                <label for='phoneNumber'>Phone</label>
                                <div class="input-group">
                                    
                                    <span id='primaryPhoneDialCode' class="input-group-prepend input-group-text"></span><input type="tel" name="phoneNumber" id="phoneNumber" class="form-control">
                                    
                                </div>
                            </div>

                            <div class="form-group col" style="margin-left: 20px">
                                <label for='secondaryPhoneNumber'>Alternative Phone <span class="same-dial-code">Same dial code <input id="same-dial-code" type="checkbox"></span></label>
                                <div class="input-group">
                                    
                                    <input type="tel" name="secondaryPhoneNumber" id="secondaryPhoneNumber" class="form-control">
                                    
                                </div>
                            </div>
                        </div>


                        <div class="row pad-row">
                            <div class="form-group col">
                                <label for="residentialAddress">Residential Address</label>
                                <textarea class="form-control" name="residentialAddress" id="residentialAddress"></textarea>
                            </div>

                            <div class="form-group col">
                                <label for="postalAddress">Postal Address</label>
                                <textarea class="form-control" name="postalAddress" id="postalAddress"></textarea>
                            </div>
                        </div>
                    
                    </div>

                     <div class="col">
                         
                         <div class="row pad-row">
                            <div class="form-group col">
                                <label for='maritalStatus'>Marital Status</label>
                                <select class="form-control" id="maritalStatus" name="maritalStatus">
                                    @foreach(config('constants.marital_status') as $maritalStatus)
                                        <option value="{{$maritalStatus}}">{{ucfirst($maritalStatus)}}</option>
                                    @endforeach
                                </select>
                                
                            </div>

                            <div class="form-group col" style="margin-left: 20px";>
                                <label for='job'>Occupation</label>
                                <input type="text" name="job" id="job" class="form-control">
                            </div>
                        </div>

                        <div class="row pad-row">
                            <div class="form-group col">
                                <label for='preferredLanguage'>Preferred Language</label>
                                <select class="form-control" id="preferredLanguage" name="preferredLanguage">
                                    @foreach(config('constants.preferred_languages') as $language)
                                        <option value="{{$language}}">{{ucfirst($language)}}</option>
                                    @endforeach
                                </select>
                                
                            </div>

                            <div class="form-group col" style="margin-left: 20px";>
                                <label for='currencyIdentifier'>Currency</label>
                                <select class="form-control" id="currencyIdentifier" name="currencyIdentifier">

                                    @foreach($currencies as $currency)
                                        <option value="{{$currency['currencyIdentifier']}}">{{ucfirst($currency['currencyName'])}}</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-6">
                                <label for='donationAmount'>Pledge</label>
                                <input type="number" name="donationAmount" id="donationAmount" class="form-control" min="">
                            </div>

                            <!--<div class="form-group col">
                                <label for="userPhoto">Profile Picture</label>
                                <div class="dropzone" id="userPhoto">

                                    <div class="dz-message needsclick">   
                                        Drop file here or click to upload.<BR>
                                    </div>
                                    
                                </div>
                            </div>
                            -->
                        </div>

                        <div class="row">
                            <div class="form-group col">
                                <button name="register_btn" id="register_btn" class="form-control btn btn-success btn-lg btn-block"> Save </button>
                            </div>
                        </div>

                     </div>

                </div>

               
            </div>
           
       </form>
    </div>

</div>

<script>

    $("#register-partner").animatedModal({
        animatedIn:'lightSpeedIn',
        animatedOut:'bounceOutDown',
        color:'#3498db',
    });

    $('#btn-close-modal').on('click', function(){
        $('#register-partner-section').html('');
    });


    Dropzone.autoDiscover = false;

    myDropzone = new Dropzone('div#userPhoto', {
    addRemoveLinks: true,
    autoProcessQueue: false,
    uploadMultiple: true,
    maxFiles: 1,
    paramName: 'file',
    clickable: true,
    url: '/register-partner',
    init: function () {

        var myDropzone = this;
        // Update selector to match your button
        $btn.click(function (e) {
            e.preventDefault();
            if ( $form.valid() ) {
                myDropzone.processQueue();
            }
            return false;
        });

        this.on('sending', function (file, xhr, formData) {
            // Append all form inputs to the formData Dropzone will POST
            var data = $form.serializeArray();
            $.each(data, function (key, el) {
                formData.append(el.name, el.value);
            });
            //console.log(formData);

        });
    },
    error: function (file, response){
        if ($.type(response) === "string")
            var message = response; //dropzone sends it's own error messages in string
        else
            var message = response.message;
        file.previewElement.classList.add("dz-error");
        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            _results.push(node.textContent = message);
        }
        return _results;
    },
    successmultiple: function (file, response) {
        console.log(file, response);
        $modal.modal("show");
    },
    completemultiple: function (file, response) {
        console.log(file, response, "completemultiple");
        //$modal.modal("show");
    },
    reset: function () {
        console.log("resetFiles");
        this.removeAllFiles(true);
    }
});

</script>
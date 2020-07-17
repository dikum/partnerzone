<a id="show-partner" href="#show-partner-modal" style="display: none;">Partner Modal</a>

<script> var countries = <?php echo json_encode($countries); ?></script>
@php $birth_country_dial_code = ''; @endphp

<div id="show-partner-modal">
    <!--"THIS IS IMPORTANT! to close the modal, the class name has to match the name given on the ID-->
    <div  id="btn-close-modal" class="close-show-partner-modal"> 
        X
    </div>
    
    <div class="container" id="partner-content">
       <form id="update_partner_form" method="post">
            {{ csrf_field() }}

            <div class="form-group">
               <div>
                    <input id="partnerIdentifier" name="partnerIdentifier" type="text" readonly="true" value= "{{$partner['partnerIdentifier']}}" >

                    @php $active_class = '';
                        if($partner['userStatus'] == 'active')
                            $active_class = 'active';
                        else if($partner['userStatus'] == 'inactive')
                            $active_class = 'inactive';
                        else
                            $active_class = 'suspended';
                    @endphp

                    <span class="{{$active_class}}">{{$partner['userStatus']}}</span>
                    
                </div>

            </div>

            <input type="hidden" name="userIdentifier" id="userIdentifier" value="{{$partner['userIdentifier']}}">


            <div class="container-fluid">
                <div class="row">
                    <div class="col veritcal-divider">
                        <div class="row">
                            <div class="form-group col">
                                <label for='title'>Title</label>
                                <select class="form-control" id="titleIdentifier" name="titleIdentifier">
                                    
                                    @foreach($titles as $title)
                                        @if($partner['titleIdentifier'] != $title['titleIdentifier'])
                                            <option value="{{$title['titleIdentifier']}}">{{$title['titleName']}}</option>
                                        @else
                                            <option selected value="{{$title['titleIdentifier']}}">{{$title['titleName']}}</option>
                                        @endif
                                    @endforeach

                                </select>

                            </div>

                            <div class="form-group col" style="margin-left: 20px;">
                                <label for='fullname'>Full Name</label>
                                <input type="text" name="fullname" id="fullname" class="form-control" value="{{$partner['fullname']}}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col">
                                <label for='gender'>Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    @foreach(config('constants.genders') as $gender)
                                        @if($partner['gender'] == $gender)
                                            <option selected value="{{$partner['gender']}}">{{ucfirst($partner['gender'])}}</option>
                                        @else
                                            <option value="{{$gender}}">{{ucfirst($gender)}}</option>
                                        @endif
                                        
                                    @endforeach
                                </select>
                                
                            </div>
                        
                                    
                                    @php
                                        $day = substr($partner['birthDate'], 8);
                                        $month = substr($partner['birthDate'], 5,2);
                                        $year = substr($partner['birthDate'], 0,4);
                                    @endphp

                                    
                            <div class="form-group col" style="margin-left: 20px;">
                                <label for='birthDate'>Date of Birth</label>
                                <input class="form-control" type="date" name="birthDate" id="birthDate" value="{{$year}}-{{$month}}-{{$day}}">
                                
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col">
                                <label for='countryOfResidence'>Resident Country</label>
                                <select class="form-control" id="countryOfResidence" name="countryOfResidence">
                                    
                                    @foreach($countries as $country)
                                        @if($partner['countryOfResidence'] == $country['countryIdentifier'])
                                            <option selected value="{{$country['countryIdentifier']}}">{{$country['countryName']}}</option>
                                            @php $birth_country_dial_code = $country['countryDialingCode']; @endphp
                                        @else
                                            <option value="{{$country['countryIdentifier']}}">{{$country['countryName']}}</option>
                                        @endif
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group col">
                                <label for='countryOfBirth'>Birth Country</label>
                                <select class="form-control" id="countryOfBirth" name="countryOfBirth">
                                    @foreach($countries as $country)
                                        @if($partner['countryOfBirth'] == $country['countryIdentifier'])
                                            <option selected value="{{$country['countryIdentifier']}}">{{$country['countryName']}}</option>
                                            @php
                                                $is_nigeria = false;
                                                if($country['countryName'] == 'Nigeria')
                                                    $is_nigeria = true;
                                            @endphp
                                        @else
                                            <option value="{{$country['countryIdentifier']}}">{{$country['countryName']}}</option>
                                        @endif
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group col">
                                <label for='stateIdentifier'>State</label>
                                <select class="form-control" id="stateIdentifier" name="stateIdentifier" disabled="{{$is_nigeria}}">
                                    
                                    @foreach($states as $state)
                                        @if($partner['stateIdentifier'] == $state['stateIdentifier'])
                                            <option selected value="{{$state['stateIdentifier']}}">{{$state['stateName']}}</option>
                                        @else
                                            <option value="{{$state['stateIdentifier']}}">{{$state['stateName']}}</option>
                                        @endif
                                    @endforeach

                                </select>
                            </div>


                        </div>


                        <div class="row">
                            <div class="form-group col">
                                <label for='emailAddress'>Email</label>
                                <input type="email" name="emailAddress" id="emailAddress" class="form-control" value="{{$partner['emailAddress']}}">
                                
                            </div>

                            <div class="form-group col" style="margin-left: 20px";>
                                <label for='secondaryEmailAddress'>Alternative Email</label>
                                <input type="email" name="secondaryEmailAddress" id="secondaryEmailAddress" class="form-control" value="{{$partner['secondaryEmailAddress']}}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col">
                                <label for='phoneNumber'>Phone</label>
                                <div class="input-group">
                                    
                                    <span id='primaryPhoneDialCode' class="input-group-prepend input-group-text">{{$birth_country_dial_code}}</span><input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="{{$partner['phoneNumber']}}">
                                    
                                </div>
                            </div>

                            <div class="form-group col" style="margin-left: 20px">
                                <label for='secondaryPhoneNumber'>Alternative Phone <span class="same-dial-code">Same dial code <input id="same-dial-code" type="checkbox"></span></label>
                                <div class="input-group">
                                    
                                    <input type="text" name="secondaryPhoneNumber" id="secondaryPhoneNumber" class="form-control" value="{{$partner['secondaryPhoneNumber']}}">
                                    
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col">
                                <label for="residentialAddress">Residential Address</label>
                                <textarea class="form-control" name="residentialAddress" id="residentialAddress">{{$partner['residentialAddress']}}</textarea>
                            </div>

                            <div class="form-group col">
                                <label for="postalAddress">Postal Address</label>
                                <textarea class="form-control" name="postalAddress" id="postalAddress">{{$partner['postalAddress']}}</textarea>
                            </div>
                        </div>
                    
                    </div>

                     <div class="col">
                         
                         <div class="row">
                            <div class="form-group col">
                                <label for='maritalStatus'>Marital Status</label>
                                <select class="form-control" id="maritalStatus" name="maritalStatus">
                                    @foreach(config('constants.marital_status') as $maritalStatus)
                                        @if($partner['maritalStatus'] == $maritalStatus)
                                            <option selected value="{{$partner['maritalStatus']}}">{{ucfirst($partner['maritalStatus'])}}</option>
                                            @else
                                                <option value="{{$maritalStatus}}">{{ucfirst($maritalStatus)}}</option>
                                            @endif
                                    @endforeach
                                </select>
                                
                            </div>

                            <div class="form-group col" style="margin-left: 20px";>
                                <label for='job'>Occupation</label>
                                <input type="text" name="job" id="job" class="form-control" value="{{$partner['job']}}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col">
                                <label for='preferredLanguage'>Preferred Language</label>
                                <select class="form-control" id="preferredLanguage" name="preferredLanguage">
                                    @foreach(config('constants.preferred_languages') as $language)
                                        @if($partner['preferredLanguage'] == $language)
                                            <option selected value="{{$partner['preferredLanguage']}}">{{ucfirst($partner['preferredLanguage'])}}</option>
                                            @else
                                                <option value="{{$language}}">{{ucfirst($language)}}</option>
                                            @endif
                                    @endforeach
                                </select>
                                
                            </div>

                            <div class="form-group col" style="margin-left: 20px";>
                                <label for='currencyIdentifier'>Currency</label>
                                <select class="form-control" id="currencyIdentifier" name="currencyIdentifier">

                                    @foreach($currencies as $currency)
                                        @if($partner['currencyIdentifier'] == $currency['currencyIdentifier'])
                                            <option selected value="{{$partner['currencyIdentifier']}}">{{ucfirst($currency['currencyName'])}}</option>
                                        @else
                                            <option value="{{$currency['currencyIdentifier']}}">{{ucfirst($currency['currencyName'])}}</option>
                                        @endif
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col">
                                <label for='donationAmount'>Pledge</label>
                                <input type="number" name="donationAmount" id="donationAmount" class="form-control" min="" value="{{$partner['donationAmount']}}">
                            </div>

                            <div class="form-group col" style="margin-left: 20px";>
                                <label for='isVerified'>Verification Status</label>
                                <select class="form-control" id="isVerified" name="isVerified">

                                    @if($partner['isVerified'] == '1')

                                        <option selected value="{{$partner['isVerified']}}">Verified</option>
                                        <option value="0">Unverified</option>

                                    @else

                                        <option  value="1">Verified</option>
                                        <option selected value="{{$partner['isVerified']}}">Unverified</option>

                                    @endif
                                        
                                    
                                
                                </select>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-6">
                                <label for='userBranch'>Branch</label>
                                <select class="form-control" id="userBranch" name="userBranch">

                                    @foreach(config('constants.branches') as $branch)
                                        @if($partner['userBranch'] == $branch)
                                            <option selected value="{{$partner['userBranch']}}">{{ucfirst($partner['userBranch'])}}</option>
                                            @else
                                                <option value="{{$branch}}">{{ucfirst($branch)}}</option>
                                            @endif
                                    @endforeach
                                
                                </select>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col">
                                <button name="update_btn" id="update_btn" class="form-control btn btn-success btn-lg btn-block"> Update </button>
                            </div>
                        </div>

                     </div>

                </div>

               
            </div>
           
       </form>
    </div>


    <div class="bottom text-center">Registered by: <span class="footer-content">Dikum Aduwu</span> | Created Date:  <span class="footer-content">{{$partner['createdDate']}}</span></div>

</div>

<script>

    $("#show-partner").animatedModal({
        animatedIn:'lightSpeedIn',
        animatedOut:'bounceOutDown',
        color:'#3498db',
        // Callbacks
        beforeOpen: function() {
            console.log("About opening");
        },           
        afterOpen: function() {
            console.log("Opened successfully");
        }, 
        beforeClose: function() {
            console.log("Before Close");
        }, 
        afterClose: function() {
            console.log("Closed");
        }
    });

</script>
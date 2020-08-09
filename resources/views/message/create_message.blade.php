<section>
	<div style="text-align:center;" id='create-message'></div>
	<div class='container' style="padding-top:50px;">
		<nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark' id='searchPartnerMessageNav'>

			<div class="row">
				<div class="col-6">
					<div class='collapse navbar-collapse' id='createMessage'>
						
						<form class='mt-2 mt-md-0' id="search_message_partner_form">
							{{ csrf_field() }}

							<div id="message_criteria_div">

								<div class="row">
									<div class="form-group">
										<select name="message_search_criteria_select_1" id="message_search_criteria_select_1" class="form-control mr-sm-2 message_select_criteria">
											<option value="partnerIdentifier">Partner ID</option>
											<option value="fullname">Name</option>
											<option value="emailAddress">Email</option>
											<option value="phoneNumber">Phone</option>
											<option value="job">Occupation</option>
											<option value="countryOfResidence">Country of Residence</option>
											<option value="stateIdentifier">State</option>
											<option value="userStatus">Status</option>
										</select>
									</div>
								
									<div class="form-group" style="margin-left: 5px;" id="message_search_text_div_1"> <input id="message_search_text_1" name='message_search_text_1' class='form-control mr-sm-2' type="text" placeholder="Search" aria-label='Search'> </div>
								
								</div>

							</div>

							<div class='row' id='add_search_criteria_div'>
								<div class="form-group">
									<div class="message_add_search_criteria" id="message_add_search_criteria" style="color:#ECF8F8; cursor:pointer; margin-left: 5px; margin-bottom: 5px;">Search Criteria <i class='fa fa-plus' aria-hidden='true'></i></div>
								</div>
							</div>


							<div class="row">
								<div class="form-group">
										<div class="form-group"><button id='partner-search-btn' name='partner-search-btn' class="btn btn-outline-success my-2 my-sm-0"> Search </button></div>
								</div>
							</div>

							<input type="" name="from_send_message" value="This post is from the create message form" hidden>

						</form>

					</div>
				</div>
				<div class="form-group col-3">
					<select class="form-control" id="message-placeholder" name="message-placeholder">
						<option value="">Placeholder</option>
						<option value="[PARTNERID]">Partner ID</option>
						<option value="[NAME]">Name</option>
						<option value="[EMAIL]">Email</option>
						<option value="[PHONE]">Phone</option>
						<option value="[GENDER]">Gender</option>
						<option value="[DATEOFBIRTH]">Date Of Birth</option>
						<option value="[MARITALSTATUS]">Marital Status</option>
						<option value="[OCCUPTATION]">Occupation</option>
						<option value="[BIRTHCOUNTRY]">Birth Country</option>
						<option value="[RESIDENTIALCOUNTRY]">Residential Country</option>
						<option value="[RESIDENTIALADDRESS]">Residential Address</option>
						<option value="[POSTALADDRESS]">Postal Address</option>
					</select>
					<i style="cursor:pointer; color: #ECF8F8;" class='fa fa-plus' aria-hidden='true' id="add-message-placeholder"></i>
				</div>

				<div class="form-group col-3">
					<select class="form-control" id="message-template" name="message-template">
						<option value="">Select Template</option>
						@foreach($templates as $template)
							<option value="{{$template['messageTemplateIdentifier']}}">{{$template['messageTitle']}}</option>
						@endforeach
					</select>
					<i style="cursor:pointer; color: #ECF8F8;" class='fa fa-plus' aria-hidden='true' id="add-message-template"></i>
				</div>
			</div>

		</nav>
		<form id="send-message-form">
			{{ csrf_field() }}
		<div class="row">

			<div class="form-group col-3">
				<label for="subject">Subject</label>
				<input type="text" name="subject" id="subject" class="form-control">
				
			</div>

			<div class="form-group col-3">
				<label for="sender">Sender</label>
				<input type="text" name="sender" id="sender" class="form-control">

			</div>

			<div class="form-groupc col-1">
				<label for="send_as_email"> Email </label>
				<input type="checkbox" name="send_as_email" id="send_as_email" class="form-control" checked="">
			</div>

			<div class="form-group col-1">
				<label for="send_as_sms"> SMS </label>
				<input type="checkbox" name="send_as_sms" id="send_as_sms" class="form-control" checked="">
			</div>

			
		</div>

		<div class="row">
			<div class="col-6">
				<label for="send_list">List</label>
				<textarea placeholder="Comma separated list of emails and phone numbers"class='form-control' id="send_list" name="send_list"></textarea>

			</div>
		</div>

		<div class="row">
            <div class="form-group col-12">
                <label for='messageEditor'>Message</label>
                <textarea  name="messageEditor" id="messageEditor" class="form-control"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-6">
                <button name="send-message-btn" id="send-message-btn" class="form-control btn btn-success btn-lg btn-block"> Send </button>
            </div>
		</div>

	</form>
	<div id='partner-list'></div>
</section>

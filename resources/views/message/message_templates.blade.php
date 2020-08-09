<section>
	<div style="text-align:center;" id='template-message'></div>
	<div class='container' style="padding-top:50px;">

		<div id='messageTemplateDiv' class="row">
			<!--<button id="show-create-message_template" class="float-left btn btn-default">Create New</button>-->
	
				<div class="col vertical-divider">
					<table class='cell-border hover order-column stripe' id='messageTemplateTable' style="width: 100%">
						<thead>
							<tr>
								<th>SN</th>
								<th>Title</th>
								<th>Action</th>
							</tr>
						</thead>

							@php $row_number = 1; @endphp
							<tbody>
								@foreach($templates as $template)
								
									<tr>
										<td>{{$row_number}}</td>
										<td>{{$template['messageTitle']}}</td>

										<div id="{{$row_number}}" style="display: none">{{$template['messageTemplate']}}</div>
										<div id="templateId{{$row_number}}" style="display: none">{{$template['messageTemplateIdentifier']}}</div>
										
										<td>

											@if(isLoggedInUserAdmin())<a href='#' onclick="deleteMessageTemplate('<?php echo $template['messageTemplateIdentifier']; ?>', '<?php echo $template['messageTitle'] ?>', '<?php echo $row_number ?>')" > <i class='fa fa-trash fa-2x' style='color:#9D0208'></i></a>@endif

										</td>
									</tr>
									@php $row_number++; @endphp
								@endforeach
						</tbody>
					</table>
				</div>

				<div class="col">
					<div class="form-group">
						<label for="placeholder"> Placeholder </label>
						<select class="form-control" id="placeholder" name="placeholder">
							<option value="[PARTNERID]">Partner ID</option>
							<option value="[TITLE]">Title</option>
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
						<i style="cursor:pointer;" class='fa fa-plus' aria-hidden='true' id="add-placeholder"></i>
					</div>
					<form id="create_message_template_form" method="post">
	            		{{ csrf_field() }}

	            		<input id="messageTemplateIdentifier" name="messageTemplateIdentifier" type="hidden" value="">
			            <div class="row">
			                <div class="form-group col-12">
			                    <label for='messageTitle'>Title</label>
			                    <input type="" name="messageTitle" id="messageTitle" placeholder="Message Title" class="form-control">
			                </div>
			            </div>


			            <div class="row">
			                <div class="form-group col-12">
			                    <label for='messageTemplate'>Message</label>
			                    <textarea name="messageTemplate" id="messageTemplate" class="form-control"></textarea>
			                </div>
			            </div>

			            <div class="row">
			                <div class="form-group col-12">
			                    <button name="create_message_template_btn" id="create_message_template_btn" class="form-control btn btn-success btn-lg btn-block"> Save </button>
			                </div>
			            </div>
	           
	       			</form>
				</div>

				
		</div>
	</div>
</section>







<section>
	<div style="text-align:center;" id='partner-message'></div>
	<div class='container' style="padding-top:50px;">
		<div id="search-partner-toggler"><i class='search-toggler fa fa-plus' aria-hidden='true' id="toggler-icon"></i></div>
		<nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark' style="display: none;" id='searchPartnerNav'>

			<div class='collapse navbar-collapse' id='partnerSearch'>
				
				<form class='mt-2 mt-md-0' id="search_partner_form">
					{{ csrf_field() }}
					<div id="criteria_div">

						<div class="row">
							<div class="form-group">
								<select name="search_criteria_select_1" id="search_criteria_select_1" class="form-control mr-sm-2 select_criteria">
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
						
							<div class="form-group" style="margin-left: 5px;" id="search_text_div_1"> <input id="search_text_1" name='search_text_1' class='form-control mr-sm-2' type="text" placeholder="Search" aria-label='Search'> </div>
						
						</div>

					</div>

					<div class='row' id='add_search_criteria_div'>
						<div class="form-group">
							<div class="add_search_criteria" id="add_search_criteria" style="color:#ECF8F8; cursor:pointer; margin-left: 5px; margin-bottom: 5px;">Search Criteria <i class='fa fa-plus' aria-hidden='true'></i></div>
						</div>
					</div>


					<div class="row">
						<div class="form-group">
								<div class="form-group"><button id='search' name='search' class="btn btn-outline-success my-2 my-sm-0"> Search </button></div>
						</div>
					</div>

				</form>

			</div>
			<button id="show-partner-registration" class="float-right btn btn-default">Add Partner</button>

		</nav>

		<div id='partners'>
			<table class='table table-hover table-striped table-sm table-responsive' id='partnerTable'>
				<caption>List of Partners</caption>
				<thead class="thead-dark">
					<tr> 
						<th>ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>

					@php $row_number = 1; @endphp
					@foreach($partners as $partner)

						<tr>
							<td>{{$partner['partnerIdentifier']}}</td>
							<td>{{$partner['fullname']}}</td>
							<td>{{$partner['emailAddress']}}</td>
							<td>{{$partner['phoneNumber']}}</td>
							<td>

								<a href='#' onclick="showPartner('<?php echo $partner['userIdentifier']; ?>')" > <i class='fa fa-edit fa-2x' style='color:#0077B6; margin-right: 10px;'></i></a>
								<a href='#' onclick="showPartnerPayments('<?php echo $partner['userIdentifier']; ?>', '<?php echo $partner['fullname']; ?>')" > <i class='fa fa-money fa-2x' style='color:#52B788; margin-right: 10px;'></i></a>
								@if(isLoggedInUserAdmin())<a href='#' onclick="deletePartner('<?php echo $partner['userIdentifier']; ?>', '<?php echo $partner['fullname'] ?>')" > <i class='fa fa-trash fa-2x' style='color:#9D0208'></i></a>@endif

							</td>
						</tr>
						@php $row_number++; @endphp
					@endforeach

				</tbody>
			</table>
		</div>
	</div>
</section>

<section id="show-partner-section"></section>
<section id="partner-payments-section"></section>
<section id="register-payments-section"></section>




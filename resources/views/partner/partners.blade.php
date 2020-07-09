<section>
	<div style="text-align:center;" id='message'></div>
	<div class='container' style="padding-top:50px;">
		<nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark'>

			<div class='collapse navbar-collapse' id='partnerSearch'>
				
				<form class='mt-2 mt-md-0' id="search_form">
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
			<a href='#'><button class="float-right btn btn-default">Add Partner</button></a>

		</nav>

		<div id='partners'>
			<table class='table table-hover table table-striped table-bordered table-sm' id='partnerTable'>
				<tbody>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Action</th>
					</tr>

					@foreach($partners as $partner)

						<tr>
							<td>{{$partner['partnerIdentifier']}}</td>
							<td>{{$partner['fullname']}}</td>
							<td>{{$partner['emailAddress']}}</td>
							<td>{{$partner['phoneNumber']}}</td>
							<td>

								<a href='#'> <i class='fa fa-edit fa-2x' style='color:#0077B6; margin-right: 10px;'></i></a>
								<a href='#'> <i class='fa fa-trash fa-2x' style='color:#9D0208'></i></a>

							</td>
						</tr>
					@endforeach

				</tbody>
			</table>
		</div>
	</div>
</section>

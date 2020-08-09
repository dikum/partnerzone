<div id='partners'>

	<table class='table table-hover table table-striped table-bordered table-sm' id='messagePartnerTable'>
		<thead class="thead-dark">
			<tr>
				<th>ID</th>
				<th>Status</th>
				<th>Name</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Gender</th>
				<th>Date Of Birth</th>
				<th>Marital Status</th>
				<th>Occupation</th>
				<th>Birth Country</th>
				<th>Resident Country</th>
				<th>Residential Address</th>
				<th>Postal Address</th>
				
				
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			

			@php $row_number = 1; @endphp
			@foreach($partners as $partner)

				<tr>
					<td>{{$partner['partnerIdentifier']}}</td>
					<td>{{$partner['userStatus']}}</td>
					<td>{{$partner['fullname']}}</td>
					<td>{{$partner['emailAddress']}}</td>
					<td>{{$partner['phoneNumber']}}</td>
					<td>{{$partner['gender']}}</td>
					<td>{{$partner['birthDate']}}</td>
					<td>{{$partner['maritalStatus']}}</td>
					<td>{{$partner['job']}}</td>
					<td>{{getCountryNameFromCollection($countries, $partner['countryOfBirth'])}}</td>
					<td>{{getCountryNameFromCollection($countries, $partner['countryOfResidence'])}}</td>
					<td>{{$partner['residentialAddress']}}</td>
					<td>{{$partner['postalAddress']}}</td>
					

					<td>
						<i id='remove_message_partner_icon' class='fa fa-minus fa-2x' style='color:#9D0208'></i>
					</td>
					
				</tr>
				@php $row_number++; @endphp
			@endforeach

		</tbody>
	</table>
</div>
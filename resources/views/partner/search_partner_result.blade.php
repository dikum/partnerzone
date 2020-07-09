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
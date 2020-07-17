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

					@php $row_number = 1; @endphp
					@foreach($partners as $partner)

						<tr>
							<td>{{$partner['partnerIdentifier']}}</td>
							<td>{{$partner['fullname']}}</td>
							<td>{{$partner['emailAddress']}}</td>
							<td>{{$partner['phoneNumber']}}</td>
							<td>

								<a href='#' onclick="showPartner('<?php echo $partner['userIdentifier']; ?>')" > <i class='fa fa-edit fa-2x' style='color:#0077B6; margin-right: 10px;'></i></a>
								<a href='#' onclick="showPartnerPayments('<?php echo $partner['userIdentifier']; ?>')" > <i class='fa fa-money fa-2x' style='color:#52B788; margin-right: 10px;'></i></a>
								@if(isLoggedInUserAdmin())<a href='#' onclick="deletePartner('<?php echo $partner['userIdentifier']; ?>', '<?php echo $partner['fullname'] ?>', '<?php echo $row_number ?>')"  > <i class='fa fa-trash fa-2x' style='color:#9D0208'></i></a>@endif

							</td>
						</tr>
						@php $row_number++; @endphp
					@endforeach

				</tbody>
			</table>
		</div>
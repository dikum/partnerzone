<div class='full-payment-details'>
	
	<div class="payment-content"></div>

</div>
<section>
	
	<div style="text-align:center;" id='payment-message'></div>
	<div class='container' style="padding-top:50px;">
		<nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark'>

			<div class='navbar-collapse' id='partnerSearch'>
				
				<form class='mt-2 mt-md-0' id="search_payment_form">
					{{ csrf_field() }}

					<div class="row">
				
						<div class="form-group">
							<select name="bankIdentifier" class="form-control" style="margin-left: 5px;">
								<option value="">All Banks</option>
								@foreach($banks as $bank)
									<option value="{{$bank['bankIdentifier']}}">{{$bank['bankShortName']}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<input style="margin-left: 10px;" name="paymentDescription" id="paymentDescription" class="form-control" placeholder="Description">
						</div>

						<div class="form-group">
							<input name="paymentDepositor" id="paymentDepositor" class="form-control" placeholder="Depositor">
						</div>

						<div class="form-group">
							<input name="emailAddress" id="emailAddress" class="form-control" placeholder="Email Address">
						</div>

						<div class="form-group">
							<input name="phoneNumber" id="phoneNumber" class="form-control" placeholder="Phone Number">
						</div>

						<div class="form-group">
							<input name="paymentChannel" id="paymentChannel" class="form-control" placeholder="Payment Channel">
						</div>



						<!--
						<span style="margin-left: 10px; color:#ECF8F8; ">From</span>
						<div class="form-group" >
							<input type="date" name="from_date" id="from_date" class="form-control">
						</div>

						<span style="margin-left: 10px; color:#ECF8F8; ">To</span>
						<div class="form-group" style="margin-left: 5px;">
							<input type="date" name="to_date" id="to_date" class="form-control">
						</div>
					-->

						<div class="form-group" style='margin-left: 10px;'>
							<div class="form-group"><button id='search_payment_btn' class="btn btn-outline-success my-2 my-sm-0"> Search </button></div>
						</div>
					</div>

				</form>

			</div>
		</nav>

		<div id='payments'>
			<table class='table table-hover table-striped table-sm' id='paymentTable'>
                <thead class="thead-dark">
                    <tr>
                    	<th>Partner ID</th>
                        <th>Pay Date</th>
                        <th>Entered Date</th>
                        <th>Amount</th>
                        <th>Bank</th>
                        <th class="hide">Depositor</th>
                        <th>Entered By</th>
                        <th>Channel</th>
                        <th class="hide">Description</th>
                        <th class="hide">Email</th>
                        <th class="hide">Phone</th>
                        <th>Action</th>
                    </tr>
               	</thead>
               		<tbody>
	                    @php $row_number = 1; @endphp
	                    @foreach($payments as $payment)
                    
                        <tr class="payment-row">
                        	<td>{{$payment['friendlyPartnerIdentifier']}}</td>
                            <td>{{$payment['datePaid']}}</td>
                            <td>{{$payment['createdDate']}}</td>
                            <td>{{getCurrencyCodeFromCollection($currencies, $payment['currencyIdentifier'])}} {{$payment['amountPaid']}}</td>
                            <td>{{getBankNameFromCollection($banks, $payment['bankIdentifier'])}}</td>
                            <td class="hide payment-depositor">{{$payment['depositor']}}</td>
                            <td>{{$payment['userIdentifier']}}</td>
                            <td>{{$payment['paymentChannel']}}</td>
                            <td class="hide payment-description">{{$payment['paymentDescription']}}</td>
                            <td class="hide payment-email">{{$payment['emailAddress']}}</td>
                            <td class="hide payment-phone">{{$payment['phoneNumber']}}</td>
                            <td>

                                @if(getBankNameFromCollection($banks, $payment['bankIdentifier'])  == 'strongroom')<a href='#' onclick="modifyPayment('<?php echo $payment['paymentIdentifier'];?>')" > <i class='fa fa-edit fa-2x' style='color:#52B788; margin-right: 10px;'></i></a> @endif

                                @if(isLoggedInUserAdmin())<a href='#' onclick="deletePayment('<?php echo $payment['paymentIdentifier']; ?>','<?php echo $row_number ?>')" > <i class='fa fa-trash fa-2x' style='color:#9D0208'></i></a>@endif

                            </td>
                        </tr>
                        @php $row_number++; @endphp
                    @endforeach

                </tbody>
            </table>
		</div>
	</div>
</section>






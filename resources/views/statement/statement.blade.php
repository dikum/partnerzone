
<section class="main-section statement-section">
	<div style="text-align:center;" id='statement-message'></div>
	<div class='container' style="padding-top:50px;">
		<nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark' id='searchStatementNav' style="margin-bottom: 50px;">

			<div class='collapse navbar-collapse' id='statementSearch'>
				
				<form class='mt-2 mt-md-0' id="search_statement_form">
					{{ csrf_field() }}

					<div class="form-group row">
						<select name="statement_search_option" id="statement_search_option" class="form-control" style="margin-left: 5px;">
							<option value="">Select</option>
							<option value="bankIdentifier">Bank</option>
							<option value="currencyIdentifier">Currency</option>
							<option value="amountPaid">Amount</option>
							<option value="depositorName">Depositor</option>
							<option value="paymentDescription">Description</option>
							<option value="payerEmail">Email</option>
							<option value="payerPhone">Phone Number</option>
						</select>
					</div>
						
					<div class="form-group hide row" id="bankIdentifierDiv">
						<label class="label-left white-label" for='bankIdentifier'>Bank</label>
						<select name="bankIdentifier" class="form-control" >
							<option value="">Select Bank</option>
							@foreach($banks as $bank)
								<option value="{{$bank['bankIdentifier']}}">{{$bank['bankShortName']}}</option>
							@endforeach
						</select>
						<i class='fa fa-minus hide-statement-search-criteria remove-search' aria-hidden='true'></i>
					</div>

					<div class="form-group hide row" id="currencyIdentifierDiv">
						<label class="label-left white-label" for='currencyIdentifier'>Currency</label>
						<select name="currencyIdentifier" class="form-control" >
							<option value="">Select Currency</option>
							@foreach($currencies as $currency)
								<option value="{{$currency['currencyIdentifier']}}">{{$currency['currencyShortName']}}</option>
							@endforeach
						</select>
						<i class='fa fa-minus hide-statement-search-criteria remove-search' aria-hidden='true'></i>
					</div>

					<div class="form-group hide row" id="amountPaidDiv">
						<label class="label-left white-label" for='amountPaid'>Amount</label>
						<input  name="amountPaid" id="amountPaid" class="form-control" type="number" placeholder="Amount">
						<i class='fa fa-minus hide-statement-search-criteria remove-search' aria-hidden='true'></i>
					</div>

					<div class="form-group hide row" id="depositorNameDiv">
						<label class="label-left white-label" for='depositorName'>Depositor</label>
						<input input  name="depositorName" id="depositorName" class="form-control" placeholder="Depositor">
						<i class='fa fa-minus hide-statement-search-criteria remove-search' aria-hidden='true'></i>
					</div>

					<div class="form-group hide row" id="paymentDescriptionDiv">
						<label class="label-left white-label" for='paymentDescription'>Description</label>
						<input input  name="paymentDescription" id="paymentDescription" class="form-control" placeholder="Description">
						<i class='fa fa-minus hide-statement-search-criteria remove-search' aria-hidden='true'></i>
					</div>

					<div class="form-group hide row" id="payerEmailDiv">
						<label class="label-left white-label" for='payerEmail'>Email</label>
						<input input  name="payerEmail" id="payerEmail" class="form-control" placeholder="Email">
						<i class='fa fa-minus hide-statement-search-criteria remove-search' aria-hidden='true'></i>
					</div>

					<div class="form-group hide row" id="payerPhoneDiv">
						<label class="label-left white-label" for='payerPhone'>Phone Number</label>
						<input input  name="payerPhone" id="payerPhone" class="form-control" placeholder="Phone Number">
						<i class='fa fa-minus hide-statement-search-criteria remove-search' aria-hidden='true'></i>
					</div>

					<div class="form-group">
						<div class="form-group"><button id='search_statement_btn' class="btn btn-outline-success my-2 my-sm-0"> Search </button></div>
					</div>
				</form>
			</div>
			<span data-toggle="modal" data-target="#importStatementModal">
  				<image id="show-import-statement" class='add-object' src="{{'assets/images/add.png'}}" />
			</span>
			
		</nav>

		<div id='statement'>
			<table class='table table-hover table-striped table-sm table-responsive' id='statementTable'>
				<caption>Bank Statement</caption>
				<thead class="thead-dark">
					<tr>
						<th>Date</th>
						<th>Bank</th>
						<th>Currency</th>
						<th>Amount</th>
						<th>Depositor</th>
						<th>Description</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Channel</th>
					</tr>
				</thead>
				<tbody>
					@foreach($statements as $statement)
						<tr>
							<td>{{$statement['valueDate']}}</td>
							<td>{{$statement['bankIdentifier']}}</td>
							<td>{{$statement['currencyIdentifier']}}</td>
							<td>{{$statement['amountPaid']}}</td>
							<td>{{$statement['depositorName']}}</td>
							<td>{{$statement['paymentDescription']}}</td>
							<td>{{$statement['payerEmail']}}</td>
							<td>{{$statement['payerPhone']}}</td>
							<td>{{$statement['payment_method']}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	
</section>
<!-- Modal -->
	<div class="modal fade" id="importStatementModal" tabindex="-1" role="dialog" aria-labelledby="importStatementModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header border-0">
	        <h5 class="modal-title" id="importStatementModalLabel">Import Bank Statement</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      	<div style="text-align:center;" id='import-message'></div>
	      	<form name="import_statement_form" id="import_statement_form" enctype="multipart/form-data" method="post">
	      		{{ csrf_field() }}
		        <div class="form-group row">
		        	<select name="bank_to_import" id="bank_to_import" class="form-control" >
						<option value="">Select Bank</option>
						@foreach($banks as $bank)
							<option value="{{$bank['bankIdentifier']}}">{{$bank['bankShortName']}}</option>
						@endforeach
					</select>
		        </div>
		        <div class="form-data row">
		        	<input class="form-control" type="file" name="bank_statement" id="bank_statement">
		        </div>

		         <div class="modal-footer border-0">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <button id='import_statement_btn' name="import_statement_btn" class="btn btn-primary">Import</button>
	        </form>
	      </div>
	      </div>
	    </div>
	  </div>
	</div>
<style type="text/css">
	
	.modal-backdrop, .modal-backdrop.fade.in {
	     opacity: 0!important;
	     filter: alpha(opacity=0)!important;
 	}

 	.blur{
      opacity: 0.6!important;
      filter: alpha(opacity=60)!important;
 	}

</style>

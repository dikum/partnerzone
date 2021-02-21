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
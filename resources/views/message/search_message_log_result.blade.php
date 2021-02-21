<div id='partners'>

	<table class='table table-hover table table-striped table-bordered table-sm' id='messageLogTable'>
		<caption>Message Search Result</caption>
		<thead class="thead-dark">
			<tr>
				<th>Subject</th>
				<th>Entered By</th>
				<th>Recipient</th>
				<th>Status</th>
				<th>Message</th>
			</tr>
		</thead>
			@php $row_number = 1; @endphp
			@foreach($messages as $message)
			<tbody>
				<tr>
					<td>{{$message['emailSubject']}}</td>
					<td>{{$message['enteredByUser']}}</td>
					<td>{{$message['recipient']}}</td>
					<td>{{$message['messageStatus']}}</td>
					<td>{{$message['messageBody']}}</td>					
				</tr>
				@php $row_number++; @endphp
			@endforeach

		</tbody>
	</table>
</div>
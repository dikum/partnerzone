<section>
	<div style="text-align:center;" id='log-message'></div>
	<div class='container' style="padding-top:50px;">
		<nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark' id='searchMessageLogNav' style="margin-bottom: 50px;">

			<div class='collapse navbar-collapse' id='messageLogSearch'>
				
				<form class='mt-2 mt-md-0' id="search_message_log_form">
					{{ csrf_field() }}

					<div class="row">
						<div class="form-group">
							<select name="search_message_log_option" id="search_message_log_option" class="form-control mr-sm-2 search-group">
								<option value="">Select</option>
								<option value="partnerIdentifier">Partner ID</option>
								<option value="recipient">Recipient</option>
							</select>
						</div>
					
						<div class="form-group" style="margin-left: 5px;"> <input id="search_message_log_value" name='search_message_log_value' class='form-control mr-sm-2' type="text" placeholder="Search" aria-label='Search'> </div>

						<div class="form-group" style="margin-left: 5px;">
							<input type="date" name="fromDate" id="fromDate" class="form-control search-group">
						</div>

						<div style="font-size:2em; color:white;">-</div>

						<div class="form-group" style="margin-left: 5px;"class="form-group" style="margin-left: 5px;">
							<input type="date" name="toDate" id="toDate" class="form-control">
						</div>

					</div>

					<div class="row">
						<div class="form-group">
								<div class="form-group"><button id='message-log-search' name='message-log-search' class="btn btn-outline-success my-2 my-sm-0"> Search </button></div>
						</div>
					</div>

				</form>

			</div>
			
		</nav>

		<div id='messageLog'>
			<table class='table table-hover table-striped table-sm table-responsive' id='messageLogTable'>
				<caption>Message Log</caption>
				<thead class="thead-dark">
					<tr> 
						<th>Partner ID</th>
						<th>Subject</th>
						<th>Entered By</th>
						<th>Recipient</th>
					</tr>
				</thead>
				<tbody>
					@foreach($logs as $log)
						<tr>
							<td>{{$log['partnerIdentifier']}}</td>
							<td>{{$log['emailSubject']}}</td>
							<td>{{$log['enteredByUser']}}</td>
							<td>{{$log['recipient']}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</section>



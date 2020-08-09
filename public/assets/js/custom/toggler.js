$(document).ready(function(){
	$(document).on('click', '#search-partner-toggler', function(){
		$('#searchPartnerNav').toggle('blind');
	});

	$(document).on('click', '#search-partner-message-toggler', function(){
		$('#searchPartnerMessageNav').toggle('blind');
	});
});
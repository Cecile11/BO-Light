$(document).ready(function(){
	$('#performanceTable').DataTable({
		searching: false,
		paging: false,
		ordering: false,
		"columns": [
		    { "width": "20px" },
		    { "width": "20px" },
		    { "width": "20px" },
		    { "width": "20px" },
		    { "width": "20px" },
		    { "width": "20px" },
		    null
		  ]
	});
});
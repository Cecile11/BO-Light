$(document).ready(function(){
    $('#ClientTable').DataTable({
		"aaSorting":[],
		"columnDefs":[{"type":"html-num-fmt","target":2}]
    });
});
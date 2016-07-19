function getPayment(i,max,limit){
	var pbar = $('#progressPayment');
	if (i >= max){
		pbar.attr('class','progress-bar-success progress-bar-striped');
		$('#progressPayment span').html("Success : "+max+" payments loaded");
	} else {
		var nb = ((max - i) < 5) ? max - i : 5;
		$.ajax({
			url:$(location).attr('href') + '/get_payment',
			method:'POST',
			data:{'number':nb,'limit':limit}
		}).done(function(data){
			pbar.attr('aria-valuenow',i+nb);
			var pourcentage = Math.floor((i+nb)/max*100);
			pbar.attr('style','width:'+pourcentage+'%');
			$('#progressPayment span').html(i+nb+"/"+max+" loaded");
			getPayment(i+nb,max,limit);
		}).fail(function( jqXHR, textStatus ) {
 			console.log( "Request failed: " + textStatus );
		});
	}
}
$(document).ready(function(){
	$("#delete").on('click', function() {
		if (confirm("Are you sure?")){
			$.ajax({
				"url":$(location).attr('href') + '/delete'
			}).done(function(data){
				alert(data);
			});
		}
	});
	$("#deletePayment").on('click', function() {
		if (confirm("Are you sure?")){
			$.ajax({
				"url":$(location).attr('href') + '/delete_payment'
			}).done(function(data){
				alert(data);
			});
		}
	})
	$('#getPayment').on('click',function(){
			var max = $('#numberPayment').val()*1;
			$('#progressPayment').attr('aria-valuemax',max);
			$('#progressPayment span').html('starting : '+max+' will be loaded');
			getPayment(0,max,'quarter');
	});
	$('.getPaymentFrom').on('click',function(){
		var time = $(this).html();
		console.log(time);
		$.ajax({
			url:$(location).attr('href') +'/payment_from',
			method:'POST',
			data:{'time':time}
		}).done(function(data){
			console.log(data);
			$('#progressPayment').attr('aria-valuemax',data*1);
			$('#progressPayment span').html('starting : '+data+' will be loaded');
			getPayment(0,data*1,time);

		}).fail(function(jqXHR, textStatus){
			console.log( "Request failed: " + textStatus );
		})
	});
	$('#loaddb').on('click',function(){
		$.ajax({
			url:$(location).attr('href') +'/loaddb'
		}).done(function(data){
			console.log(data);
			alert("Ipn loaded");
		}).fail(function( jqXHR, textStatus ) {
 			console.log( "Request failed: " + textStatus );
		});
	});
	$('#reloaddb').on('click',function(){
		$.ajax({
			url:$(location).attr('href') + '/delete'
		}).done(function(data){
			alert(data + " Ipn will be loaded");
			$.ajax({
				url:$(location).attr('href') +'/loaddb'
			}).done(function(data){
				console.log(data);
				alert('Ipn Loaded');
			});
		});
	});
});
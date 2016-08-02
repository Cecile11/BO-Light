(function (rJS, jIO, RSVP, JSON){
	
	var gk = rJS(window);

	gk.declareService(function(){
		var g = this;
		return g.declareGadget("payzenGadget")
		.push(function(gadget){
			return gadget.allTransaction({
				select_list: ["requestId"]
			});
		})
		.push(function(data){
			return g.getElement()
			.push(function(element){
				var oTable = element.getElementsByTagName('table')[0];
				var table = "<tr><th>TransID</th></tr>";
				data.data.rows.forEach(function(datum){
					if(!datum.id.match('_replicate')){
						table += "<tr><td>"+datum.id+"</td></tr>";
					}
				});
				oTable.innerHTML = table;
			});
		});
	});

}(rJS, jIO, RSVP, JSON));
(function (rJS, jIO, RSVP, JSON){
	
	var gk = rJS(window);

	gk.declareService(function(){
		var g = this;
		return g.getDeclaredGadget("payzenGadget")
		.push(function(gadget){
			return gadget.allTransaction({
				limit: [0, 50],
				select_list: ["orderResponse_orderId"]
			});
		})
		.push(function(data){
			return g.getDeclaredGadget("tableGadget")
			.push(function(gadget){
				return gadget.setData([{
					"sTitle":"Uuid",
					"mData":"id"
				},{
					"sTitle":"OrderId",
					"mData":"value.orderResponse_orderId"
				}],data);
			});
		});
	});

}(rJS, jIO, RSVP, JSON));
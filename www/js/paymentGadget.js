/*jslint indent: 2, maxlen: 80, nomen: true */
/*global console, window, document, rJS, RSVP, $ */
(function ($, window, RSVP, rJS) {
  "use strict";

  rJS(window)
  .declareService(function(){
  	var g = this;
  	return g.getAllPayment()
  		.push(function(data){
  			return g.getDeclaredGadget("table")
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
	})
  /* ========================= METHODS NEEDED =========================== */
    .declareAcquiredMethod("nav_main_gadget","main_gadget")
    .declareAcquiredMethod("getAllPayment","storage_getPayment");

}($, window, RSVP, rJS));
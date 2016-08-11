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
					"sTitle":"Uuid<br><small>TransactionID</small>",
					"mData":"value",
					"mRender": function(data){
						return data.paymentResponse_transactionUuid + '<br><small>' + data.paymentResponse_transactionId + '</small>';
					}
				},{
					"sTitle":"OrderId<br><small>Ref client | Détails</small>",
					"mData":"value",
					"mRender": function(data){
						return data.orderResponse_orderId + '<br><small>' + data.customerResponse_billingDetails_reference + '&nbsp;</small><button type="button" class="btn btn-primary btn-sm" data-container="body" data-toggle="popover" data-placement="auto right" data-content="'+ formatJSONdata(data) +'" data-original-title="Détails" title="Détails"  data-html="true" >+</button>';
					}
				},{
					"sTitle":"Amount<br><small>Modified</small>",
					"mData":"value.paymentResponse_amount",
					"sType":"num-spe",
					"mRender": function(amount,type,data){
						return amount +'<br><small>' + amount/100 + '/' + data.value.paymentResponse_currency + '</small>';
					}
				},{
					"sTitle":"<strong> CreationDate </strong><br><small> LastDate </small>",
					"mData":"value",
					"mRender": function(data){
						return data.paymentResponse_creationDate + '<br><small>' + (data.captureResponse_date ? data.captureResponse_date : data.paymentResponse_creationDate) + '</small>';
					}
				},{
					"sTitle":"Status",
					"mData":"value.commonResponse_transactionStatusLabel"
				},{
					"sTitle":"Type",
					"mData":"value.paymentResponse_operationType",
					"mRender": function(data){
						return (data ? "CREDIT" : "DEBIT");
					}
				},{
					"sTitle":"Brand",
					"mData":"value.cardResponse_brand"
				},{
					"sTitle":"Mail",
					"mData":"value.customerResponse_billingDetails_email"
				}],data);
			});
		});
	})
  /* ========================= METHODS NEEDED =========================== */
    .declareAcquiredMethod("nav_main_gadget","main_gadget")
    .declareAcquiredMethod("getAllPayment","storage_getPayment");

  var formatJSONdata = function(data){
  	var string = JSON.stringify(data);
  	// TODO : up this function.
  	string = string.replace(/[,]/g,'<br>');
  	string = string.replace(/["']/g,'');
  	return string;
  }

}($, window, RSVP, rJS));
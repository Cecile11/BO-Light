/*jslint indent: 2, maxlen: 80, nomen: true */
/*global console, window, document, rJS, RSVP, $ */
(function (window, document, rJS, RSVP) {
  "use strict";

  var STORAGE_GADGET = "./gadget/payzen",
    NAVIGATION_GADGET = "./gadget/navigation",pages;

  rJS(window)

    /**
    * ready handler executing when the gadget is ready
    * @method  ready
    * @param   {Object}  g  Gadget object
    */
    .ready(function (g) {
        g.render()
    })

    /* ==================================================================== */
    /*                             ENTRY POINT                              */
    /* ==================================================================== */
    /**
    * main gadget initializer which loads all gadgets and calls render
    * method of each gadget if it's available
    * @method  render
    */
    .declareMethod('render', function () {
      var g = this;

      // load init gadgets
      return g.declareGadget(STORAGE_GADGET, {
          element: g.__element,
          scope: "storage"
        }).push(function(){
          return g.declareGadget(NAVIGATION_GADGET, {
            element: g.__element,
            scope:"navigation"
        }).push(function(){
          g.getDeclaredGadget("navigation")
          .push(function(gadget){
            return gadget.addPages([{"title":"Sales","gadget":"sales"},{"title":"Payment","gadget":"payment"}]);
          }).push(function(button_list){
            console.log(button_list);
          });
        });
      }).fail(console.log);
    });

    /* ======================== METHODS EXPOSED =========================== */

    /* ========================= METHODS NEEDED =========================== */

    /* ==================================================================== */
    /*                            METHOD INDEX                              */
    /* ==================================================================== */
	// gk.declareService(function(){
	// 	var g = this;
	// 	return g.getDeclaredGadget("navigationGadget")
	// 	.push(function(gadget){
	// 		return gadget.addPages(['Payments','Sales']);
	// 	});
	// }).declareService(function(){
	// 	var g = this;
	// 	return g.getDeclaredGadget("payzenGadget")
	// 	.push(function(gadget){
	// 		return gadget.allTransaction({
	// 			limit: [0, 50],
	// 			select_list: ["orderResponse_orderId"]
	// 		});
	// 	})
	// 	.push(function(data){
	// 		return g.getDeclaredGadget("tableGadget")
	// 		.push(function(gadget){
	// 			return gadget.setData([{
	// 				"sTitle":"Uuid",
	// 				"mData":"id"
	// 			},{
	// 				"sTitle":"OrderId",
	// 				"mData":"value.orderResponse_orderId"
	// 			}],data);
	// 		});
	// 	});
	// });

}(window, document, rJS, RSVP));
/*jslint indent: 2, maxlen: 80, nomen: true */
/*global console, window, document, rJS, RSVP, $ */
(function (window, document, rJS, RSVP) {
  "use strict";

  var STORAGE_GADGET = "./gadget/payzen",
    NAVIGATION_GADGET = "./gadget/navigation";

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
      return g.getDeclaredGadget("navigation")
      .push(function(gadget){
        return gadget.addPages([{"title":"Sales","gadget":"gadget/sales"},{"title":"Payment","gadget":"gadget/payment"}],g);
      })
      .push(function(gadget){
        return gadget.changePage("gadget/payment");
      });
    })
    /* ======================== METHODS EXPOSED =========================== */
    .declareMethod("pass_main_gadget",function(){
      return this;
    })
    /* ========================= METHODS NEEDED =========================== */

    /* ==================================================================== */
    /*                            METHOD INDEX                              */
    /* ==================================================================== */
    .allowPublicAcquisition("storage_getPayment",function(){
      return this.getDeclaredGadget("storage")
      .push(function(storage){
        return storage.getPayment();
      });
    })
    .allowPublicAcquisition("main_gadget",function(){
      return this.pass_main_gadget();
    })
    .declareService(function(){
      return this.getDeclaredGadget("storage")
      .push(function(gadget){
        gadget.syncAll();
      });
    })

}(window, document, rJS, RSVP));
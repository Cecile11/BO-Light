/*jslint indent: 2, maxlen: 80, nomen: true */
/*global console, window, document, rJS, RSVP, $ */
(function ($, window, RSVP, rJS) {
  "use strict";

  rJS(window)
  .ready(function(g){
    g.render();
  }).declareMethod("render",function(){
    var g = this;
    g.time_selected = "Day";
    g.getElement()
    .push(function(element){
        var oButtons = $(element).find("button");
        for (var i = oButtons.length - 1; i >= 0; i--) {
            oButtons[i].addEventListener('click',function(){
                if (this.value != g.time_selected){
                    $('.time-'+g.time_selected).attr('class','btn-sm btn-primary time-'+g.time_selected);
                    $(this).attr('class','btn-sm btn-success time-'+this.text);
                    g.time_selected = this.text;
                };
            });
        };
    });
  })
  /* ======================== METHODS TO EXPOSE ========================= */


}($, window, RSVP, rJS))
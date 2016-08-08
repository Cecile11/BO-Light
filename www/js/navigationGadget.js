/*jslint indent: 2, maxlen: 80, nomen: true */
/*global console, window, document, rJS, RSVP, $ */
(function ($, window, RSVP, rJS) {
  "use strict";

  rJS(window)
  /**
    * ready handler executing when the gadget is ready
    * @method  ready
    * @param   {Object}  g  Gadget object
    */
  	.declareMethod("addPages",function(page_list){
  		var g = this;
    	return g.getElement()
    	.push(function(element){
    		page_list.forEach(function(page){
    			$(element).find('.urls-list').append('<li class="active"><a style="padding:0.5em 1em;"><button type="button" class="btn btn-link" value="'+ page.gadget +'">'+page.title+'</button></a></li>');
    		});
    		g.page_list = (g.page_list instanceof Array) ? g.page_list.concat(page_list) : page_list;
    			$(element).find('.urls-list button').forEach(function(button){
    				console.log(button);
    			});
    		return "Bonjour";
    	});
    });

}($, window, RSVP, rJS))
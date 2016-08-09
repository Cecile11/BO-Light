/*jslint indent: 2, maxlen: 80, nomen: true */
/*global console, window, document, rJS, RSVP, $ */
(function ($, window, RSVP, rJS) {
  "use strict";

  rJS(window)
  /* ======================== METHODS TO EXPOSE ========================= */
  	.declareMethod("addPages",function(page_list){
  		var g = this;
    	return g.getElement()
    	.push(function(element){
    		var oLi, oLink, oButton;
    		page_list.forEach(function(page){
    			oLi = $('<li class="active"></li>');
    			oLink = $('<a style="padding:0.5em 1em;"></a>');
    			oButton = $('<button type="button" class="btn btn-link" value="'+page.gadget+'">'+page.title+'</button>');
    			oLink.append(oButton);
    			oLi.append(oLink);
    			$(element).find('.urls-list').append(oLi);
    			oButton.on('click',function(){
    				g.changePage(this.value);
    			});
    		});
    		g.page_list = (g.page_list instanceof Array) ? g.page_list.concat(page_list) : page_list;

    		return g;
    	});
    })
    .declareMethod('changePage',function(gadget){
    	var g = this;
        console.log(gadget);
        g.nav_main_gadget()
        .push(function(main_gadget){
            var main_gadget = main_gadget;
        	if (g.selected_page){
        		if (g.selected_page != gadget){
                    main_gadget.dropGadget("current_page");
                    $(main_gadget.__element).find(".current_page").html("");
                    g.selected_page = gadget;
        			return main_gadget.declareGadget(gadget,{
                         element:$(main_gadget.__element).find(".current_page")[0],
                         scope:"current_page"
                     });
        		}
        	} else {
                $(main_gadget.__element).append($('<div class="current_page"></div>'));
        		g.selected_page = gadget;
        		return main_gadget.declareGadget(gadget,{
                    element:$(main_gadget.__element).find(".current_page")[0],
                    scope:"current_page"
                });
        	};
        });
    })
    /* ========================= METHODS NEEDED =========================== */
    .declareAcquiredMethod("nav_main_gadget","main_gadget");

}($, window, RSVP, rJS))
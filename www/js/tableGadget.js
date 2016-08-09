(function ($,rJS, RSVP, JSON){
  
  var gk = rJS(window);

  gk.declareMethod("setData",function(header,data){
    var g = this;
    return g.getElement()
    .push(function(element){
      var oTable = $(element).find(".table-data").eq(0);
      var dataTable = $.fn.dataTable;
      oTable.dataTable({
        "aaData":data.data.rows,
        "aoColumns":header
      });
      return g;
    });
  });

}($,rJS, RSVP, JSON));
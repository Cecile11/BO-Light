/*jslint indent: 2, maxlen: 80, nomen: true */
/*global console, window, rJS, jIO, RSVP */
(function (window, rJS, jIO, RSVP){
  
  var gk = rJS(window);

  var jio = jIO.createJIO({
    type:"query",
      sub_storage:{
        type:"replicate",
        conflict_handling:2,
        local_sub_storage:{
          type:"indexeddb",
          database:"payzenDb"
        },
        remote_sub_storage:{
          type:"payzenStorage",
          path:"/allPayments"
        }
      }
    });

  gk.declareMethod("allTransaction",function(query){
    return jio.allDocs(query);
  })
  .declareMethod("syncAll",function(){
    return jio.repair().fail(function(error){
      console.log(error)
    });
  }).declareService(function(){
    return this.syncAll();
  });

}(window, rJS, jIO, RSVP));
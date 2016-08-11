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

  gk.declareMethod("getPayment",function(){
    return jio.allDocs()
    .push(function(data){
      var data = data;
      var promises = [];
      data.data.rows.forEach(function(row){
        promises.push(jio.get(row.id));
      });
      return RSVP.all(promises)
      .then(function(values){
        var j;
        for (var i = data.data.rows.length - 1; i >= 0; i--) {
          if (/_replicate/.test(data.data.rows[i].id)){
            j = i;
          } else {
            data.data.rows[i].value = values[i];
          }
        };
        data.data.rows.splice(j,1); // Delete __replicate record.
        return data;
      });
    });
  })
  .declareMethod("syncAll",function(){
    return jio.repair().fail(function(error){
      console.log(error)
    });
  })
  .declareService(function(){
    return this.syncAll();
  });

}(window, rJS, jIO, RSVP));
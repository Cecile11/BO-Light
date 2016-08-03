/*var jio = jIO.createJIO({
  type: "query",
  sub_storage: {
    type:"document",
    document_id: "/",
    sub_storage: {
      type: "local",
      sessiononly: true
    }
  }
});

jio.put('cle1',{'title':'doc_test'})
  .then(function(){
    console.log('doc create');
    return jio.put('cle2',{'title':'doc_autre'});
  })
  .then(function(){
    console.log('doc create');
    return jio.put('cle3',{'title':'bonjour'});
  })
  .then(function(){
    return jio.put('cle2',{'content':'lorem ipsum'});
  })
  .then(function(){
    console.log('doc create');
    return jio.allDocs({
      query:'(title: "d%") OR (content: "l%")',
      select_list: ["title"]
    });
  })
  .then(function(response){
    for(i = 0; i < response.data.total_rows; i++ ){
      console.log(response.data.rows[i]);
    }
  })
  .fail(function(error){
    console.log(error);
});*/
(function (rJS, jIO, RSVP, JSON){
  
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

}(rJS, jIO, RSVP, JSON));
<script>
(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.com/en_US/messenger.Extensions.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'Messenger'));

//the Messenger Extensions JS SDK is done loading:
window.extAsyncInit = function() {

	
	<?php if(is_dev()){ ?>
	
	var psid = '1443101719058431';
	$.post("/my/display_actionplan/"+psid+"/<?= $b_id ?>/<?= $c_id ?>", {}, function(data) {
 		//Update UI to confirm with user:
 		$( "#page_content").html(data).append('<p style="font-size:0.6em; color:#999;">In local development mode</p>');
 	});
 	
	<?php } else { ?>
	
	//Get context:
	MessengerExtensions.getContext('1782431902047009', 
      function success(thread_context){
        // success
        //User ID was successfully obtained.
      	var psid = thread_context.psid;
      	var signed_request = thread_context.signed_request;
        //Fetch Page:
     	$.post("/my/display_actionplan/"+psid+"/<?= $b_id ?>/<?= $c_id ?>?sr="+signed_request, {}, function(data) {
     		//Update UI to confirm with user:
     		$( "#page_content").html(data);
     	});
      },
      function error(err){
        // error
		$("#page_content").html('<div class="alert alert-danger" role="alert">ERROR: '+err+'</div>');
      }
    );
    
    <?php } ?>
    
};

//Optionally you can close webview like this:
function close_webview(){
	window.location = 'https://www.messenger.com/closeWindow/?display_text=Closing....';
}
</script>

<div id="page_content"><div style="text-align:center;"><img src="/img/round_yellow_load.gif" class="loader" /></div></div>
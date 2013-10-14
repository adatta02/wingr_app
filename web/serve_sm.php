<?php if( array_key_exists("trk", $_REQUEST) ): ?>

(function() {

	<?php require_once 'jquery-1.10.2.min.js'; ?>
	
	jQuery.noConflict();
	var $ = jQuery;
	
	if( window.location.href.indexOf("viewprofile.aspx") != -1 && $("[name='sendmessage']").length > 0 ){
	
		var url = "http://www.wingr.me/serve.php?id=$UID&imp_type=c&served=searchamong_quick&url=";
		$("<div style='width:450px !important; margin-top:10px !important' class='button big-blue center'><a class='plain' href='" + url + "'>Need help writing this message? Click here!</a></div>")
			.insertAfter("[name='sendmessage'] input:submit");
	
	}
	
	if( window.location.href.indexOf("sendmessage.aspx") != -1 && $("[name='sendmessage']").length > 0 ){
	
		var url = "http://www.wingr.me/serve.php?id=$UID&imp_type=c&served=searchamong_main&url=";
		$("<div style='width:450px !important; margin-top:10px !important' class='button big-blue center'><a class='plain' href='" + url + "'>Need help writing this message? Click here!</a></div>")
			.insertAfter("[name='sendmessage'] input:submit");
	}

  var d=document,h=d.getElementsByTagName('head')[0],s=d.createElement('script'); 
  s.type='text/javascript'; 
  s.async=true; 
  s.src='https://ib.adnxs.com/getuid?http://www.wingr.me/serve.php?id=$UID&imp_type=v&served=searchamong&r=' + (Math.random() * 1e17) + '&url=' + window.location.href;  
  h.appendChild(s); 
   
})();

<?php

endif;

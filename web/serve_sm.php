<?php 

if( array_key_exists("trk", $_REQUEST) ){
?>
	(function() {
	  var d=document,h=d.getElementsByTagName('head')[0],s=d.createElement('script'); 
	  s.type='text/javascript'; 
	  s.async=true; 
	  s.src='http://ib.adnxs.com/getuid?http://www.wingr.me/serve_sm.php?id=$UID&r=' + (Math.random() * 1e17) + '&url=' + window.location.href;  
	  h.appendChild(s); 
	})();	
<?php 
}else{

	$pdo = new PDO("mysql:dbname=wingr;host=localhost", "wingr", "wingr");
	$sth = $pdo->prepare("INSERT INTO impression_sm (ip, appnexus, user_agent, url) VALUES (:ip, :appnexus, :user_agent, :url)");
	$sth->execute( array("ip" => $_SERVER["REMOTE_ADDR"], "appnexus" => $_REQUEST["id"],
						 "user_agent" => $_SERVER["HTTP_USER_AGENT"], "url" => $_REQUEST["url"]) );
	
	echo "true;";
	die();
	
}
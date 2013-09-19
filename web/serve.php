<?php 

if( array_key_exists("tag", $_REQUEST) || array_key_exists("path", $_REQUEST) ){
		
	$validFiles = glob( dirname(__FILE__) . "/creatives/*.png" );
	$validFiles = array_merge( $validFiles , glob( dirname(__FILE__) . "/creatives/*.gif" ) );
	
	$path = array_key_exists("path", $_REQUEST) ? $_REQUEST["path"] : basename($validFiles[ rand(0, count($validFiles) - 1) ]);	
	$targetFile = dirname(__FILE__) . "/creatives/" . $path;	
	
	?>
			
document.write("<a target='_blank' href='http://www.wingr.me/serve.php?id=$UID&imp_type=c&served=<?php echo $path?>&r=" + (Math.random() * 1e17) + "&url=" + window.location.href + "'><img src='http://wingr.me/creatives/<?php echo $path?>' /></a>");

(function() {
  var d=document,h=d.getElementsByTagName('head')[0],s=d.createElement('script'); 
  s.type='text/javascript'; 
  s.async=true; 
  s.src='https://ib.adnxs.com/getuid?http://www.wingr.me/serve.php?id=$UID&imp_type=v&served=<?php echo $path?>&r=' + (Math.random() * 1e17) + '&url=' + window.location.href;  
  h.appendChild(s); 
})();

	
<?php 
}else{

	if( $_REQUEST["imp_type"] == "v" ){
		setcookie("appnexus", $_REQUEST["id"]);
	}

	if( $_REQUEST["imp_type"] == "c" ){
		$_REQUEST["id"] = $_COOKIE["appnexus"];			
	}

	$pdo = new PDO("mysql:dbname=wingr;host=localhost", "wingr", "wingr");
	$sth = $pdo->prepare("INSERT INTO impression (ip, appnexus, user_agent, url, tag, imp_type) VALUES (:ip, :appnexus, :user_agent, :url, :tag, :imp_type)");
	$sth->execute( array("ip" => $_SERVER["REMOTE_ADDR"], "appnexus" => $_REQUEST["id"], 
						 "tag" => $_REQUEST["served"], "imp_type" => $_REQUEST["imp_type"], 
					     "user_agent" => $_SERVER["HTTP_USER_AGENT"], "url" => $_REQUEST["url"]) );
	
	if( $_REQUEST["imp_type"] == "c" ){
		header("Location: https://wingr.me/?utm_source=fifty&utm_medium=banner&utm_content=" . $_REQUEST["served"] . "&utm_campaign=POF");
		die();
	}
	
	echo "true;";
	die();	
}
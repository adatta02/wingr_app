<?php 

if( array_key_exists("tag", $_REQUEST) || array_key_exists("path", $_REQUEST) ){
		
	$validFiles = glob( dirname(__FILE__) . "/creatives/*.png" );		
	$path = array_key_exists("path", $_REQUEST) ? $_REQUEST["path"] : basename($validFiles[ rand(0, count($validFiles) - 1) ]);	
	$targetFile = dirname(__FILE__) . "/creatives/" . $path;	
	
	?>
			
document.write("<a target='_blank' href='https://wingr.me/?utm_source=fifty&utm_medium=banner&utm_content=<?php echo $path?>&utm_campaign=POF'><img src='http://wingr.me/creatives/<?php echo $path?>' /></a>");

(function() {
  var d=document,h=d.getElementsByTagName('head')[0],s=d.createElement('script'); 
  s.type='text/javascript'; 
  s.async=true; 
  s.src='https://ib.adnxs.com/getuid?http://www.wingr.me/serve.php?id=$UID&served=<?php echo $path?>&r=' + (Math.random() * 1e17) + '&url=' + window.location.href;  
  h.appendChild(s); 
})();

	
<?php 
}else{

	$pdo = new PDO("mysql:dbname=wingr;host=localhost", "wingr", "wingr");
	$sth = $pdo->prepare("INSERT INTO impression (ip, appnexus, user_agent, url, tag) VALUES (:ip, :appnexus, :user_agent, :url, :tag)");
	$sth->execute( array("ip" => $_SERVER["REMOTE_ADDR"], "appnexus" => $_REQUEST["id"], "tag" => $_REQUEST["served"],
						 "user_agent" => $_SERVER["HTTP_USER_AGENT"], "url" => $_REQUEST["url"]) );
	
	echo "true;";
	die();	
}
<?php
header("Cache-Control: no-cache");
header("Pragma: no-cache");
if(isset($_SESSION)) session_destroy();
try{
	if(file_exists('r2d2.php')){
		global $reffer;
		$_POST['direction']='freeStyle';
		require_once('r2d2.php');
		global $reffer;
		global $confi;
		$reffer=$confi['site']."Sara/IzweSetup.php";
	}
}catch(Exception $e){
	//do nothing
}
?>
<html>
	<head>
		<title>404 Not Found</title>
		<style>
			a:visited{color:#330033;}
		</style>
	</head>
<body text="#ffffff" bgcolor="#0000cc" link="#ffffcc" vlink="#ff9966">

<center><div align="center">
<pre><code>



<table border="0" bgcolor="#C0C0C0"><tbody><tr><td><code><font color="#330033">
	<?	if(isset($reffer)): ?>
		<a style="text-decoration:none;" href=<?=$reffer?>> ERR 404. Click Me To Be Rescued! </a>
	<?else:?>	
	ERR 404
	<?endif;?>
</font></code></td></tr></tbody></table>
FFFFFF0 FFFFFFF FF00000 FF00000 0FFFFF0 FF000FF FF000FF
FF000FF 000F000 FF00000 FF00000 FF000FF FF000FF 0FF0FF0
FFFFFF0 000F000 FF00000 FF00000 0FFF000 FF000FF 00FFF00
FFFFFF0 000F000 FF00000 FF00000 000FFF0 FF000FF 00FFF00
FF000FF 000F000 FF00000 FF00000 FF000FF FF000FF 0FF0FF0
FFFFFF0 FFFFFFF FFFFFFF FFFFFFF 0FFFFF0 0FFFFF0 FF000FF


You have attempted to access a non-existent page.
The current HTTP session will be terminated.


Press any key to continue<blink>_</blink>
</code></pre>
</div></center>
</body></html>
<?php die(); ?>

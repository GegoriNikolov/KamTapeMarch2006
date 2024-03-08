<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo htmlspecialchars($_GET["title"]); ?></title>
</head>
<style>
html {
scrollbar-face-color:#000000;
scrollbar-highlight-color:#000000;
scrollbar-3dlight-color:#000000;
scrollbar-darkshadow-color:#000000;
scrollbar-shadow-color:#000000;
scrollbar-arrow-color:#000000;
scrollbar-track-color:#000000;
}
</style>
<script type="text/javascript" src="swfobject.js"></script>
<script>
function closeFull()
{
	window.close();
}

function fillFull()
{
	window.moveTo(0,0);
	window.resizeTo(screen.availWidth, screen.availHeight)
}
</script>

<body onload="fillFull();" style="background-color:#000000">
	<div id="flashcontent" style="position:absolute; left:0; top:0;right:0;bottom:0">
					Hello, you either have JavaScript turned off or an old version of Macromedia's Flash Player, <a href="http://www.macromedia.com/go/getflashplayer/">click here</a> to get the latest flash player.
	</div>
		
	<script type="text/javascript">
var flashvars = {};
var params = { bgcolor: "#000000" };
var attributes = {};

swfobject.embedSWF("/player2.swf?<?php echo http_build_query($_GET); ?>", "flashcontent", "100%", "100%", 7, false, flashvars, params, attributes);
	</script>
</body>
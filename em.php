<?php 
require "needed/scripts.php";
if(1 == 1){
	redirect("/p.swf?video_id=".htmlspecialchars($_GET['v'])."&eurl=".$_SERVER['HTTP_REFERER']."&iurl=https://bobcat-stable-jointly.ngrok-free.app/get_still.php%3Fvideo_id%3D".htmlspecialchars($_GET['v'])."&t=OEgsToPDskKw571KOk0tX0ux_fsXA9Pp&autoplay=1&BASE_YT_URL=http://localhost/");
}
$video = $conn->prepare("SELECT * FROM videos WHERE vid = ? AND converted = 1");
$video->execute([$_GET['v']]);

if($video->rowCount() == 0) {
	header("Location: /index.php");
	die();
} else {
	$video = $video->fetch(PDO::FETCH_ASSOC);
}
$uploader = $conn->prepare("SELECT * FROM users WHERE uid = ?");
$uploader->execute([$video['uid']]);
$uploader = $uploader->fetch(PDO::FETCH_ASSOC);

if ($uploader['uid'] == NULL) {
    redirect("/index.php");
}
if($video['converted'] == 0) {
	header("Location: /index.php");
}
echo "<title>Watch On KamTape - ".htmlspecialchars($video['title'])."</title>";

?>
<link rel="stylesheet" href="/viewfinder/embed.css"> 
		<div id="flashcontent">
		<div style="padding: 20px; font-size:14px; font-weight: bold;">
			Hello, you either have JavaScript turned off or an old version of Macromedia's Flash Player, <a href="http://archive.org/download/fp8_archive/fp8_archive.zip">click here</a> to get the latest flash player.
		</div>
        <script type="text/javascript">
			if(swfobject.hasFlashPlayerVersion("6")) {	
				swfobject.embedSWF("/player.swf?video_id=<?php echo $video['vid']; ?>&l=<?php echo ceil($video['time']); ?>", "flashcontent", "450", "370", "6");
			}  
            
            </script><script type="text/javascript">
            if(typeof(document.createElement('video').canPlayType) != 'undefined' && document.createElement('video').canPlayType('video/webm;codecs="vp8,opus"') == "probably") {
				document.getElementById('flashcontent').innerHTML = `<!-- player HTML begins here -->
        <div class="player" id="playerBox">
            <div class="mainContainer">
                <div class="playerScreen">
                    <div class="playbackArea">
                        <div class="videoContainer">
                            <video class="videoObject" id="video">
                                <source src="/get_video.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>&format=webm"> 
                             </video>
                        </div>
                    </div>
                  <div class="watermark">
                        <img src="/viewfinder/resource/watermark.png" height="35px">
                    </div>
                </div>
                <div class="controlBackground">
                    <div class="controlContainer">
                        <div class="lBtnContainer">
                            <div class="button" id="playButton">
                                <img src="/viewfinder/resource/play.png" id="playIcon">
                                <img src="/viewfinder/resource/pause.png" class="hidden" id="pauseIcon">
                            </div>
                        </div>
                        <div class="centerContainer">
                            <div class="seekbarElementContainer">
                                <progress class="seekProgress" id="seekProgress" value="0" min="0"></progress>
                            </div>
                            <div class="seekbarElementContainer">
                                <input class="seekHandle" id="seekHandle" value="0" min="0" step="1" type="range">
                            </div>
                        </div>
                        <div class="rBtnContainer">
                            <div class="button" id="muteButton">
                                <img src="/viewfinder/resource/mute.png" id="muteIcon">
                                <img src="/viewfinder/resource/unmute.png" class="hidden" id="unmuteIcon">
                            </div>
                        </div>
                    </div>
                </div>
                <img class="share" src="/viewfinder/embedded/share.png">
            </div>
            <div class="aboutBox hidden" id="aboutBox">
                <div class="aboutBoxContent">
                <div class="aboutHeader">Viewfinder-P</div>
                <div class="aboutBody">
                    <div>Version 1.0<br>
                    Embedded Player Edition
                    <br>
                    2005 HTML5 player<br>
                    <br>
                    by purpleblaze<br>
                    modified by jr<br>
                    for KamTape.com
                </div>
                </div>
                <button id="aboutCloseBtn">Close</button>
                </div>
            </div>
            <div class="contextMenu hidden" id="playerContextMenu">
                <div class="contextItem" id="contextMute">
                    <span>Mute</span>
                    <div id="muteTick" class="tick hidden">    
                    </div>
                </div>
                <div class="contextItem" id="contextLoop">
                    <span>Loop</span>
                    <div id="loopTick" class="tick hidden">
                    </div>
                </div>
                <div class="contextSeparator"></div>
                <div class="contextItem" id="contextAbout">About</div>
            </div>
        </div>
        
        <!-- here lies purple -->
				`;
			}
			</script>
         <script src="/viewfinder/player.js"></script>
		</div>
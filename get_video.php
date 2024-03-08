<?php
require "needed/scripts.php";
$video_info = $conn->prepare("SELECT * FROM videos WHERE vid = ? AND converted = 1");
$video_info->execute([$_GET['video_id']]);
if ($video_info->rowCount() == 0) {
 die();
} else {
    $video_info = $video_info->fetch(PDO::FETCH_ASSOC);
    $video_id = $video_info['vid'];
	if ($video_info['allow_embed'] == 0 && isset($_GET['eurl'])) {
		die();
	}
    if (isset($_GET['format']) && $_GET['format'] == "webm") {
        $getvideo = "http://v" . $video_info['cdn'] . ".kamtape.com/get_video.php?video_id=" . $video_id . "&format=webm";
    } else {
        $getvideo = "http://v" . $video_info['cdn'] . ".kamtape.com/get_video.php?video_id=" . $video_id . "&format=flv";
    }
    header('Location: ' . $getvideo);
    header('HTTP/1.1 200 OK');
}
?>



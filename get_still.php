<?php
require "needed/scripts.php";
if(!isset($_GET['still_id'])) $_GET['still_id'] = 2;
if(!isset($_GET['video_id'])) $_GET['video_id'] = false;
$video_info = $conn->prepare("SELECT * FROM videos WHERE vid = ? AND converted = 1");
$video_info->execute([$_GET['video_id']]);
if ($video_info->rowCount() == 0) {
$getvideo = "http://v14.kamtape.com/get_still.php?video_id=" . $_GET['video_id'] . "&still_id=". $_GET['still_id'];
    header('Location: ' . $getvideo);
    header('HTTP/1.1 200 OK');
} else {
    $video_info = $video_info->fetch(PDO::FETCH_ASSOC);
    $video_id = $video_info['vid'];
        $getvideo = "http://v" . $video_info['cdn'] . ".kamtape.com/get_still.php?video_id=" . $video_id . "&still_id=". $_GET['still_id'];
    header('Location: ' . $getvideo);
    header('HTTP/1.1 200 OK');
}
?>



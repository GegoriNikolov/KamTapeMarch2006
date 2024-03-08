<?php
require "needed/scripts.php";
if($_SESSION['uid'] == NULL) {
	header("Location: login.php");
}
ob_get_clean();

$video = $conn->prepare("SELECT uid FROM favorites WHERE vid = ?");
$video->execute([$_GET['video_id']]);
$video = $video->fetch(PDO::FETCH_ASSOC);

if($video['uid'] == $session['uid']) {
	$remove_video = $conn->prepare("DELETE FROM favorites WHERE uid = :uid AND vid = :vid");
	$remove_video->execute([
		":uid" => $session['uid'],
		":vid" => $_GET['video_id']
	]);
}

header("Location: my_favorites.php");
?>
<?php
require "needed/scripts.php";
if($_SESSION['uid'] == NULL) {
	header("Location: login.php");
}

$video = $conn->prepare("SELECT uid FROM videos WHERE vid = ?");
$video->execute([$_GET['video_id']]);
$video = $video->fetch(PDO::FETCH_ASSOC);
if($video['converted'] === "0") {
    header("Location: my_videos.php");
    exit;
}
if($video['uid'] == $session['uid']) {
	$remove_video = $conn->prepare("DELETE FROM videos WHERE uid = :uid AND vid = :vid");
	$remove_video->execute([
		":uid" => $session['uid'],
		":vid" => $_GET['video_id']
	]);
    $killitwithfire = $conn->prepare("DELETE FROM views WHERE views.vid = ?");
$killitwithfire->execute([$_GET['video_id']]);

$killitwithfire = $conn->prepare("DELETE FROM comments WHERE comments.vidon = ?");
$killitwithfire->execute([$_GET['video_id']]);

$killitwithfire = $conn->prepare("DELETE FROM favorites WHERE favorites.vid = ?");
$killitwithfire->execute([$_GET['video_id']]);

$killitwithfire = $conn->prepare("DELETE FROM ratings WHERE ratings.video = ?");
$killitwithfire->execute([$_GET['video_id']]);

}
redirect("/my_videos.php")
?>
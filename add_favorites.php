<?php
require "needed/start.php";
ob_get_clean();

force_login();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die();
}

// Check if the video in question exists.
$video_exists = $conn->prepare("SELECT vid FROM videos WHERE vid = :video_id AND converted = 1");
$video_exists->execute([
	":video_id" => $_GET['video_id']
]);

if($video_exists->rowCount() == 0) {
	header("Location: index.php", true, 401);
	die();
}

// Check if the user has already favorited this video.
$favorite_exists = $conn->prepare("SELECT fid FROM favorites WHERE uid = :member_id AND vid = :video_id");
$favorite_exists->execute([
	":member_id" => $session['uid'],
	":video_id" => $_GET['video_id']
]);

if($favorite_exists->rowCount() > 0) {
	die();
}

// Add it to favorites!
$add_to_favorites = $conn->prepare("INSERT INTO favorites (fid, uid, vid) VALUES (:favorite_id, :member_id, :video_id)");
$add_to_favorites->execute([
    ":favorite_id" => generateId(),
	":member_id" => $session['uid'],
	":video_id" => $_GET['video_id']
]);

?>
<?php
$comment = $conn->prepare("SELECT * FROM comments WHERE cid = ?");
$comment->execute([$_GET['c']]);
$comment = $comment->fetch(PDO::FETCH_ASSOC);

$video = $conn->prepare("SELECT * FROM videos WHERE vid = ?");
$video->execute([$comment['vidon']]);
$video = $video->fetch(PDO::FETCH_ASSOC);

$uploader = $conn->prepare("SELECT * FROM users WHERE uid = ?");
$uploader->execute([$video['uid']]);
$uploader = $uploader->fetch(PDO::FETCH_ASSOC);

if ($uploader['uid'] == $session['uid'] || $comment['uid'] == $session['uid']) {
    $remove_video = $conn->prepare("DELETE FROM comments WHERE cid = :cid");
    $remove_video->execute([
        ":cid" => $_GET['c']
    ]);
    redirect('watch.php?v=' . $comment['vidon']);
}
?>

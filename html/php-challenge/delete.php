<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id'])) {
	$id = $_REQUEST['id'];
	
	// 投稿を検査する
	$messages = $db->prepare('SELECT * FROM posts WHERE id=?');
	$messages->execute(array($id));
	$message = $messages->fetch();

	if ($message['member_id'] == $_SESSION['id']) {
		// 削除する
		$do_retweet = $db->query('DELETE r.* FROM retweet r, posts p WHERE r.retweeted_post_id=p.retweeted_post_id AND r.retweet_member_id=p.member_id');
		$del = $db->prepare('DELETE FROM posts WHERE id=?');
		$del->execute(array($id));

	}

}

header('Location: index.php'); exit();
?>

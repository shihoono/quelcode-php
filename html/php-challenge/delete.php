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
		$del = $db->prepare('DELETE FROM posts WHERE id=?');
		$del->execute(array(
			$id
		));

		$rtmsg_del = $db->prepare('DELETE FROM posts where retweeted_post_id=?');
		$rtmsg_del->execute(array(
			$_REQUEST['id']
		));

		$rt_del = $db->prepare('DELETE FROM retweet where retweeted_post_id=?');
		$rt_del->execute(array(
			$_REQUEST['id']
		));

		$li_del = $db->prepare('DELETE FROM likes where liked_post_id=?');
		$li_del->execute(array(
			$_REQUEST['id']
		));
	}

}

header('Location: index.php'); exit();
?>

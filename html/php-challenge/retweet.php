<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id'])) {

    // リツイート済みかチェックするための情報を取得
    $retweets = $db->prepare('SELECT COUNT(retweeted_post_id) AS retweet_cnt FROM (SELECT retweeted_post_id FROM retweet WHERE retweeted_post_id=? AND retweet_member_id=? GROUP BY retweeted_post_id) R');
    $retweets->execute(array(
        $_REQUEST['id'], //リツイートしようとしているメッセージid
        $_SESSION['id']  //リツイートしようとしているメンバーid
    ));
    $retweet = $retweets->fetch();

    //リツイートしようとしているメッセージを、すでにリツイートしていないかチェック
    if ($retweet['retweet_cnt'] == 0) {
        //リツイートする
        $do_retweet = $db->prepare('INSERT INTO retweet SET retweeted_post_id=?, retweet_member_id=?, created=NOW()');
        $do_retweet->execute(array(
            $_REQUEST['id'], 
            $_SESSION['id']  
        ));

        // $posts['id'] = $_REQUEST['id'];
        $do_retweet = $db->prepare('SELECT p.message FROM posts p, retweet r WHERE p.id=r.retweeted_post_id AND p.id=?');
        $do_retweet->execute(array($_REQUEST['id']));
        $do_retweets = $do_retweet->fetch();

        $members = $db->query('SELECT m.name FROM members m, posts p WHERE m.id=p.member_id');
	    $member = $members->fetch();
        $doneRt = ' RT from' .' ' .$member['name'];

        $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, retweeted_post_id=?, created=NOW()');
        $message->execute(array(
            $_SESSION['id'],
            $do_retweets['message'].$doneRt,
            $_REQUEST['id']
        ));
    }

    if ($retweet['retweet_cnt'] == 1) {
        //リツイートを解除する
        $do_retweet = $db->prepare('DELETE FROM retweet WHERE retweeted_post_id=? AND retweet_member_id=?');
        $do_retweet->execute(array(
            $_REQUEST['id'], 
            $_SESSION['id']  
        ));

        $posts['id'] = $_REQUEST['id'];
        $message = $db->prepare('DELETE FROM posts WHERE retweeted_post_id=?');
        $message->execute(array(
            $posts['id']
        ));
    }
}

header('Location: index.php');
exit();
?>
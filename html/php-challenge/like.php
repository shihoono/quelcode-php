<?php

session_start();
require('dbconnect.php');

if (isset($_SESSION['id'])) {

  // いいね済みかチェックするための情報を取得
  $likes = $db->prepare('SELECT COUNT(liked_post_id) AS liked_cnt FROM (SELECT liked_post_id FROM likes WHERE liked_post_id=? AND like_member_id=? GROUP BY liked_post_id) A');
  $likes->execute(array(
    $_REQUEST['id'], //like_post_id
    $_SESSION['id']  //like_member_id
  ));
  $like = $likes->fetch();
  

  $posts = $db->prepare('SELECT retweeted_post_id FROM posts WHERE id=?');
  $posts->execute(array(
      $_REQUEST['id']
  ));
  $post = $posts->fetch();

  // いいねしようとしているメッセージを、すでにいいねをしていないかチェック
  if ($like['liked_cnt'] == 0) {
    // いいねを登録する
        $doLike = $db->prepare('INSERT INTO likes SET liked_post_id=?, like_member_id=?, created=NOW()');
        $doLike->execute(array(
          $_REQUEST['id'], 
          $_SESSION['id']  
        )); 
  }

  // いいね取り消し

  //通常のメッセージのいいね取り消し
  if ($like['liked_cnt'] == 1) {
        $doLike = $db->prepare('DELETE FROM likes WHERE liked_post_id=? AND like_member_id=?');
        $doLike->execute(array(
          $_REQUEST['id'], 
          $_SESSION['id']  
        ));
  }
}

header('Location: index.php');
exit();

?>

<?php
ob_start();
extract($params);
foreach($comments as $comment) {
  if($comment->getId() == (int) $commentId){
?>
<div  class="container rounded row commentBox col-10 col-lg-9 justify-content-between" style="border:1px solid blue; margin:1em auto; padding:0" id="comment<?= $comment->getId() ?>">
  <div class="container-liquid author col-2 row align-items-center">
    <div class="container-liquid commentAvatar justify-content-center col-6 offset-3">
      <?php
      if($comment->getAuthorId()) {
      $myAvatar = $avatarList[$comment->getAuthorId()];
      } else {
        $myAvatar = 'default.png';
      }
      ?>
        <img class="rounded-circle" src="<?= HOST ?>public/images/avatar/<?= $myAvatar ?>" alt="avatar user" width="50px" height="50px" style="border:1px solid blue">
    </div>
    <div class="container col-6 offset-3">
      <p><strong><?= $comment->getAuthor() ?></strong></p>
    </div>
  </div>
  <div class="container-liquid comment col-10">
    <div class="container-liquid row justify-content-end">
      <p class="date-time"> <i class="far fa-clock"></i> le <?= $comment->getDateFr() ?></p>
    </div>
    <form method="post" action="<?= HOST ?>commentUpdate" class="row align-items-start">
      <textarea name="updated" rows="2" cols="80"><?= nl2br(htmlspecialchars($comment->getComment())) ?></textarea>
      <input type="hidden" name="commentId" value="<?= $comment->getId() ?>">

      <button type="submit" class="btn-success rounded" style="border:none;padding:0.8em;margin-left:0.5em"><i class="far fa-check-circle"></i></button>
      <a href="<?= HOST ?>post/id/<?= $post->getId().'#comment'.$comment->getId() ?>" class="btn-danger rounded" style="padding:0.8em;margin-left:0.5em"><i class="far fa-times-circle"></i></a>
    </form>
    <hr/ style="margin-bottom:0">
    <div class="container-liquid row options ">
      <div class="container-liquid col-12 row justify-content-end" style="padding:0">
        <form action="<?= HOST ?>delete" method="post" class="container row justify-content-end col-6" style="padding:0">
          <input type="hidden" name="postId" value="<?= $post->getId() ?>">
          <input type="hidden" name="commId" value="<?= $comment->getId() ?>">
          <button type="submit" name="effacer" class="container col-5 text-primary bg-white option effacer"  style="margin:0;border:none;cursor:pointer">
            <i class="fas fa-trash-alt col-9"></i>
            <span>Effacer</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
} }
$editCommentView = ob_get_clean();
require('postView.php');
?>

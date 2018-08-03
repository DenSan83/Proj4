<?php
extract($params); // $params = array($post,$comments,$avatarList)
$title = $post->getTitle();
$postId = $post->getId();

ob_start();
$siteKey = '6LeVFmQUAAAAAGSSMYlzvv-GvhyxhKNymbAAxtWe'; // captcha: clé publique ?>
<div class="rounded container news" style="border:1px solid black; margin:1em auto;">
  <div class="container-liquid row justify-content-between bg-dark text-white" style="padding:0.5em">
    <div class="container row align-items-start col-4">
      <h3> <?= htmlspecialchars($post->getTitle()) ?> </h3>
    </div>
    <div class="container row align-items-end col-3">
      <em>le <?= htmlspecialchars($post->getDateFr()) ?></em>
    </div>
  </div>
    <p> <?= nl2br(htmlspecialchars($post->getContent())) ?> </p>
</div>

<h2>Commentaires</h2>
<?php
if (isset($_SESSION['comment']['success'])){
?>
<div class="container rounded text-white bg-success col-4" style="text-align:center;padding:1em">
  <span><i class="fas fa-check-circle"></i>&nbsp;&nbsp;</span><span>Votre commentaire à été ajouté !</span>
</div>
<?php
}
if(empty($comments[0]))
{
?>
<div class="container rounded bg-info text-white justify-content-center" style="height:3em;margin:2em auto">
  <p class="col-5" style="margin:1em auto">Ce post ne contient pas encore des commentaires.</p>
</div>
<?php
} else {
  foreach ($comments as $comment)
  {
    if (isset($commentId) && $comment->getId() == $commentId) // when modifyComment($commentId)
    {
      // modifier commentaire
      $editing = $comment;
      echo $editCommentView;
    } else {
      // montrer commentaire
?>
<div class="container rounded row commentBox col-10 col-lg-9 justify-content-between" style="border:1px solid blue; margin:1em auto; padding:0" id="comment<?= $comment->getId() ?>">
  <div class="container-liquid author col-2 row align-items-center" >
    <div class="container-liquid commentAvatar col-4 offset-3">
      <?php
      if($comment->getAuthorId()) {
      $myAvatar = $avatarList[$comment->getAuthorId()];
      } else {
        $myAvatar = $avatarList[0];
        $myAvatar['pseudo'] = $comment->getAuthor();
      }
      ?>
      <img class="rounded-circle" src="<?= HOST ?>public/images/avatar/<?= $myAvatar['avatar'] ?>" alt="avatar user" width="50px" height="50px" style="border:1px solid blue; margin: 0 auto">
    </div>
    <div class="container col-6 offset-3 align-items-center">
      <p><strong><?= htmlspecialchars($myAvatar['pseudo']) ?></strong></p>
      <p><?= htmlspecialchars($myAvatar['status']) ?></p>
    </div>
  </div>
  <div class="container-liquid comment align-self-end col-10">
    <div class="container-liquid row justify-content-end" style="padding:0.5em 1em">
      <p class="date-time"> <i class="far fa-clock"></i> le <?= htmlspecialchars($comment->getDateFr()) ?></p>
    </div>
    <p><?=  nl2br(htmlspecialchars($comment->getComment())) ?></p>
    <hr/ style="margin-bottom:0">
    <div class="container-liquid row options" style="padding:0.2em;min-height:3.5em">
    <?php
    if (!empty($_SESSION['user_session']) && $_SESSION['user_session']['user_id'] == $comment->getAuthorId()) {
    ?>
      <div class="container-liquid col-12 row justify-content-end" style="padding:0">
        <form action="<?= HOST ?>modifyComment#comment<?= $comment->getId()?>" method="post"class="container row justify-content-end col-6" style="padding:0">
          <input type="hidden" name="postId" value="<?= $post->getId() ?>">
          <input type="hidden" name="commentId" value="<?= $comment->getId() ?>">
          <button type="submit" class="container col-5 text-primary bg-white option modify" style="margin:0;border:none;cursor:pointer">
            <i class="fas fa-edit col-7"></i>
            <span class="col-7">Modifier</span>
          </button>
        </form>
      </div>
      <?php
      }
      elseif (!empty($_SESSION['user_session']['user_pseudo'])){
      ?>
      <div class="container-liquid col-12 row justify-content-end" style="padding:0">
        <form action="<?= HOST ?>flagComment" method="post"class="container row justify-content-end col-6" style="padding:0">
          <input type="hidden" name="postId" value="<?= $post->getId() ?>">
          <input type="hidden" name="commentId" value="<?= $comment->getId() ?>">
          <button type="submit" class="container col-5 text-primary bg-white option modify" style="margin:0;border:none;cursor:pointer">
            <i class="fas fa-fire col-7"></i>
            <span class="col-7">Signaler</span>
          </button>
        </form>
        <?php
        if(isset($_SESSION['flagged']) && $_SESSION['flagged'] == $comment->getId()){
        ?>
        <div class="flagged">
            <p>Le commentaire a bien été signalé.</p>
        </div>
        <?php
        }
        ?>
      </div>
      <?php
      }
      ?>
    </div>
  </div>
</div>
<?php
    }
  }
}
//ajouter commentaire
include('addCommentView.php');

$content = ob_get_clean();
require('template.php'); ?>

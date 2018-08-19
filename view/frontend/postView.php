<?php
$superTitle = $post->getTitle();
$siteKey = '6LeVFmQUAAAAAGSSMYlzvv-GvhyxhKNymbAAxtWe'; // captcha: clé publique
?>
<div id="myPost" class="container rounded news">
  <div class="container-liquid">
    <img src="<?=HOST.'public/images/post/'.$post->getImage() ?>" alt="image du post <?=$post->getId()?>">
  </div>
  <div class="container-liquid row justify-content-between align-items-center bg-dark text-white">
    <h3 class="container row align-items-start col-xs-8 col-sm-6"> <?= htmlspecialchars($post->getTitle()) ?> </h3>
    <em class="container row justify-content-end col-xs-6 col-sm-5">le <?= $post->getCreationDate() ?></em>
  </div>
  <div>
    <p> <?= htmlspecialchars_decode($post->getContent()) ?> </p>
  </div>
</div>
<div class="container">
  <h2>Commentaires</h2>
</div>
<?php
if (isset($_SESSION['comment']['success'])){
?>
<div class="modal fade" id="overlay">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h4 class="modal-title">Succès !</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <p><span><i class="fas fa-check-circle text-success"></i>&nbsp;&nbsp;</span><span>Votre commentaire à été ajouté !</span></p>
      </div>
    </div>
  </div>
</div>
<?php
}
if(empty($comments[0]))
{
?>
<div id="noComs" class="container rounded bg-info text-white justify-content-center align-items-center">
  <p class="col-8">Ce post ne contient pas encore des commentaires.</p>
</div>
<?php
} else {
  foreach ($comments as $comment)
  {
    if (isset($commentId) && $comment->getId() == $commentId) // when modifyComment($commentId)
    { // modifier commentaire
      require('editCommentView.php');
    } else {
      // montrer commentaire
?>
<div class="container rounded row col-xs-12 col-sm-10 col-lg-9 justify-content-between commentBox" id="comment<?= $comment->getId() ?>">
  <div class="container-liquid col-xs-6 col-sm-2 row align-items-center author" >
    <div class="container-liquid col-12 commentAvatar">
      <?php
      if($comment->getAuthorId()) {
      $myAvatar = $avatarList[$comment->getAuthorId()];
      } else {
        $myAvatar = $avatarList[0];
        $myAvatar['pseudo'] = $comment->getAuthor();
      }
      ?>
      <img class="rounded-circle" src="<?= HOST ?>public/images/avatar/<?= $myAvatar['avatar'] ?>" alt="user avatar">
    </div>
    <div class="container col-12 align-items-center userIds">
      <p><strong><?= htmlspecialchars($myAvatar['pseudo']) ?></strong></p>
      <p><?= htmlspecialchars($myAvatar['status']) ?></p>
    </div>
  </div>
  <div class="container-liquid col-xs-12 col-sm-10 commentText">
    <div class="container-liquid">
      <p class="date-time"> <i class="far fa-clock"></i> le <?= $comment->getDateCom() ?></p>
    </div>
    <p><?= nl2br(htmlspecialchars($comment->getComment())) ?></p>
    <hr/>
    <div class="container-liquid row choix">
    <?php
    if (!empty($_SESSION['user_session']) && $_SESSION['user_session']['user_id'] == $comment->getAuthorId()) {
    ?>
      <div class="container-liquid col-12 row justify-content-end modifier">
        <form action="<?= HOST ?>modifyComment#comment<?= $comment->getId()?>" method="post"class="container row justify-content-end col-5">
          <input type="hidden" name="postId" value="<?= $post->getId() ?>">
          <input type="hidden" name="commentId" value="<?= $comment->getId() ?>">
          <button type="submit" class="container col-8 text-primary bg-white modify">
            <i class="fas fa-edit col-7"></i>
            <span class="col-7">Modifier</span>
          </button>
        </form>
      </div>
      <?php
      }
      elseif (!empty($_SESSION['user_session']['user_pseudo'])){
      ?>
      <div class="container-liquid col-12 row justify-content-end">
        <?php
        if(isset($_SESSION['comment']['flag']) && $_SESSION['comment']['flag'] == $comment->getId()){
        ?>
        <div class="container bg-success col-8 rounded row align-items-center text-white timed flagged">
          <span><i class="far fa-check-circle"></i> Le commentaire a bien été signalé</span>
        </div>
        <?php
        }
        ?>
        <form action="<?= HOST ?>flagComment" method="post"class="container row justify-content-end col-5 signaler">
          <input type="hidden" name="postId" value="<?= $post->getId() ?>">
          <input type="hidden" name="commentId" value="<?= $comment->getId() ?>">
          <button type="submit" class="container col-5 text-primary bg-white flag">
            <i class="fas fa-fire col-7"></i>
            <span class="col-7">Signaler</span>
          </button>
        </form>
      </div>
      <?php
      } else {
      ?>
      <div class="container liquid offline">
        <p>Pour avoir accès aux options des commentaires, <a href="<?=HOST.'newUser'?>">abonnez-vous !</a></p>
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
//ajouter commentaires
require('addCommentView.php');
?>

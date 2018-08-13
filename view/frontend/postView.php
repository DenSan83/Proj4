<?php
extract($params); // $params = array($post,$comments,$avatarList)
$title = $post->getTitle();

ob_start();
$siteKey = '6LeVFmQUAAAAAGSSMYlzvv-GvhyxhKNymbAAxtWe'; // captcha: clé publique ?>
<div class="container rounded news" style="border:1px solid black; margin:2em auto;margin-top:12em;padding:0;overflow:hidden">
  <div class="container-liquid">
    <img src="<?=HOST.'public/images/post/'.$post->getImage() ?>" alt="" style="width:100%">
  </div>
  <div class="container-liquid row justify-content-between align-items-center bg-dark text-white" style="padding:1em">
    <h3 class="container row align-items-start col-4" style="margin-left:1em;padding:0"> <?= htmlspecialchars($post->getTitle()) ?> </h3>
    <em class="container row justify-content-end col-3" style="margin-right:1em;padding:0">le <?= $post->getCreationDate() ?></em>
  </div>
  <div style="padding:1em">
    <p> <?= htmlspecialchars_decode($post->getContent()) ?> </p>
  </div>
</div>
<div class="container">
  <h2>Commentaires</h2>
</div>
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
<div class="container rounded bg-info text-white justify-content-center align-items-center" style="margin:2em auto;padding:0.1em">
  <p class="col-5" style="margin:1em auto">Ce post ne contient pas encore des commentaires.</p>
</div>
<?php
} else {
  foreach ($comments as $comment)
  {
    if (isset($commentId) && $comment->getId() == $commentId) // when modifyComment($commentId)
    { // modifier commentaire
      $editing = $comment;
      echo $editCommentView;
    } else {
      // montrer commentaire
?>
<div class="container rounded row commentBox col-10 col-lg-9 justify-content-between" style="border:1px solid blue; margin:1em auto; padding:0" id="comment<?= $comment->getId() ?>">
  <div class="container-liquid author col-2 row align-items-center" >
    <div class="container-liquid commentAvatar col-12" style="text-align:center;margin-top:0.5em">
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
    <div class="container col-12 align-items-center">
      <p style="text-align:center;margin-bottom:0"><strong><?= htmlspecialchars($myAvatar['pseudo']) ?></strong></p>
      <p style="text-align:center"><?= htmlspecialchars($myAvatar['status']) ?></p>
    </div>
  </div>
  <div class="container-liquid comment align-self-end col-10">
    <div class="container-liquid row justify-content-end" style="padding:0.5em 1em">
      <p class="date-time"> <i class="far fa-clock"></i> le <?= $comment->getDateCom() ?></p>
    </div>
    <p><?=  nl2br(htmlspecialchars($comment->getComment()));var_dump(strlen($comment->getComment())); ?></p>
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
      } else {
      ?>
      <div class="container liquid" style="justify-content:right">
        <p style="text-align:right;color:blue">Pour avoir accès aux options des commentaires, <a href="<?=HOST.'newUser'?>">abonnez-vous !</a></p>
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

<?php
extract($params);
$title = $post->getTitle();
$postId = $post->getId();
?>

<?php ob_start(); ?>
<?php
$siteKey = '6LeVFmQUAAAAAGSSMYlzvv-GvhyxhKNymbAAxtWe'; // votre clé publique
?>
<div class="rounded container news" style="border:1px solid black; margin:1em auto;">
  <div class="container-liquid row justify-content-between bg-dark text-white">
    <div class="container row align-items-start col-4">
      <h3> <?= $post->getTitle() ?> </h3>
    </div>
    <div class="container row align-items-end col-3">
      <em>le <?= $post->getCreationDate() ?></em>
    </div>
  </div>
    <p> <?= $post->getContent() ?> </p>
</div>

<h2>Commentaires</h2>
<?php
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
    if (isset($commentId) && $comment->getId() == $commentId)
    {
      // modifier commentaire
      $editing = $comment;
      echo $editCommentView;
    } else {
      // montrer commentaire
?>
<div class="container rounded row commentBox col-10 col-lg-9" style="border:1px solid blue; margin:1em auto; padding:0" id="comment<?= $comment->getId() ?>">
  <div class="container-liquid author col-2" style="margin-top:1em;padding:0">
    <div class="commentAvatar">
      <?php
      if($comment->getAuthorId()) {
      $avatarCheck = new LoginManager();
      $authorId = $comment->getAuthorId();
      $myAvatar = $avatarCheck->getAvatar($authorId);
      } else {
        $myAvatar = 'default.png';
      }
      ?>
        <img class="rounded-circle" src="<?= HOST ?>public/images/avatar/<?= $myAvatar ?>" alt="avatar user" width="50px" height="50px" style="border:1px solid blue">
    </div>
      <p><strong><?= $comment->getAuthor() ?></strong></p>
  </div>
  <div class="container-liquid comment col-10">
    <div class="container-liquid row justify-content-end">
      <p class="date-time"> <i class="far fa-clock"></i> le <?= $comment->getDateFr() ?></p>
    </div>
    <p><?= $comment->getComment() ?></p>
    <hr/ style="margin-bottom:0">
    <div class="container-liquid row options ">
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
    } else {
      ?>
      <div class="container-liquid row justify-content-center">
        <a href="#">
          <div class="container-liquid justify-content-center col-3 option signaler">
            <i class="fas fa-fire"></i>
            <span>Signaler</span>
          </div>
        </a>
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
?>
<div id="optionsSession" class="container-liquid justify-content-center row bg-secondary" >
  <div class="offLine">
    <?php
    if (empty($_SESSION['user_session'])){
    ?>
    <form action="<?= HOST ?>addComment/id/<?= $post->getId() ?>" method="post" style="margin:1em auto">
      <h4>Ajouter un commentaire sans connexion :</h4>
      <input type="hidden" name="postId" value="<?= $post->getId() ?>">
      <div class="form-group">
        <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
      </div>
      <div class="form-group">
        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prenom" required>
      </div>

      <div class="form-group">
        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Commentaire" required></textarea>
      </div>

      <div class="container-liquid">
        <div class="g-recaptcha col-7 row justify-content-center" data-sitekey="<?php echo $siteKey; ?>" style="margin:0.5em auto; width:305px"></div>
        <?php if(isset($noCaptcha) && $noCaptcha == 1) { ?>
        <div class="noCaptcha bg-danger text-white col-9 rounded row justify-content-center" style="padding:1em; margin:0.5em auto">
           <i class="fas fa-exclamation-triangle"></i><span> Veuillez completer le Captcha ! </span>
        </div>
        <?php } ?>
        <div class="row justify-content-center">
            <button type="submit" class="btn btn-primary col-8">Submit</button>
        </div>
      </div>

    </form>
    <?php
    } else {
    ?>
    <form action="<?= HOST ?>onlineComment/id/<?= $post->getId() ?>" method="post">
      <h4>Ajouter un message en tant que <span><strong><?= $_SESSION['user_session']['user_pseudo'] ?></strong></span> :</h4>
      <input type="hidden" name="postId" value="<?= $post->getId() ?>">
      <input type="hidden" name="authorId" value="<?= $_SESSION['user_session']['user_id'] ?>">
      <input type="hidden" id="pseudo" name="pseudo" value="<?= $_SESSION['user_session']['user_pseudo'] ?>" required/>

      <label for="comment">Commentaire</label><br />
      <textarea id="comment" name="comment" cols= "35" rows="3" required></textarea><br/><br/>
      <input type="submit" id="submitOnline" /> <br/>
    </form>

    <?php
    }
    ?>
  </div>

  <div class="offLineSp">

  </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>

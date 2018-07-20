<?php
extract($params);
$title = $post->getTitle();
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
while ($comment = $comments->fetch())
{
  if (isset($commId) && $comment['id'] == $commId){
    // modifier commentaire
  ?>
  <div class="commentBox" id="<?php echo $commId?>">
    <div class="author">
      <div class="commentAvatar">
        <?php
        $avatarCheck = new LoginManager;
        $myAvatar = $avatarCheck->getAvatar($comment['author']);
        if($myAvatar['avatar'] !== null ){
        ?>
          <img class="rounded-circle" src="<?= HOST ?>public/images/avatar/<?= $myAvatar['avatar'] ?>" alt="avatar user" width="50px" height="50px">
        <?php
        } else {
        ?>
          <img class="rounded-circle" src="<?= HOST ?>public/images/avatar/default.png" alt="avatar user" width="50px" height="50px">
        <?php
        }
        ?>
      </div>
        <p><strong><?= htmlspecialchars($comment['author']) ?></strong></p>
    </div>
    <div class="comment">
      <p class="date-time"> <i class="far fa-clock"></i> le <?= $comment['comment_date_fr'] ?></p>
      <form method="post" action="<?= HOST ?>commentUpdate">
        <textarea name="updated" rows="2" cols="40"><?= nl2br(htmlspecialchars($comment['comment'])) ?></textarea>
        <input type="hidden" name="commentId" value="<?= $comment['id'] ?>">

        <button type="submit"><i class="far fa-check-circle"></i></button>
        <a href="<?= HOST ?>post/id/<?= $post['id'] ?>"><i class="far fa-times-circle"></i></a>
      </form>
      <hr/>
      <div class="options">
        <form action="<?= HOST ?>delete" method="post">
          <input type="hidden" name="postId" value="<?= $post['id'] ?>">
          <input type="hidden" name="commId" value="<?= $comment['id'] ?>">
          <button type="submit" name="effacer" class="option effacer">
            <i class="fas fa-trash-alt"></i>
            <span>Effacer</span>
          </button>
        </form>
      </div>
    </div>
  </div>
<?php
  } else {
  // montrer commentaire
?>
<div class="container rounded row commentBox col-10 col-lg-9" style="border:1px solid blue; margin:1em auto">
  <div class="container author col-3 justify-content-center">
    <div class="commentAvatar">
      <?php
      $avatarCheck = new LoginManager;
      $myAvatar = $avatarCheck->getAvatar($comment['author']);
      if($myAvatar['avatar'] !== null ){
      ?>
        <img class="rounded-circle" src="<?= HOST ?>public/images/avatar/<?= $myAvatar['avatar'] ?>" alt="avatar user" width="50px" height="50px">
      <?php
      } else {
      ?>
        <img class="rounded-circle" src="<?= HOST ?>public/images/avatar/default.png" alt="avatar user" width="50px" height="50px">
      <?php
      }
      ?>
    </div>
      <p><strong><?= htmlspecialchars($comment['author']) ?></strong></p>
  </div>
  <div class="container-liquid comment col-8">
    <div class="container-liquid row justify-content-end">
      <p class="date-time"> <i class="far fa-clock"></i> le <?= $comment['comment_date_fr'] ?></p>
    </div>
    <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
    <hr/>
    <div class="container-liquid row justify-contend-end options col-3">
      <?php
      if (!empty($user) && $comment['author'] == $_SESSION['user_session']['user_pseudo'])
      {
      ?>
      <div class="container col-3">
        <form action="<?= HOST ?>modifyComment" method="post">
          <input type="hidden" name="postId" value="<?= $post['id'] ?>">
          <input type="hidden" name="commId" value="<?= $comment['id'] ?>">
          <button type="submit" name="button" class="option modify">
            <i class="fas fa-edit"></i>
            <span>Modifier</span>
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
      <!-- <div class="container-liquid row col-3">
        <a href="#">
          <div class="option repondre">
            <i class="fas fa-reply"></i>
            <span>Répondre</span>
          </div>
        </a>
      </div> -->
    </div>
  </div>
</div>
<?php
  }
}
//ajouter commentaire
?>
<div id="optionsSession" class="container-liquid justify-content-center row bg-secondary" >
  <div class="offLine">
    <?php
    if (empty($user)){
    ?>
    <form action="<?= HOST ?>addComment/id/<?= $post->getId() ?>" method="post" style="margin:1em auto">
      <h4>Ajouter un commentaire sans connexion :</h4>
      <input type="hidden" name="postId" value="<?= $post->getId() ?>">
      <div class="form-group">
        <!-- <label for="exampleFormControlInput1">Nom</label> -->
        <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
      </div>
      <div class="form-group">
        <!-- <label for="exampleFormControlInput1">Prenom</label> -->
        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prenom" required>
      </div>

      <div class="form-group">
        <!-- <label for="exampleFormControlTextarea1">Commentaire</label> -->
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
    <form action="<?= HOST ?>onlineComment/id/<?= $post['id'] ?>" method="post">
      <h4>Ajouter un message en tant que <span><strong><?= $_SESSION['user_session']['user_pseudo'] ?></strong></span> :</h4>
      <input type="hidden" name="postId" value="<?= $post['id'] ?>">
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

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
        <div class="g-recaptcha col-7 row justify-content-center" data-sitekey="<?= $siteKey ?>" style="margin:0.5em auto; width:305px"></div>
        <?php
        if(isset($_SESSION['comment']['error'])) { ?>
        <div class="noCaptcha bg-danger text-white col-9 rounded row justify-content-center" style="padding:1em; margin:0.5em auto">
          <?php
          foreach($_SESSION['comment']['error'] as $eKey => $error){
          ?>
          <span><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;</span><span><?= htmlspecialchars($error) ?></span>
          <?php
          }
          ?>
        </div>
        <?php } ?>
        <div class="row justify-content-center">
          <button type="submit" class="btn btn-primary col-8">Envoyer</button>
        </div>
      </div>

    </form>
    <?php
    } else {
    ?>
    <form action="<?= HOST ?>onlineComment/id/<?= $post->getId() ?>" method="post" style="margin:1em auto">
      <h4>Ajouter un message en tant que <span class="text-primary"><strong><?= $_SESSION['user_session']['user_pseudo'] ?></strong></span> :</h4>
      <input type="hidden" name="postId" value="<?= $post->getId() ?>">
      <input type="hidden" name="authorId" value="<?= $_SESSION['user_session']['user_id'] ?>">
      <input type="hidden" id="pseudo" name="pseudo" value="<?= $_SESSION['user_session']['user_pseudo'] ?>" required/>

      <div class="form-group">
        <textarea class="form-control" id="comment" name="comment" cols= "35" rows="3" placeholder="Commentaire" required></textarea>
      </div>
      <div class="row justify-content-center">
          <button type="submit" id="submitOnline" class="btn btn-primary col-8">Envoyer</button>
      </div>
    </form>
    <?php
    if (isset($_SESSION['comment']['error'])){
    ?>
    <div class="container rounded text-white bg-danger col-8" style="text-align:center; margin-bottom:1em">
      <span><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;</span> <span><?= $_SESSION['comment']['error'] ?></span>
    </div>
    <?php
      }
    }
    ?>
  </div>
</div>

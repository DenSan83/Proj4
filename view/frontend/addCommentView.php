<div id="optionsSession" class="container-liquid justify-content-center row bg-secondary" >
  <div>
    <?php
    if (empty($_SESSION['user_session'])){
    ?>
    <form action="<?= HOST ?>addComment/id/<?= $post->getId() ?>" method="post" id="offline">
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

      <div class="container-liquid captchaDiv">
        <div class="g-recaptcha col-7 row justify-content-center" data-sitekey="<?= $siteKey ?>">
        </div>
        <?php
        if(isset($_SESSION['comment']['error'])) {
        ?>
        <div class="modal fade" id="overlay">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h4 class="modal-title">Erreur !</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                <?php
                foreach($_SESSION['comment']['error'] as $eKey => $error){
                ?>
                <p><span><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;</span>
                <span><?= htmlspecialchars($error) ?></span></p>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
        </div>
        <?php
        }
        ?>
        <div class="row justify-content-center">
          <button type="submit" class="btn btn-primary col-8">Envoyer</button>
        </div>
      </div>

    </form>
    <?php
    } else {
    ?>
    <form action="<?= HOST ?>onlineComment/id/<?= $post->getId() ?>" method="post" id="onLine">
      <h4>Ajouter un message en tant que<span id="userCom"><br/></span> <span class="text-primary"><strong><?= $_SESSION['user_session']['user_pseudo'] ?></strong></span> :</h4>
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
    }
    ?>
  </div>
</div>

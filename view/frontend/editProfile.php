<?php
$superTitle = 'Modifier mon profil';
?>
<div id="editProfile" class="container row col-9 bg-secondary rounded justify-content-center">
  <form method="post" action="<?= HOST ?>updateProfile" enctype="multipart/form-data" class="form-horizontal col-10 justify-content-center" role="form">
    <h2>Modifier mon profil</h2>

    <div class="form-group">
      <div class="container-liquid avatar">
        <img class="rounded-circle" id="avatar" src="<?= HOST ?>public/images/avatar/<?= $_SESSION['user_session']['user_avatar'] ?>" alt="Avatar de <?= $_SESSION['user_session']['user_pseudo'] ?>">
        <label for="avatar" class="col-sm-3 control-label">Changer avatar* :</label>
        <div class="col-sm-12">
          <input type="file" name="avatar" class="bg-light rounded form-control-file">
        </div>
      </div>
      <?php
      if (isset($_SESSION['error']['errAvatar'])){
        foreach ($_SESSION['error']['errAvatar'] as $errAvatar) {
      ?>
      <div class="container bg-danger col-6 text-white rounded">
        <p><?= $errAvatar ?></p>
      </div>
      <?php
      }}
      ?>
    </div>

    <div class="form-group">
      <label for="pseudo" class="col-sm-6 control-label">Changer pseudo :</label>
      <div class="col-sm-12">
        <input type="text" id="pseudo" placeholder="Pseudo" class="form-control" name="pseudo" value="<?= $_SESSION['user_session']['user_pseudo'] ?>" autofocus>
      </div>
      <?php
      if (isset($_SESSION['error']['errPseudo'])){
      ?>
      <div class="container bg-danger col-6 text-white rounded">
        <p><?= htmlspecialchars($_SESSION['error']['errPseudo']) ?></p>
      </div>
      <?php
      }
      ?>
    </div>

    <div class="form-group">
      <label for="email" class="col-sm-6 control-label">Changer email :</label>
        <div class="col-sm-12">
          <input type="email" id="email" placeholder="Email" class="form-control" name= "email" value="<?= $_SESSION['user_session']['user_email'] ?>">
        </div>
        <?php
        if (isset($_SESSION['error']['errEmail'])){
        ?>
        <div class="container bg-danger col-6 text-white rounded">
          <p><?= htmlspecialchars($_SESSION['error']['errEmail']) ?></p>
        </div>
        <?php
        }
        ?>
    </div>

    <div class="form-group">
      <label for="password" class="col-sm-6 control-label">Changer mot de passe :</label>
      <div class="col-sm-12">
        <input type="password" id="password" placeholder="Mot de passe (au moins 6 caractères)" class="form-control" name="password">
      </div>
      <?php
      if (isset($_SESSION['error']['errPassword'])){
      ?>
      <div class="container bg-danger col-6 text-white rounded">
        <p><?= htmlspecialchars($_SESSION['error']['errPassword']) ?></p>
      </div>
      <?php
      }
      ?>
    </div>
    <div class="form-group">
      <label for="password2" class="col-sm-6 control-label">Confirmer mot de passe :</label>
      <div class="col-sm-12">
        <input type="password" id="password2" placeholder="Confirmer mot de passe" class="form-control" name="password2">
      </div>
      <?php
      if (isset($_SESSION['error']['errPassword2'])){
      ?>
      <div class="container bg-danger col-6 text-white rounded">
        <p><?= htmlspecialchars($_SESSION['error']['errPassword2']) ?></p>
      </div>
      <?php
      }
      ?>
    </div>

    <div class="form-group">
      <div class="col-sm-12 col-sm-offset-3">
        <span class="help-block">* = L'image de profil doit être en format jpg, jpeg, gif ou png, et la taille ne doit pas depasser 2Mo.</span>
      </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block col-12 maj">Mise à jour</button>
    <a href="<?=HOST?>" id="backHome">
      <div class="btn btn-light btn-block col-12">
        Retour à l'accueil
      </div>
    </a>
  </form>
  <?php
  if(isset($_SESSION['error']['errClear'])){
  ?>
  <div class="modal fade" id="overlay">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h4 class="modal-title">Profil mis à jour</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <p><i class="far fa-check-circle text-success"></i> <?= htmlspecialchars($_SESSION['error']['errClear']) ?></p>
        </div>
      </div>
    </div>
  </div>
  <?php
  }
  ?>
</div>

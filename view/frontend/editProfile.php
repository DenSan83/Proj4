<?php
$superTitle = 'Modifier mon profil';
?>
<div class="container row col-9 bg-secondary rounded justify-content-center" style="margin:2em auto;margin-top:12em;padding:2em">
  <form method="post" action="<?= HOST ?>updateProfile" enctype="multipart/form-data" class="form-horizontal col-10 justify-content-center" role="form">
    <h2 style="margin:0.5em auto;text-align:center">Modifier mon profil</h2>

    <div class="form-group">
      <div class="container-liquid avatar">
        <img class="rounded-circle" id="avatar" src="<?= HOST ?>public/images/avatar/<?= $_SESSION['user_session']['user_avatar'] ?>" alt="Avatar de <?= $_SESSION['user_session']['user_pseudo'] ?>" width="200px" height="200px" style="border:2px solid blue;display:block;margin:0 auto">
        <label for="avatar" class="col-sm-3 control-label">Changer avatar* :</label>
        <div class="col-sm-12">
            <input type="file" name="avatar" class="bg-light rounded form-control-file" style="margin:5px auto">
        </div>
      </div>
      <?php
      if (isset($_SESSION['update_err']['errAvatar'])){
      ?>
      <div class="container bg-danger col-6 text-white rounded" style="margin-top:0.5em">
        <p style="margin:0.5em auto;text-align:center"><?= htmlspecialchars($_SESSION['update_err']['errAvatar']) ?></p>
      </div>
      <?php
      }
      ?>
    </div>

    <div class="form-group">
      <label for="pseudo" class="col-sm-6 control-label">Changer pseudo :</label>
      <div class="col-sm-12">
        <input type="text" id="pseudo" placeholder="Pseudo" class="form-control" name="pseudo" value="<?= $_SESSION['user_session']['user_pseudo'] ?>" autofocus>
      </div>
      <?php
      if (isset($_SESSION['update_err']['errPseudo'])){
      ?>
      <div class="container bg-danger col-6 text-white rounded" style="margin-top:0.5em">
        <p style="margin:0.5em auto;text-align:center"><?= htmlspecialchars($_SESSION['update_err']['errPseudo']) ?></p>
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
        if (isset($_SESSION['update_err']['errEmail'])){
        ?>
        <div class="container bg-danger col-6 text-white rounded" style="margin-top:0.5em">
          <p style="margin:0.5em auto;text-align:center"><?= htmlspecialchars($_SESSION['update_err']['errEmail']) ?></p>
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
      if (isset($_SESSION['update_err']['errPassword'])){
      ?>
      <div class="container bg-danger col-6 text-white rounded" style="margin-top:0.5em">
        <p style="margin:0.5em auto;text-align:center"><?= htmlspecialchars($_SESSION['update_err']['errPassword']) ?></p>
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
      if (isset($_SESSION['update_err']['errPassword2'])){
      ?>
      <div class="container bg-danger col-6 text-white rounded" style="margin-top:0.5em">
        <p style="margin:0.5em auto;text-align:center"><?= htmlspecialchars($_SESSION['update_err']['errPassword2']) ?></p>
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
    <a href="<?= HOST ?>" style="text-decoration:none">
      <div class="btn btn-light btn-block col-12" style="margin-top:1em">
        Retour à l'accueil
      </div>
    </a>
  </form>
  <?php
  if(isset($_SESSION['update_err']['errClear'])){
  ?>
  <div class="container bg-success col-8 text-white rounded justify-content-center" style="margin-top:0.5em;padding:2em">
      <p style="margin:0.5em auto;text-align:center"> <i class="far fa-check-circle"></i> <?= htmlspecialchars($_SESSION['update_err']['errClear']) ?></p>
  </div>
  <?php
  }
  ?>
</div>
<?php
unset($_SESSION['update_err']);
?>

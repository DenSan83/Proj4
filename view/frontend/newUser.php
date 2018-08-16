<?php
$superTitle = 'Créer une compte utilisateur';
?>

<div id="newUser" class="container row col-9 bg-secondary rounded justify-content-center">
  <form method="post" action="<?= HOST ?>newUser" class="form-horizontal col-10 justify-content-center" role="form">
    <h2 class="text-primary"><b>Créez votre compte</b></h2>
    <div class="form-group">
      <label for="pseudo" class="col-sm-6 control-label">Pseudo *</label>
      <div class="col-sm-12">
        <input type="text" id="pseudo" placeholder="Pseudo" class="form-control" name="pseudo" value="<?php if(isset($_POST['pseudo'])) echo $_POST['pseudo']; ?>" autofocus required>
      </div>
      <?php
      if (isset($errPseudo)){
      ?>
      <div class="container bg-danger col-6 text-white rounded">
        <p><?= htmlspecialchars($errPseudo) ?></p>
      </div>
      <?php
      }
      ?>
    </div>

    <div class="form-group">
      <label for="email" class="col-sm-6 control-label">Email* </label>
        <div class="col-sm-12">
          <input type="email" id="email" placeholder="Email" class="form-control" name= "email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" required>
        </div>
        <?php
        if (isset($errEmail)){
        ?>
        <div class="container bg-danger col-6 text-white rounded">
          <p><?= htmlspecialchars($errEmail) ?></p>
        </div>
        <?php
        }
        ?>
    </div>
    <div class="form-group">
      <label for="email2" class="col-sm-6 control-label">Confirmer email* </label>
        <div class="col-sm-12">
          <input type="email" id="email2" placeholder="Confirmer email" class="form-control" name= "email2" value="<?php if(isset($_POST['email2'])) echo $_POST['email2']; ?>" required>
        </div>
        <?php
        if (isset($errEmail2)){
        ?>
        <div class="container bg-danger col-6 text-white rounded">
          <p><?= htmlspecialchars($errEmail2) ?></p>
        </div>
        <?php
        }
        ?>
    </div>
    <div class="form-group">
      <label for="password" class="col-sm-6 control-label">Mot de passe*</label>
      <div class="col-sm-12">
        <input type="password" id="password" placeholder="Mot de passe (au moins 6 caractères)" class="form-control" name="password" required>
      </div>
      <?php
      if (isset($errPassword)){
      ?>
      <div class="container bg-danger col-6 text-white rounded">
        <p><?= htmlspecialchars($errPassword) ?></p>
      </div>
      <?php
      }
      ?>
    </div>
    <div class="form-group">
      <label for="password2" class="col-sm-6 control-label">Confirmer mot de passe*</label>
      <div class="col-sm-12">
        <input type="password" id="password2" placeholder="Confirmer mot de passe" class="form-control" name="password2" required>
      </div>
      <?php
      if (isset($errPassword2)){
      ?>
      <div class="container bg-danger col-6 text-white rounded">
        <p><?= htmlspecialchars($errPassword2) ?></p>
      </div>
      <?php
      }
      ?>
    </div>

    <div class="form-group">
      <div class="col-sm-12 col-sm-offset-3">
        <span class="help-block">* = Champs obligatoires</span>
      </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block col-12">S'en registrer</button>
  </form>
  <?php
  if(isset($errClear)){
  ?>
  <div class="modal fade" id="overlay">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h4 class="modal-title">Succès !</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <p><span><i class="fas fa-check-circle text-success"></i>&nbsp;&nbsp;</span><span><?= $errClear ?></span></p>
        </div>
      </div>
    </div>
  </div>
  <?php
  }
  ?>
</div>

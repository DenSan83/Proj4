<?php $title = 'Créer une compte utilisateur'; ?>
<?php
if(!empty($params)) extract($params);
ob_start();
?>

<div class="container row col-9 bg-secondary rounded justify-content-center" style="margin:2em auto; padding:2em">
  <form method="post" action="<?= HOST ?>newUser" class="form-horizontal col-10 justify-content-center" role="form">
    <h2 style="margin:0.5em auto;text-align:center">Créez votre compte</h2>
    <div class="form-group">
      <label for="pseudo" class="col-sm-3 control-label">Pseudo *</label>
      <div class="col-sm-12">
        <input type="text" id="pseudo" placeholder="Pseudo" class="form-control" name="pseudo" value="<?php if(isset($_POST['pseudo'])) echo $_POST['pseudo']; ?>" autofocus required>
      </div>
      <?php
      if (isset($errPseudo)){
      ?>
      <div class="container bg-danger col-6 text-white rounded" style="margin-top:0.5em">
        <p style="margin:0.5em auto;text-align:center"><?= $errPseudo ?></p>
      </div>
      <?php
      }
      ?>
    </div>

    <div class="form-group">
      <label for="email" class="col-sm-3 control-label">Email* </label>
        <div class="col-sm-12">
          <input type="email" id="email" placeholder="Email" class="form-control" name= "email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" required>
        </div>
        <?php
        if (isset($errEmail)){
        ?>
        <div class="container bg-danger col-6 text-white rounded" style="margin-top:0.5em">
          <p style="margin:0.5em auto;text-align:center"><?= $errEmail ?></p>
        </div>
        <?php
        }
        ?>
    </div>
    <div class="form-group">
      <label for="email2" class="col-sm-3 control-label">Confirmer email* </label>
        <div class="col-sm-12">
          <input type="email" id="email2" placeholder="Confirmer email" class="form-control" name= "email2" value="<?php if(isset($_POST['email2'])) echo $_POST['email2']; ?>" required>
        </div>
        <?php
        if (isset($errEmail2)){
        ?>
        <div class="container bg-danger col-6 text-white rounded" style="margin-top:0.5em">
          <p style="margin:0.5em auto;text-align:center"><?= $errEmail2 ?></p>
        </div>
        <?php
        }
        ?>
    </div>
    <div class="form-group">
      <label for="password" class="col-sm-3 control-label">Mot de passe*</label>
      <div class="col-sm-12">
        <input type="password" id="password" placeholder="Mot de passe" class="form-control" name="password" required>
      </div>
      <?php
      if (isset($errPassword)){
      ?>
      <div class="container bg-danger col-6 text-white rounded" style="margin-top:0.5em">
        <p style="margin:0.5em auto;text-align:center"><?= $errPassword ?></p>
      </div>
      <?php
      }
      ?>
    </div>
    <div class="form-group">
      <label for="password2" class="col-sm-3 control-label">Confirmer mot de passe*</label>
      <div class="col-sm-12">
        <input type="password" id="password2" placeholder="Confirmer mot de passe" class="form-control" name="password2" required>
      </div>
      <?php
      if (isset($errPassword2)){
      ?>
      <div class="container bg-danger col-6 text-white rounded" style="margin-top:0.5em">
        <p style="margin:0.5em auto;text-align:center"><?= $errPassword2 ?></p>
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
  <div class="container bg-success col-8 text-white rounded justify-content-center" style="margin-top:0.5em">
      <p style="margin:0.5em auto;text-align:center"> <?= $errClear ?></p>
  </div>
  <?php
  }
  ?>
</div> <!-- ./container -->

<?php $content = ob_get_clean(); ?>
<?php require(VIEW.'template.php'); ?>

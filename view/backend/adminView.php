<?php
$title = 'AdminView';

ob_start();
?>

<h1>Session Admin</h1>

<fieldset class="container">
  <legend>Derniers commentaires</legend>
  <p>commentaires</p>
</fieldset>

<fieldset class="container">
  <legend>Nouveaux commentaires signalés</legend>
  <p>commentaires</p>
</fieldset>

<fieldset class="container">
  <legend>Ajouter nouveau Post</legend>
  <textarea name="name" rows="8" cols="80"></textarea>
</fieldset>

<?php $content = ob_get_clean(); ?>
<?php require(VIEW.'template.php'); ?>

<?php $title = 'Mon blog'; ?>

<?php ob_start();  // debut de contenu à stocker ?>
<h1>Mon super blog !</h1>
<p>Derniers billets du blog :</p>

<?php
while($data = $posts->fetch()){
?>
<div class="news">
  <h3>
    <?= htmlspecialchars($data['title']) ?>
    <em>le <?= $data['creation_date_fr']; ?></em>
  </h3>
  <p>
  <?= nl2br(htmlspecialchars($data['content']))  ?>
  <br />
  <em><a href="index.php?r=post/id/<?= $data['id'] ?>">Commentaires</a></em></p>
</div>
  <?php
}

$posts->closeCursor();
?>
<?php $content = ob_get_clean(); //fin du contenu à stocker (dans $content) ?>

<?php require('view/frontend/template.php'); ?>

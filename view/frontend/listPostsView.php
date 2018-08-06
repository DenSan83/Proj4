<?php $title = 'Mon blog'; ?>

<?php ob_start();  // debut de contenu à stocker ?>
<div class="container col-10" style="margin:1em auto">
    <h2>Derniers billets du blog :</h2>
</div>


<?php
foreach($params as $post){
?>
<div class="rounded container news" style="border:1px solid black; margin:1em auto;">
  <div class="row justify-content-between bg-dark text-white" >
    <div class="col-4">
        <h3><?= htmlspecialchars($post->getTitle()) ?> </h3>
    </div>
    <div class="col-3">
        <h5><em>le <?= htmlspecialchars($post->getDateFr()) ?></em></h5>
    </div>
  </div>
  <p> <?=  htmlspecialchars_decode($post->getShortContent(60)) ?> </p>
  <em><a href="<?= HOST ?>post/id/<?= $post->getId() ?>">Lire la suite >></a></em>
</div>
  <?php
}
?>
<div class="container col-3" style="margin:1em auto">
    <a href="#">Lire tout le Chapitre 1</a>
</div>
<?php $content = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>

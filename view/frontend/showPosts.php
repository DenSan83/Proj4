<?php
$superTitle = 'Billet Simple pour Alaska - Chapitre 1';
?>
<div id="chapter" class="container rounded">
  <?php
  foreach (array_reverse($params) as $obj) {
  ?>
  <a href="<?=HOST.'post/id/'.$obj->getId()?>">
    <div class="rounded post">
      <?=htmlspecialchars_decode($obj->getContent())?>
    </div>
  </a>
  <?php
  }
  ?>
</div>
<div id="retour" class="container">
  <a href="<?=HOST?>"><< Retour</a>
</div>

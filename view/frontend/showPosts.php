<?php
$superTitle = 'Billet Simple pour Alaska - Chapitre 1';
?>
<div class="container rounded" style="margin-top:12em;margin-bottom:6em">
  <?php
  foreach (array_reverse($params) as $obj) {
  ?>
  <a href="<?=HOST.'post/id/'.$obj->getId()?>" style="text-decoration:none;color:black;text-align:justify">
    <p style="border:1px solid black"><?=htmlspecialchars_decode($obj->getContent())?></p>
  </a>
  <?php
  }
  ?>
</div>
<div class="container" style="margin-bottom:6em">
  <a href="<?=HOST?>" style="text-decoration:underline"><< Retour</a>
</div>

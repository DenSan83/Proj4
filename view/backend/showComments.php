<?php
$superTitle = 'Commentaires des Posts';
?>

<div id="showComments" class="container">
  <p class="hidden">Pour meilleurs résultats, tournez l'écran à l'horizontale</p>
  <div class="container rounded showComs">
    <h4>Tous les commentaires :</h4>
    <h4>(Total : <?= count($lastComments) ?>)</h4>
    <div class="container rounded tousComs">
      <div class="comments">
        <table class="table table-hover">
          <thead class="thead-dark">
            <tr>
              <th scope="col">ID</th>
              <th scope="col" width="15%">Auteur</th>
              <th scope="col" width="30%">Commentaire</th>
              <th scope="col" width="20%">Sur le post...</th>
              <th scope="col">Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($lastComments as $comment) {
            ?>
            <tr onclick="window.location='<?= HOST.'post/id/'.$comment->getPostId().'#comment'.$comment->getId() ?>';" class="pointer">
              <th scope="row"><?= $comment->getId() ?></th>
              <td><?= $comment->getAuthor() ?></td>
              <td class="tdComment"><?= $comment->getComment() ?></td>
              <td><?= $postManager->getPost($comment->getPostId())->getTitle() ?></td>
              <td>le <?= $comment->getDateCom() ?></td>
            </tr>
            </a>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <br/>
    <a href="<?=HOST?>admin"><< Retour</a>
  </div>
</div>

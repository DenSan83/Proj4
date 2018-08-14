<?php
$title = 'Commentaires des Posts';
extract($params);
ob_start();
?>

<div class="container" style="margin-top:12em">
  <div class="container rounded" style="border:1px solid black;margin:1em auto;padding:1em">
    <h4>Tous les commentaires :</h4>
    <h4>(Total : <?= count($lastComments) ?>)</h4>
    <div class="container rounded" style="border:1px solid black; padding:0;overflow:hidden">
      <div class="comments">
        <table class="table table-hover">
          <thead class="thead-dark">
            <tr>
              <th scope="col">ID</th>
              <th scope="col" style="width:15%">Auteur</th>
              <th scope="col" style="width:30%">Commentaire</th>
              <th scope="col" style="width:20%">Sur le post...</th>
              <th scope="col">Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($lastComments as $comment) {
            ?>
            <tr onclick="window.location='<?= HOST.'post/id/'.$comment->getPostId() ?>';" style="cursor:pointer">
              <th scope="row"><?= $comment->getId() ?></th>
              <td><?= $comment->getAuthor() ?></td>
              <td style="color:blue"><?= $comment->getComment() ?></td>
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
<?php
$content = ob_get_clean();
require(VIEW.'template.php'); ?>

<?php
$title = 'AdminView';
extract($params);

ob_start();
?>

<h1>Session Admin</h1>

<div class="container">
  <h4>Derniers 5 commentaires</h4>
  <div class="container rounded" style="border:1px solid black; padding:0;overflow:hidden">
    <div class="comments">
      <table class="table table-hover">
        <thead class="thead-dark">
          <tr>
            <th scope="col">ID</th>
            <th scope="col" style="width:20%">Auteur</th>
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
            <td>(titre du post)</td>
            <td>le <?= $comment->getDateFr() ?></td>
          </tr>
          </a>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</br></br>

<div class="container">
  <h4>Nouveaux commentaires signalés</h4>
  <div class="container rounded" style="border:1px solid black; padding:0;overflow:hidden">
    <div class="comments">
      <table class="table table-hover">
        <thead class="thead-dark">
          <tr>
            <th scope="col">ID</th>
            <th scope="col" style="width:20%">Auteur</th>
            <th scope="col" style="width:50%">Commentaire</th>
            <th scope="col">Signalé par</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($flagged as $flag) {
          ?>
          <tr onclick="window.location='<?= HOST.'post/id/'.$flag['comment_id'] ?>';" style="cursor:pointer">
            <th scope="row"><?= $flag['id'] ?></th>
            <td><?= $flag['flagger'] ?></td>
            <td style="color:blue"><?= $flag['comment_id'] ?></td>
            <td>le <?= $flag['flag_date'] ?></td>
          </tr>
          </a>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<fieldset class="container">
  <legend>Ajouter nouveau Post</legend>
  <textarea name="name" rows="8" cols="80"></textarea>
</fieldset>

<?php $content = ob_get_clean(); ?>
<?php require(VIEW.'template.php'); ?>

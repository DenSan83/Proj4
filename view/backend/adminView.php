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

      <table class="table">
        <tr>
          <td style="padding:0">
            <table class="table">
              <thead class="thead-dark">
                <tr>
                  <th scope="col" style="width:5%">ID</th>
                  <th scope="col" style="width:10%">Auteur</th>
                  <th scope="col" style="width:40%">Commentaire</th>
                  <th scope="col" style="width:10%">Signalé par</th>
                  <th scope="col" style="width:16%">Signalé le...</th>
                  <th scope="col" style="text-align:center">Enlever</th>
                  <th scope="col" style="text-align:center">Effacer</th>
                </tr>
              </thead>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:0">
            <div class="" style="max-height:15em; overflow:auto;">
              <table class="table">
                <tbody>
                  <?php
                  foreach ($flagged as $flag) {
                  ?>
                  <tr>
                    <th scope="row" style="width:5%"><?= $flag['id'] ?></th>
                    <td style="width:10%"><?= $commentManager->getComment($flag['comment_id'])['author'] ?></td>
                    <td onclick="window.location='<?= HOST.'post/id/'.$commentManager->getComment($flag['comment_id'])['post_id'].'#comment'.$flag['comment_id'] ?>';" style="color:blue;cursor:pointer;width:40%"><?= $commentManager->getComment($flag['comment_id'])['comment'] ?></td>
                    <td style="width:10%"><?= $flag['flagger'] ?></td>
                    <td style="width:16%"><?= $flag['flag_date'] ?></td>
                    <td onclick="window.location='#';" style="cursor:pointer" align="center"><i class="fas fa-thumbs-up text-info"></i></td>
                    <td onclick="window.location='#';" style="cursor:pointer" align="center"><i class="fas fa-thumbs-down text-danger"></i></td>
                  </tr>
                  </a>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </td>
        </tr>
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

<?php
$title = 'AdminView';
extract($params);

ob_start();
?>

<h1 style="text-align:center;text-decoration:underline;margin:1em auto;margin-top:4.5em">Espace Administrateur</h1>

<div class="container rounded" style="border:1px solid black;margin:1em auto;padding:1em">
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
</div>
</br>

<div class="container rounded" style="border:1px solid black;margin:1em auto;padding:1em">
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
                  <th scope="col" style="width:38%">Commentaire</th>
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
            <?php
            if(empty($flagged)){
            ?>
            <div class="container rounded bg-info col-6 text-white">
              <p style="text-align:center">Il n'y a pas des commentaires signalés pour l'instant. Excellent !</p>
            </div>
            <?php
            }
            ?>
            <div style="min-height:2em;height:5em;overflow:auto;resize:vertical">
              <table class="table">
                <tbody>
                  <?php
                  foreach ($flagged as $flag) {
                  ?>
                  <tr>
                    <th scope="row" style="width:5%"><?= $flag['comment_id'] ?></th>
                    <td style="width:10%"><?= $commentManager->getComment($flag['comment_id'])['author'] ?></td>
                    <td onclick="window.location='<?= HOST.'post/id/'.$commentManager->getComment($flag['comment_id'])['post_id'].'#comment'.$flag['comment_id'] ?>';" style="color:blue;cursor:pointer;width:40%">
                      <?= $commentManager->getComment($flag['comment_id'])['comment'] ?>
                    </td>
                    <td style="width:10%"><?= $flag['flagger'] ?></td>
                    <td style="width:16%"><?= $flag['flag_date'] ?></td>
                    <td align="center">
                      <a data-toggle="modal" href="#unflag" class="btn btn-link"><i class="fas fa-thumbs-up text-info"></i></a>
                    </td>
                    <td align="center">
                      <a data-toggle="modal" href="#delAdmin" class="btn btn-link"><i class="fas fa-thumbs-down text-danger"></i>
                    </td>
                  </tr>

                  <!-- modale unflag -->
                  <div class="modal fade" id="unflag" tabindex="-1" role="dialog" aria-labelledby="unflagLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Enlever le signalement</h4>
                                <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
                            </div>
                            <div class="modal-body">
                                <p><b><?= $commentManager->getComment($flag['comment_id'])['comment'] ?></b></p>
                                <p>Êtes-vous sûr  de vouloir enlever le signalement de ce commentaire ?</p>
                            </div>
                            <div class="modal-footer">
                              <form action="<?= HOST.'unflag' ?>" method="post">
                                <input type="hidden" name="commentId" value="<?= $flag['comment_id'] ?>">
                                <button class="btn btn-primary" type="submit"><i class="icon icon-check icon-lg"></i> Enlever</button>
                              </form>
                              <button class="btn btn-inverse" type="button" data-dismiss="modal"><i class="icon icon-times icon-lg"></i> Fermer</button>
                            </div>
                        </div>
                    </div>
                  </div>

                  <!-- modale delete -->
                  <div class="modal fade" id="delAdmin" tabindex="-1" role="dialog" aria-labelledby="delAdminLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Effacer le commentaire</h4>
                                <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
                            </div>
                            <div class="modal-body">
                                <p><b><?= $commentManager->getComment($flag['comment_id'])['comment'] ?></b></p>
                                <p>Êtes-vous sûr  de vouloir effacer ce commentaire ?</p>
                            </div>
                            <div class="modal-footer">
                              <form action="<?= HOST.'delAdmin' ?>" method="post">
                                <input type="hidden" name="deleteId" value="<?= $flag['comment_id'] ?>">
                                <button class="btn btn-primary" type="submit"><i class="icon icon-check icon-lg"></i> Effacer</button>
                              </form>
                              <button class="btn btn-inverse" type="button" data-dismiss="modal"><i class="icon icon-times icon-lg"></i> Fermer</button>
                            </div>
                        </div>
                    </div>
                  </div>

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
</br>

<div class="container rounded" style="border:1px solid black;margin:1em auto;padding:1em">
  <h4>Editer un post :</h4>
  <div class="container">
    <?php
    foreach($postManager->getPosts() as $post)
    {
    ?>
    <form class="" action="<?= HOST.'editPost/id/'.$post->getId() ?>" method="post" style="margin:0.5em auto">
      <button class="btn-info container form-control"type="submit" name="post"><?= $post->getTitle() ?> (Post ID : <?= $post->getId() ?>)</button>
    </form>
    <?php
    }
    ?>
  </div>
</div>
</br>

<div class="container rounded" style="margin:2em auto;border:1px solid black;padding:1em">
  <h4>Ajouter nouveau post</h4>
  <form method="post" action="adminPost" enctype="multipart/form-data">
    <input class="form-control" type="text" name="postTitle" placeholder="Titre" style="margin-bottom:1em">
    <textarea id="editor" name="newPost"></textarea>

    <button class="form-control btn-info rounded" type="submit" style="margin-top:1em">Créer nouveau post</button>
  </form>
</div>

<?php $content = ob_get_clean(); ?>
<?php require(VIEW.'template.php'); ?>

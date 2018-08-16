<?php
$superTitle = 'AdminView';
?>

<h2 id="adminH2">Espace Administrateur</h2>

<div id="adminComments" class="container rounded">
  <h4>Derniers 5 commentaires</h4>
  <div class="container rounded tableau">
    <div class="comments">
      <table class="table table-hover">
        <thead class="thead-dark">
          <tr>
            <th scope="col">ID</th>
            <th scope="col" width="15%">Auteur</th>
            <th scope="col" width="40%">Commentaire</th>
            <th scope="col" width="20%">Sur le post...</th>
            <th scope="col">Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($lastComments as $comment) {
          ?>
          <tr onclick="window.location='<?= HOST.'post/id/'.$comment->getPostId().'#comment'.$comment->getId() ?>';" class="trComment">
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
  <a href="<?=HOST?>showComments">Voir tous les commentaires >></a>
</div>
</br>

<div id="flagList" class="container rounded">
  <h4>Nouveaux commentaires signalés</h4>
  <div class="container rounded first">
    <div class="comments">

      <table class="table top">
        <tr>
          <td class="top">
            <table class="table">
              <thead class="thead-dark">
                <tr>
                  <th scope="col" width="5%">ID</th>
                  <th scope="col" width="10%">Auteur</th>
                  <th scope="col" width="38%">Commentaire</th>
                  <th scope="col" width="10%">Signalé par</th>
                  <th scope="col" width="16%">Signalé le...</th>
                  <th scope="col" class="thCenter">Valider</th>
                  <th scope="col" class="thCenter">Effacer</th>
                </tr>
              </thead>
            </table>
          </td>
        </tr>
        <tr>
          <td class="top">
            <?php
            if(empty($flagged)){
            ?>
            <div class="container rounded bg-info col-6 text-white">
              <p class="thCenter">Il n'y a pas des commentaires signalés pour l'instant. Excellent !</p>
            </div>
            <?php
            }
            ?>
            <div id="flagIn">
              <table class="table">
                <tbody>
                  <?php
                  foreach ($flagged as $flag) {
                  ?>
                  <tr>
                    <th scope="row" width="5%"><?= $flag['comment_id'] ?></th>
                    <td width="10%"><?= $commentManager->getComment($flag['comment_id'])['author'] ?></td>
                    <td onclick="window.location='<?= HOST.'post/id/'.$commentManager->getComment($flag['comment_id'])['post_id'].'#comment'.$flag['comment_id'] ?>';" class="tdComFlag">
                      <?= $commentManager->getComment($flag['comment_id'])['comment'] ?>
                    </td>
                    <td width="10%"><?= $flag['flagger'] ?></td>
                    <td width="16%"><?= $flag['flag_date_fr'] ?></td>
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

<div id="editAdmin" class="container rounded">
  <h4>Editer un post :</h4>
  <div class="container">
    <?php
    foreach($postManager->getPosts() as $post)
    {
    ?>
    <form class="" action="<?= HOST.'editPost/id/'.$post->getId() ?>" method="post">
      <button class="btn-info container form-control"type="submit" name="post"><?= $post->getTitle() ?> (Post ID : <?= $post->getId() ?>)</button>
    </form>
    <?php
    }
    ?>
  </div>
</div>
</br>

<div id="newPostAdmin" class="container rounded">
  <h4>Ajouter nouveau post</h4>
  <form method="post" action="adminPost" enctype="multipart/form-data">
    <input class="form-control" type="text" name="postTitle" placeholder="Titre">
    <textarea id="editor" name="newPost" ></textarea>
    <div class="container align-items-center rounded col-12 images">
      <div class="container row align-items-center">
        <label for="postImg" class="col-4">Ajouter une image au post (taille max : 2Mo) :</label>
        <input type="file" name="postImg" class="bg-light rounded form-control-file col-8">
      </div>
    </div>

    <button class="form-control btn-info rounded" type="submit">Créer nouveau post</button>
  </form>
</div>

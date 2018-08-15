<?php
$superTitle = 'Billet Siple pour Alaska - Zone Admin';
?>

<div class="container" style="margin:2em auto;margin-top:12em">
  <h5 class="rounded" style="background-color:#f5f5f5;text-align:center">
    <a href="<?= HOST ?>admin"><i class="fas fa-arrow-circle-left"></i> Retour</a> ||
    <a href="<?= HOST.'post/id/'.$id ?>"><i class="fas fa-arrow-circle-right"></i> Aller au post</a> ||
    <a data-toggle="modal" href="#delPost"><i class="far fa-trash-alt"></i> Effacer ce post</a>
  </h5>

  <!-- modale delPost -->
  <div class="modal fade" id="delPost" tabindex="-1" role="dialog" aria-labelledby="delPostLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Effacer le post</h4>
                <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr  de vouloir effacer ce post ?</p>
            </div>
            <div class="modal-footer">
              <form action="<?= HOST.'delPost' ?>" method="post">
                <input type="hidden" name="delId" value="<?= $id ?>">
                <button class="btn btn-primary" type="submit"><i class="icon icon-check icon-lg"></i> Effacer</button>
              </form>
              <button class="btn btn-inverse" type="button" data-dismiss="modal"><i class="icon icon-times icon-lg"></i> Fermer</button>
            </div>
        </div>
    </div>
  </div>

  <h4>Editer post (ID : <?= $id ?>)</h4>
  <form method="post" action="<?= HOST.'modifyPost' ?>" enctype="multipart/form-data">
    <h4>Titre</h4>
    <input class="form-control" type="text" name="postTitle" placeholder="Titre" value="<?= $title ?>" style="margin-bottom:1em">
    <h4>Contenu</h4>
    <textarea id="editor" name="newPost"><?= $content ?></textarea>
    <div class="container align-items-center rounded col-12" style="border:1px solid silver;margin:1em auto;padding:0.5em">
      <div class="container-liquid image" style="margin:0 auto">
        <h4>Image</h4>
        <img src="<?= HOST.'public/images/post/'.$image ?>" alt="image du post <?= $id ?>" style="width:100%;margin:0 auto 1em auto">
        <input type="hidden" name="postId" value="<?= $id ?>">
      </div>
      <div class="container align-items-center row">
        <label for="postImg" class="col-4">Changer l'image du post (taille max : 2Mo) :</label><input type="file" name="postImg" class="bg-light rounded form-control-file col-8" style="margin:5px auto">
      </div>
    </div>

    <button class="form-control btn-info rounded" type="submit" style="margin-top:1em">Enregistrer les changements du post</button>
  </form>
</div>

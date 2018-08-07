<?php
$title = 'AdminView';
extract($params);

ob_start();
?>


<div class="container" style="margin:2em auto">
  <h4>Editer post (ID : <?= $id ?>)</h4>
  <h5>
    <a href="<?= HOST ?>admin">Retour</a> ||
    <a href="<?= HOST.'post/id/'.$id ?>">Aller au post</a>
  </h5>
  <form method="post" action="<?= HOST.'modifyPost' ?>" enctype="multipart/form-data">
    <h4>Titre</h4>
    <input class="form-control" type="text" name="postTitle" placeholder="Titre" value="<?= $title ?>" style="margin-bottom:1em">
    <h4>Contenu</h4>
    <textarea id="editor" name="newPost"><?= $content ?></textarea>
    <div class="container row align-items-center rounded col-12" style="border:1px solid silver;margin:1em auto;padding:0.5em">
      <div class="container-liquid image" style="margin:0 auto">
        <h4>Image</h4>
        <img src="<?= HOST.'public/images/post/'.$image ?>" alt="image du post <?= $id ?>" style="width:100%;margin:0 auto 1em auto">
        <input type="hidden" name="postId" value="<?= $id ?>">
      </div>
      <label for="postImg" class="col-4">Changer l'image du post (taille max : 2Mo) :</label><input type="file" name="postImg" class="bg-light rounded form-control-file col-8" style="margin:5px auto">
    </div>

    <button class="form-control btn-info rounded" type="submit" style="margin-top:1em">Editer le post</button>
  </form>
</div>

<?php $content = ob_get_clean(); ?>
<?php require(VIEW.'template.php'); ?>

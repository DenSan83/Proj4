<?php $title = htmlspecialchars($post['title']); ?>

<?php ob_start(); ?>
<h1>Mon super blog !</h1>
<p><a href="index.php">Retour à la liste des billets</a></p>

<div class="news">
    <h3>
        <?= htmlspecialchars($post['title']) ?>
        <em>le <?= $post['creation_date_fr'] ?></em>
    </h3>

    <p>
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </p>
</div>

<h2>Commentaires</h2>
<?php
while ($comment = $comments->fetch())
{
  if (isset($commId) && $comment['id'] == $commId){
    // modifier commentaire
  ?>
  <div class="commentBox">
    <div class="author">
        <p><strong><?= htmlspecialchars($comment['author']) ?></strong></p>
    </div>
    <div class="comment">
      <p class="date-time"> <i class="far fa-clock"></i> le <?= $comment['comment_date_fr'] ?></p>
      <form method="post" action="index.php?r=commentUpdate">
        <textarea name="updated" rows="2" cols="40"><?= nl2br(htmlspecialchars($comment['comment'])) ?></textarea>
        <input type="hidden" name="commentId" value="<?= $comment['id'] ?>">

        <button type="submit"><i class="far fa-check-circle"></i></button>
        <a href="index.php?r=post/id/<?= $post['id'] ?>"><i class="far fa-times-circle"></i></a>
      </form>
      <hr/>
      <div class="options">
        <a href="index.php?r=modifyComment/postId/<?= $post['id'] ?>/commId/<?= $comment['id'] ?>">
          <div class="option modify">
            <i class="fas fa-edit"></i>
            <span>Modifier</span>
          </div>
        </a>
        <a href="#">
          <div class="option repondre">
            <i class="fas fa-reply"></i>
            <span>Répondre</span>
          </div>
        </a>
        <a href="#">
          <div class="option signaler">
            <i class="fas fa-fire"></i>
            <span>Signaler</span>
          </div>
        </a>
      </div>
    </div>
  </div>
<?php
  } else {
  // montrer commentaire
?>
<div class="commentBox">
  <div class="author">
      <p><strong><?= htmlspecialchars($comment['author']) ?></strong></p>
  </div>
  <div class="comment">
    <p class="date-time"> <i class="far fa-clock"></i> le <?= $comment['comment_date_fr'] ?></p>
    <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
    <hr/>
    <div class="options">
      <a href="index.php?r=modifyComment/postId/<?= $post['id'] ?>/commId/<?= $comment['id'] ?>">
        <div class="option modify">
          <i class="fas fa-edit"></i>
          <span>Modifier</span>
        </div>
      </a>
      <a href="#">
        <div class="option repondre">
          <i class="fas fa-reply"></i>
          <span>Répondre</span>
        </div>
      </a>
      <a href="#">
        <div class="option signaler">
          <i class="fas fa-fire"></i>
          <span>Signaler</span>
        </div>
      </a>
    </div>
  </div>
</div>
<?php
  }
}
//ajouter commentaire
?>
<form action="index.php?r=addComment/id/<?= $post['id'] ?>" method="post" class="postForm">
    <div>
      <input type="hidden" id="postId" name="postId" value="<?= $post['id'] ?>">
      <label for="author">Auteur</label><br />
      <input type="text" id="author" name="author" required/>
    </div>
    <div>
      <label for="comment">Commentaire</label><br />
      <textarea id="comment" name="comment" required></textarea>
    </div>
    <div>
        <input type="submit" />
    </div>
</form>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>

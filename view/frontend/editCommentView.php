<?php
foreach($comments as $comment) {
  if($comment->getId() == (int) $commentId){
?>
<div class="container rounded row commentBox col-10 col-lg-9 justify-content-between editComment" id="comment<?= $comment->getId() ?>">
  <div class="container-liquid author col-2 row align-items-center" >
    <div class="container-liquid commentAvatar col-4 offset-3">
      <?php
      if($comment->getAuthorId()) {
      $myAvatar = $avatarList[$comment->getAuthorId()];
      } else {
        $myAvatar = $avatarList[0];
        $myAvatar['pseudo'] = $comment->getAuthor();
      }
      ?>
      <img class="rounded-circle" src="<?= HOST ?>public/images/avatar/<?= $myAvatar['avatar'] ?>" alt="avatar user">
    </div>
    <div class="container col-6 offset-3 align-items-center">
      <p><strong><?= htmlspecialchars($myAvatar['pseudo']) ?></strong></p>
      <p><?= htmlspecialchars($myAvatar['status']) ?></p>
    </div>
  </div>
  <div class="container-liquid commentText align-self-end col-10">
    <div class="container-liquid row justify-content-end">
      <p class="date-time"> <i class="far fa-clock"></i> le <?= $comment->getDateCom() ?></p>
    </div>
    <form method="post" action="<?= HOST ?>commentUpdate" class="row align-items-start">
      <textarea name="updated" rows="2" cols="80"><?= nl2br(htmlspecialchars($comment->getComment())) ?></textarea>
      <input type="hidden" name="commentId" value="<?= $comment->getId() ?>">
      <input type="hidden" name="postId" value="<?= $comment->getPostId() ?>">

      <button type="submit" id="change" class="btn-success rounded"><i class="far fa-check-circle"></i></button>
      <a href="<?= HOST ?>post/id/<?= $post->getId().'#comment'.$comment->getId() ?>" class="btn-danger rounded refuse"><i class="far fa-times-circle"></i></a>
    </form>
    <hr/>
    <div class="container-liquid col-12 row choix justify-content-end">
      <div class="container row justify-content-end col-5">
        <button type="submit" name="effacer" class="container col-5 text-primary bg-white option effacer" data-toggle="modal" data-target="#effacer">
          <i class="fas fa-trash-alt col-9"></i>
          <span>Effacer</span>
        </button>

        <div class="modal fade" id="effacer" tabindex="-1" role="dialog" aria-labelledby="eraseCommentLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h4 class="modal-title">Effacer commentaire</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce commentaire ?
              </div>
              <div class="modal-footer">
                <form action="<?= HOST ?>delete" method="post" id="deleteForm">
                  <input type="hidden" name="postId" value="<?= $post->getId() ?>">
                  <input type="hidden" name="commId" value="<?= $comment->getId() ?>">
                  <button type="submit" class="btn btn-danger"><i class="icon icon-check icon-lg"></i> Oui, supprimer</button>
                </form>

                <button type="button" class="btn btn-inverse" data-dismiss="modal"><i class="icon icon-times icon-lg"></i> Non, fermer</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<?php
  }
}
?>

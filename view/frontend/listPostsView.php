<?php
$superTitle = 'Billet Simple pour Alaska';
$last = $last[0];
?>
<div id="last" class="container-fluid row justify-content-center align-items-center">
  <div class="background"></div>
  <div class="coat"></div>
  <div class="container col-12 col-md-8 pic">

    <div class="rounded container news">
      <div class="container first">
        <div class="container col-4 offset-8 row align-items-end second">
          <div>
            <h4><b><?= htmlspecialchars($last->getTitle()) ?></b></h4>
            <h6><em>le <?= $last->getCreationDate() ?></em></h6>
            <p><?= htmlspecialchars_decode($last->getShortContent(75)) ?></p>
            <em class="link rounded"><a href="<?= HOST ?>post/id/<?= $last->getId() ?>">Lire la suite >></a></em>
          </div>
        </div>
        <img src="<?= HOST.'public/images/post/'.$last->getImage() ?>" alt="Image du dernier post (post <?=$last->getId() ?>)">
      </div>
    </div>

  </div>
</div>

<div id="bar" class="container-fluid bg-primary row align-items-center">
  <div class="container">
    <h3>Pas encore membre ? <a href="<?=HOST?>newUser">Inscrivez-vous !</a> </h3>
  </div>
</div>

<div id="lissez" class="container rounded">
  <div class="container bg-dark text-white">
    <h3>Lissez aussi :</h3>
  </div>
  <div class="container second">
    <?php
    foreach($posts as $post){
    ?>
    <div class="row align-items-start rounded whole" >
      <div class="col-3 img">
        <img src="<?= HOST.'public/images/post/'.$post->getImage() ?>" alt="Image du post <?=$post->getId() ?>">
      </div>
      <div class="container col-9 news">

        <div class="container-fluid row justify-content-between bg-dark text-white first">
          <div class="col-4">
            <h5><?= htmlspecialchars($post->getTitle()) ?> </h5>
          </div>
          <div class="col-3">
            <h6><em>le <?= $post->getCreationDate() ?></em></h6>
          </div>
        </div>

        <div class="container-fluid row second">
          <div class="col-11">
            <p><?=  strip_tags(htmlspecialchars_decode($post->getShortContent(69))) ?></p>
          </div>
          <div class="col-1 row align-items-center">
            <a href="<?= HOST ?>post/id/<?= $post->getId() ?>">
              <div class="bouton align-items-center bg-primary">
                <p>></p>
              </div>
            </a>
          </div>
        </div>

      </div>
    </div>
    <?php
    }
    ?>
  </div>
</div>

<div id="showPosts" class="container col-3">
    <a href="<?=HOST?>showPosts" >Lire tout le Chapitre 1</a>
</div>

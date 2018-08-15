<?php
$superTitle = 'Billet Simple pour Alaska';
$last = $last[0];
?>
<div class="container-fluid row justify-content-center align-items-center last" style="height:60em;position:relative;margin:0;padding:0">
  <div class="background" style="background-image: url('<?=HOST?>public/images/backalaska.png');width:100%;height:60em;position:absolute"></div>
  <div class="coat" style="height:60em;width:100%;background-color:rgba(255,255,255,0.1);position:absolute;top:0"></div>
  <div class="container col-12 col-md-8" style="position:absolute;margin:0 auto;margin-top:4em">

    <div class="rounded container news" style="border:1px solid black; margin:1em auto;padding:0;overflow:hidden;box-shadow:4px 4px 4px rgba(0,0,0,0.2)">
      <div class="container" style="padding:0;position:relative">
        <div class="container col-4 offset-8 row align-items-end" style="background-color:rgba(255,255,255,0.8);height:100%;position:absolute;padding:2em 1.2em">
          <div style="text-align:justify">
            <h4><b><?= htmlspecialchars($last->getTitle()) ?></b></h4>
            <h6><em>le <?= $last->getCreationDate() ?></em></h6>
            <p><?= htmlspecialchars_decode($last->getShortContent(75)) ?></p>
            <em class="link rounded" style="background-color:white;padding:0.5em;box-shadow:2px 2px 1px silver"><a href="<?= HOST ?>post/id/<?= $last->getId() ?>">Lire la suite >></a></em>
          </div>
        </div>
        <img src="<?= HOST.'public/images/post/'.$last->getImage() ?>" alt="Image du dernier post" style="width:100%;max-height:49em;min-height:28em">
      </div>
    </div>

  </div>
</div>

<div class="container-fluid bg-primary row align-items-center bar" style="height:5em;margin:0">
  <div class="container">
    <h3 style="color:#1C3257">Pas encore membre ? <a href="<?=HOST?>newUser" style="text-decoration:underline;color:white">Inscrivez-vous !</a> </h3>
  </div>
</div>

<div class="container rounded" style="border:1px solid black;margin-top:5em;padding:0">
  <div class="container bg-dark text-white">
    <h3>Lissez aussi :</h3>
  </div>
  <div class="container" style="padding:0.5em">
    <?php
    foreach($posts as $post){
    ?>
    <div class="row align-items-start rounded" style="border:1px solid black;padding:0.1em;margin:0.3em;overflow:hidden">
      <div class="col-3" style="padding:0;height:10em;overflow:hidden">
        <img src="<?= HOST.'public/images/post/'.$post->getImage() ?>" alt="Image du post <?=$post->getId() ?>" style="width:100%">
      </div>
      <div class="container col-9 news" style="margin:0 auto;z-index:5;height:10em;padding:0">

        <div class="container-fluid row justify-content-between bg-dark text-white" style="padding:0.5em;margin:0">
          <div class="col-4">
            <h5><?= htmlspecialchars($post->getTitle()) ?> </h5>
          </div>
          <div class="col-3">
            <h6><em>le <?= $post->getCreationDate() ?></em></h6>
          </div>
        </div>

        <div class="container-fluid row" style="padding:0;margin:0">
          <div class="col-11" style="padding:0.5em;margin:0">
            <p style="margin:0;text-align:justify"><?=  strip_tags(htmlspecialchars_decode($post->getShortContent(69))) ?></p>
          </div>
          <div class="col-1 row align-items-center" style="margin:0;height:7em">
            <a style="color:white;text-decoration:none" href="<?= HOST ?>post/id/<?= $post->getId() ?>">
              <div class="bouton align-items-center bg-primary" style="margin:0;padding:0;border-radius:50%;width:30px;height:30px">
                <p style="text-align:center">></p>
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

<div class="container col-3" style="margin:1em auto;text-align:center">
    <a href="#" >Lire tout le Chapitre 1</a>
</div>

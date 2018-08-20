<!DOCTYPE html>
<html lang="fr">
    <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?= $superTitle ?></title>
      <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
      <link href="<?= HOST ?>public/css/styles.css" rel="stylesheet" />
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
      <script src='https://www.google.com/recaptcha/api.js'></script>
      <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=70pss22mnqcz4t9wgtazx0hdud310iwanzdzcjw587789kz3"></script>
      <script>
        tinymce.init({
        selector: '#editor',
        height: 300,
        plugins: 'lists advlist wordcount textcolor',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | forecolor',
        branding: false,
        forced_root_blocks : false,
        });
      </script>
    </head>

    <body>
      <header class="container-fluid">
        <nav class="container-fluid row">
          <a class="navbar-brand text-dark col-7 col-sm-6" href="<?= HOST ?>">
            <div class="rounded">
              <h1>Billet Simple <span></br></span> pour Alaska</h1>
            </div>
          </a>
          <div class="usercont row align-items-center">
            <?php
            if(empty($_SESSION['user_session'])){                               // sans session
            ?>
            <div class="btn-group dropdown">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle" src="<?= HOST ?>public/images/avatar/default.png" alt="avatar user" width="50px" height="50px">
                <span id="seConnecter">Se connecter</span>
              </button>
              <?php
              if(isset($_SESSION['noUser']) && $_SESSION['noUser'] == 1)
              {
              ?>
              <div class="modal fade" id="overlay">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                      <h4 class="modal-title">Error</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                      <p>Pseudo ou mot de passe incorrect !</p>
                    </div>
                  </div>
                </div>
              </div>
              <?php
              }
              ?>
              <div class="dropdown-menu dropdown-menu-right bg-lg">
                <form method="post" action="<?= HOST ?>login" class="login">
                  <?php
                  if(isset($postId)){
                  ?>
                  <input type="hidden" name="postId" value="<?= $postId ?>">
                  <?php
                  }
                  ?>

                  <div class="input-group">
                    <div class="col-md-12">
                      <input type="text" class="form-control" name="login" placeholder="Pseudo">
                    </div>
                  </div>

                  <div class="input-group">
                    <div class="col-md-12">
                      <div class="input-group">
                        <input type="password" class="form-control" name="password" placeholder="Mot de passe">
                      </div>
                    </div>
                  </div>

                  <div class="form-group row align-items-center">
                    <div class="button">
                      <button id="btn-signup" type="submit" class="btn btn-default">Connexion</button>
                    </div>
                  </div>

                  <div class="bottom text-center">
          					<a href="<?= HOST ?>newUser"><b>Je n'ai pas encore une compte</b></a>
          				</div>
                </form>
              </div>
            </div>
            <?php
            } else {                                                            //avec session
            ?>
            <div class="user">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                if(empty($_SESSION['user_session']) || empty($_SESSION['user_session']['user_avatar'])){
                ?>
                <img class="rounded-circle" src="<?= HOST ?>public/images/avatar/default.png" alt="avatar user" width="50px" height="50px">
                <?php
              } else {
                ?>
                <img src="<?= HOST ?>public/images/avatar/<?= $_SESSION['user_session']['user_avatar'] ?>" class="rounded-circle" alt="avatar user" width="50px" height="50px">
                <?php
                }
                ?>
                <span>
                  <?=' Bonjour '.$_SESSION['user_session']['user_pseudo'] ?>
                </span>
              </button>
              <div class="dropdown-menu dropdown-menu-right bg-lg">
                <?php
                if($_SESSION['user_session']['user_status'] == 'admin'){
                ?>
                <form action="<?=HOST?>admin" method="post">
                  <button type="admin" name="admin" class="col-12 bg-white usrOption">Administrer le site</button>
                </form>
                <hr/>
                <?php
                }
                ?>
                <form action="<?=HOST?>editProfile" method="post">
                  <input type="hidden" name="userId" value="<?= $_SESSION['user_session']['user_id'] ?>">
                  <button type="logout" name="logout" class="col-12 bg-white usrOption">Editer mon Profil</button>
                </form>
                <hr/>
                <form action="<?=HOST?>logout" method="post">
                  <button type="logout" name="logout" class="col-12 bg-white usrOption">Se déconnecter</button>
                </form>
              </div>
            </div>
            <?php
            }
            ?>

        </div>
      </header>

        <?= $content ?>


      <footer>
        <div class="container-fluid bg-dark text-light row main">
          <div class="container row first">
            <div class="col-xs-12 col-sm-4 rounded about foot">
              <p><a href="<?=HOST?>about">L'auteur</a></p>
              <p><a href="<?=HOST?>about#bspalaska">L'extrait de l'ouvrage</a></p>
            </div>
            <div class="col-xs-12 col-sm-4 rounded links foot">
              <?php
              if(isset($_SESSION['user_session'])){
              ?>
              <h5>Ma Session</h5>
              <?php
                if($_SESSION['user_session']['user_status'] == 'admin'){
              ?>
              <p><a href="<?=HOST?>admin">Administrer le site</a></p>
              <?php
                }
              ?>
              <p><a href="<?=HOST?>editProfile">Editer mon profil</a></p>
              <p><a href="<?=HOST?>logout">Se déconnecter</a></p>
              <?php
            } else {
              ?>
              <p>Dennis Santillan - 2018</p>
              <?php
              }
              ?>
            </div>
            <div class="col-xs-12 col-sm-4 rounded disclaimer foot">
              <p>Ce site a été fait dans le cadre du Projet 4 : <b>"Créez un blog pour un écrivain"</b>
                 de la formation <em>Développeur Web Junior</em> avec <a href="https://openclassrooms.com" target="_blank" >OpenClassrooms</a>.</p>
            </div>
          </div>
        </div>

        <?php
        usleep(1);
        unset($_SESSION['comment']);
        unset($_SESSION['error']);
        unset($_SESSION['noUser']);
        ?>
      </footer>
      <script>
          $(".maj").click(function(){
            location.reload(true);
          });

          function timer(){
            setTimeout(function() {
              $(".timed").hide();
            },3000);
          };
          timer();

          $('#overlay').modal('show');
          setTimeout(function() {
              $('#overlay').modal('hide');
          }, 3000);
      </script>
    </body>
</html>

<div class="profil" id="<?= $datas["id"] ?>">
  <?php
  if (strpos($datas["avatar"], "http") !== false){
    echo '<img src="'.$datas["avatar"].'" alt="Photo du profil" width="330" height="400">'
  ;}
  else
  {
   echo
  '<img src="public/img/users/'.$datas["id"].'/'.$datas["avatar"].'" alt="Photo du profil" width="330" height="400">';
  }?>
  <div class="user_info d-flex align-items-center justify-content-between">
    <div class="d-flex flex-column">
      <div class="d-flex align-items-center justify-content-between" style="width:20vh;">
        <span><?= ucfirst($datas["firstname"]) ?>, <?= $datas["age"] ?></span>
        <img class="show_profil" src="public/img/icons/info.png" alt="Info" width="20" height="20">
      </div>
      <div class="d-flex align-items-center justify-content-between" style="width:20vh;">

        <div class="d-flex align-items-center">
          <img src="public/img/icons/location.png" alt="Localisation" width="20" height="20">
          <span style="margin-left: 5px;"><?= $datas["distance"]." km"?></span>
        </div>
        <div class="d-flex align-items-center">
          <img src="public/img/icons/score.png" alt="Score" width="20" height="20">
          <span style="margin-left: 5px;"> <?= $datas["score"] ?></span>
        </div>
      </div>
      <span> <?= ucfirst($datas["job"]) ?></span>
    </div>
  </div>
  <div class="d-flex justify-content-center icons_div">
    <div>
      <img class="like-icon" src="public/img/icons/<?= $datas["likeIcon"] ?>" alt="Like" width="30" height="30">
    </div>
  </div>
</div>

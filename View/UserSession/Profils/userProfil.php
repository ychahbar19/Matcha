
<section class="user_profil d-flex flex-column align-items-center">

  <header class="d-flex justify-content-between align-items-center">

    <div class="user_info_1 d-flex justify-content-around align-items-center">
      <?php
        if (strpos($datas["avatar"], "http") !== false){
          echo '<img src="'.$datas["avatar"].'" alt="Photo du profil" width="250" height="312">'
        ;}
        else
        {
         echo
        '<img src="public/img/users/'.$datas["id"].'/'.$datas["avatar"].'" alt="Photo du profil" width="250" height="312">';
        }
      ?>
      <div class="d-flex flex-column justify-content-around">
        <span class="user_name">
          <?= ucfirst($datas["firstname"]) . " " . ucfirst($datas["name"]) . ", " . $datas["age"]; ?>
        </span>
        <div class="d-flex align-items-center">
          <?php
            if ($datas["online_status"] == "online")
            {
              echo "<div class='circle circle_green'></div>";
              echo "<span class='user_status'>En ligne</span>";
            }
            else
            {
              echo "<div class='circle circle_red'></div>";
              echo "  <span class='user_status'>Hors ligne (". $datas["last_activity"] .")</span>";
            }
           ?>
        </div>
        <span class="user_job"><?= ucfirst($datas["job"]); ?></span>
        <div class="location">
          <img src="public/img/icons/location.png" alt="Localisation" width="20" height="20">
          <span><?php if ($datas['match_preferences']['location']['city'] ==="undefined by this free API"){
            echo $datas['match_preferences']['location']['country'];
            }
            else
              echo $datas['match_preferences']['location']['city'];?></span>
        </div>
      </div>
    </div>

    <div class="d-flex">
      <div class="settings_div signal_div">
        <img src="public/img/icons/error.png" alt="Signaler" width="35" height="35">
      </div>
      <div class="settings_div block_div">
        <img src="public/img/icons/forbidden.png" alt="Bloquer" width="35" height="35">
      </div>
    </div>
  </header>

  <div class="user_info_2 d-flex">

    <div class="bio_div">
      <h3>Bio :</h3>
      <?php
        if (!empty($datas["bio"]))
          echo "<p>" . $datas["bio"] . "</p>";
        else
          echo "<p>Aucune biographie pour le moment. Décris toi en quelques mots.</p>";
      ?>
    </div>

    <div class="user_info_3 d-flex flex-column align-items-start">
      <h3>Informations complémentaires :</h3>
      <span>Genre : <?= ucfirst($datas["gender"]) ?></span>
      <span>Orientation sexuelle :  <?= $datas['match_preferences']["interessedBy"]; ?></span>
      <span>Date de naissance: <?= (new \Datetime($datas["birthdate"]))->format("d-m-Y"); ?></span>
    </div>

  </div>

  <div class="user_interests d-flex flex-column align-items-start">
    <h3>Intérêts : </h3>
    <div class="interests_list d-flex justify-content-start flex-wrap">
<?php
      if (empty($datas["interests"]))
        echo "<p>Aucun centre d'intérêts pour le moment. Ajoute en au minimum 5 pour pouvoir matcher avec d'autres personnes.</p>";

      for ($i=0; $i < count($datas["interests"]); $i++)
      {
        $interest = $datas["interests"][$i];
        echo "<div class='interest' id='". $interest["id"] ."'><span>#". ucfirst($interest["interest"]) ."</span></div>";
      }
 ?>
    </div>
  </div>

  <div class="user_pictures">
    <h3>Photos :</h3>
    <?php
      if (empty($datas["pictures"]))
        echo "<p>Aucune photo pour le moment. Ajoute en au minimum une pour pouvoir matcher avec d'autres personnes.</p>";
     ?>
    <div class="pictures_list d-flex justify-content-start align-items-center flex-wrap">
      <?php
        if (isset($datas["pictures"]))
        {
          foreach ($datas["pictures"] as $picture)
          {
            if (strpos($picture, "http") !== false){
              echo '<img src="'.$picture.'" alt="Photo du profil" width="120" height="150">'
            ;}
            else
            {
             echo
            '<img src="public/img/users/'.$datas["id"].'/'.$picture.'" alt="Photo du profil" width="120" height="150">';
            }
          }
        }
       ?>
    </div>

  </div>

</section>

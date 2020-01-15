<div class="dropdown-item notif_div d-flex align-items-center justify-content-between <?= $datas["status"] ?>" id="<?= $datas["id"] ?>" style="">
  <?php
      echo "<img src='public/img/users/";
      echo $datas['id_sender'] . "/" . $datas['sender_avatar'] . "' class='dropdown_avatar' alt='Avatar' width='150' height='188'>";
  ?>

    <div class="notif_txt">
      <?php
        if ($datas["type"] == "match")
          echo "<p><span>". ucfirst($datas['type']) . " avec " . ucfirst($datas['sender_name']) . " !</span> </p>";
        else if ($datas["type"] == "message")
          echo "<p><span>" . ucfirst($datas['sender_name']) . " vous a envoyé un message ! </span> </p>";
        else
          echo "<p><span>" . ucfirst($datas["sender_name"]) . " a " . $datas['type'] . " votre profil</span></p>";
       ?>
      <em>Il y a <?= $datas["timeInterval"]  ?></em>
    </div>
</div>

<?php

namespace Matcha\Model;

use Matcha\Core\Model;
use Matcha\Core\Notification;

class NotificationManager extends Model
{

  public function add($notification)
  {
    $sqlReq = "INSERT INTO notification(id_sender, id_receiver, type, status, notif_date)
              VALUES(:id_sender, :id_receiver, :type, :status, NOW())";
    $params =  array(
      "id_sender" => $notification->id_sender(),
      "id_receiver" => $notification->id_receiver(),
      "type" => $notification->type(),
      "status" => "unseen",
    );
    $req = $this->setDbRequest($sqlReq, $params);

  }

  public function getAllUserNotifs($user_id)
  {
    $sqlReq = "SELECT * FROM notification WHERE id_receiver = ". $user_id . " ORDER BY id DESC";
    $req = $this->setDbRequest($sqlReq);
    $data = $req->fetchAll(\PDO::FETCH_ASSOC);

    return $data;
  }

  public function get($col, $val)
  {
    $sqlReq = "SELECT * FROM notification WHERE ". $col ."= ?";
    $req = $this->setDbRequest($sqlReq, array($val));
    $data = $req->fetch(\PDO::FETCH_ASSOC);

    return $data;
  }

  public function delete($user_id_1, $user_id_2, $type)
  {
    $sqlReq = "DELETE FROM notification WHERE id_sender = ?  AND id_receiver = ? AND type = ?";
    $req = $this->SetDbRequest($sqlReq, array($user_id_1, $user_id_2, $type));
  }


  public function hasMatch($user_id_1, $user_id_2, $type)
  {
    $sqlReq = "SELECT * FROM notification WHERE id_sender = ?  AND id_receiver = ? AND type = ?";

    $req1 = $this->setDbRequest($sqlReq, array($user_id_1, $user_id_2, $type));
    $req2 = $this->setDbRequest($sqlReq, array($user_id_2, $user_id_1, $type));

    $count1 = $req1->rowCount();
    $count2 = $req2->rowCount();

    return $count1 && $count2;
  }

  public function getNotif($user_id_1, $user_id_2, $type)
  {
    $sqlReq = "SELECT * FROM notification WHERE id_sender = ?  AND id_receiver = ? AND type = ?";

    $req = $this->setDbRequest($sqlReq, array($user_id_1, $user_id_2, $type));
    $count = $req->rowCount();

    return $count;
  }

  public function update($notif_id, $col, $val)
  {
    $sqlReq = "UPDATE notification SET ". $col ." = ? WHERE id = ?";
    $params = array($val, $notif_id);

    $req = $this->SetDbRequest($sqlReq, $params);
  }

  public function getAllUserNotifsUnseen($user_id)
  {
    $sqlReq = "SELECT * FROM notification WHERE id_receiver = ? AND status = ?";
    $req = $this->setDbRequest($sqlReq, array($user_id, "unseen"));


    $count = $req->rowCount();
    $data = $req->fetchAll(\PDO::FETCH_ASSOC);


    return $count;
  }


}

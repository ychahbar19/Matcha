<?php

namespace Matcha\Controller\UserSession;

use Matcha\Core\SessionController;
use Matcha\Model\NotificationManager;
use Matcha\Core\Notification;


class NotificationController extends SessionController
{
  public function __construct($request) { parent::__construct($request); }

  public function changeStatus()
  {
    $notif_id = htmlspecialchars($_POST["notifId"]);
    $this->notifManager->update($notif_id, "status", "seen");
    echo "OK";
  }

  public function checkNotif()
  {
    $notifCount = htmlspecialchars($_POST["notifCount"]);

    $count = $this->notifManager->getAllUserNotifsUnseen($_SESSION["id"]);

    if ($notifCount != $count)
    {
      $array = [];
      $newNotifs = $this->getAllUserNotifsDiv();
      array_push($array, $count);
      array_push($array, $newNotifs);
      echo json_encode($array, true);
    }
    else
      echo "NULL";
  }

  public function checkMatchArray()
  {

    $user =  $this->userManager->get("id", $_SESSION["id"]);

    if (!empty($user["match_array"]))
      $match_array = explode(", ", $user["match_array"]);
    else
      $match_array = [];

    if (!empty($_SESSION["match_array"]))
      $session_match_array = explode(", ", $_SESSION["match_array"]);
    else
      $session_match_array = [];

    if (count($session_match_array) !=  count($match_array))
    {
      $matchStr = implode(", ", $match_array);
      $this->userSession()->setAttribut("match_array", $user["match_array"]);
    }

  }

  private function _addToMatchArray($user, $user_id_match)
  {
    if (isset($user["match_array"]))
    {
      $match_array = explode(", ", $user["match_array"]);
      array_push($match_array, $user_id_match);
      $match_array = implode(", ", $match_array);
    }
    else
    {
      $match_array = $user_id_match;
    }
    $this->userManager->update($user["id"], "match_array", $match_array);
  }

  private function _removeToMatchArray($user, $user_id_match)
  {
    $match_array = explode(", ", $user["match_array"]);
    $i = array_search($user_id_match, $match_array);
    $match_array[$i] = "";
    $match_array = array_filter($match_array);
    $match_array = implode(", ", $match_array);

    $this->userManager->update($user["id"], "match_array", $match_array);
  }

  public function _sendMatchNotif($user1, $user2, $notif_datas, $score_array)
  {
    $this->notifManager->delete($notif_datas["idReceiver"], $notif_datas["idSender"], "like");
    $this->notifManager->delete($notif_datas["idSender"], $notif_datas["idReceiver"], "like");

    $this->_addToMatchArray($user1, $notif_datas["idSender"]);
    $this->_addToMatchArray($user2, $notif_datas["idReceiver"]);

    $match1 = new Notification($notif_datas["idReceiver"], $notif_datas["idSender"], "match");
    $match2 = new Notification($notif_datas["idSender"], $notif_datas["idReceiver"], "match");
    $this->notifManager->add($match1);
    $this->notifManager->add($match2);
    $score_array["senderScore"] += 20;
    $score_array["receiverScore"] += 40;

    // echo "Match ! ";

    return $score_array;
  }

  public function _deleteMatchNotif($user1, $user2, $notif_datas, $score_array)
  {
    $this->notifManager->delete($notif_datas["idReceiver"], $notif_datas["idSender"], "match");
    $this->notifManager->delete($notif_datas["idSender"], $notif_datas["idReceiver"], "match");
    $this->notifManager->add(new Notification($notif_datas["idSender"], $notif_datas["idReceiver"], "like"));

    $this->_removeToMatchArray($user1, $notif_datas["idSender"]);
    $this->_removeToMatchArray($user2, $notif_datas["idReceiver"]);

    if (($score_array["receiverScore"] - 40) > 0)
      $score_array["receiverScore"] -= 40;
    else
      $score_array["receiverScore"] = 0;

    // echo "DISMatch ! ";

    return $score_array;
  }

  public function addNotif()
  {
    $notif_datas = array(
      "idSender" => $_SESSION["id"],
      "idReceiver" => htmlspecialchars($_POST["IdUserCard"]),
      "type" => htmlspecialchars($_POST["type"])
    );

    $notif = new Notification($notif_datas["idReceiver"], $notif_datas["idSender"], $notif_datas["type"]);
    $user1 =  $this->userManager->get("id", $notif_datas["idReceiver"]);
    $user2 = $this->userManager->get("id", $notif_datas["idSender"]);
    $score_array = array( "senderScore" => $user2["score"], "receiverScore" => $user1["score"] );

    if ($notif_datas["type"] == "like")
    {
      $this->notifManager->add($notif);

      $hasMatch = $this->notifManager->hasMatch($notif_datas["idSender"], $notif_datas["idReceiver"], "like");
      if ($hasMatch)
      {
        $score_array = $this->_sendMatchNotif($user1, $user2, $notif_datas, $score_array);
      }
      else
      {
        if (($score_array["receiverScore"] + 20) < 500)
          $score_array["receiverScore"] += 20;
        else
          $score_array["receiverScore"] = 0;
      }
      // echo "Like";
    }
    else if ($notif_datas["type"] == "dislike")
    {
      $hasMatch = $this->notifManager->hasMatch($notif_datas["idSender"], $notif_datas["idReceiver"], "match");
      if ($hasMatch)
        $score_array = $this->_deleteMatchNotif($user1, $user2, $notif_datas, $score_array);
      else
      {
        $this->notifManager->delete($notif_datas["idSender"], $notif_datas["idReceiver"], "like");

        if (($score_array["receiverScore"] - 20) > 0)
          $score_array["receiverScore"] -= 20;
        else
          $score_array["receiverScore"] = 0;
      }
      $this->notifManager->add($notif);
      // echo "Dislike";
    }
    else if ($notif_datas["type"] == "visit")
    {
      $this->notifManager->add($notif);
      if (($score_array["receiverScore"] + 10) < 500)
        $score_array["receiverScore"] += 10;
      else
        $score_array["receiverScore"] = 0;
      // echo "Visit !";
    }
    else if ($notif_datas["type"] == "message")
    {
      $user =  $this->userManager->get("login", $notif_datas["idReceiver"]);
      $notif_datas["idReceiver"] = $user["id"];
      $notif = new Notification($notif_datas["idReceiver"], $notif_datas["idSender"], $notif_datas["type"]);
      $this->notifManager->add($notif);
    }

    if ($score_array["senderScore"] != $user1["score"])
      $this->userManager->update($notif_datas["idSender"], "score", $score_array["senderScore"]);

    $this->userManager->update($notif_datas["idReceiver"], "score", $score_array["receiverScore"]);


  }

}

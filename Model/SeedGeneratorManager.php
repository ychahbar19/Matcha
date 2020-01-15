<?php

namespace Matcha\Model;

use Matcha\Core\Model;
use Matcha\Core\User;
use \PDO;

/**
 *  seed generator manager class that sends fake profiles to the DB
 */

class SeedGeneratorManager extends Model
{
  public function add($user)
  {
      $sqlReq = "INSERT INTO users(login, name, firstname, email, password, gender, match_preferences, interests, birthdate, avatar, pictures, activated, online_status, last_activity) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $params = array(
        $user['login'],
        $user['name'],
        $user['firstname'],
        $user['email'],
        $user['password'],
        $user['gender'],
        $user['$match_preferences'],
        $user['interests'],
        $user['birthdate'],
        $user['avatar'],
        $user['avatar'],
        $user['activated'],
        $user["online_status"],
        $user["last_activity"]
      );
      $req = $this->setDbRequest($sqlReq, $params);
  }
}

?>

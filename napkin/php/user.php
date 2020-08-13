<?php
/*©agpl*************************************************************************
*                                                                              *
* Napkin Solar – Napkin-Visual based analysis tool for the solar energy market *
* Copyright (C) 2020  Napkin AS                                                *
*                                                                              *
* This program is free software: you can redistribute it and/or modify         *
* it under the terms of the GNU Affero General Public License as published by  *
* the Free Software Foundation, either version 3 of the License, or            *
* (at your option) any later version.                                          *
*                                                                              *
* This program is distributed in the hope that it will be useful,              *
* but WITHOUT ANY WARRANTY; without even the implied warranty of               *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                 *
* GNU Affero General Public License for more details.                          *
*                                                                              *
* You should have received a copy of the GNU Affero General Public License     *
* along with this program.  If not, see <http://www.gnu.org/licenses/>.        *
*                                                                              *
*****************************************************************************©*/

session_start();

//error_reporting(E_ALL); ini_set('display_errors',1); ini_set('error_reporting', E_ALL); ini_set('display_startup_errors',1); error_reporting(-1);

include "utils/init_db.php";
include_once "utils/validateUID.php";
include_once "utils/security.php";
include_once "utils/getUser.php";
include_once "utils/updateUser.php";
include_once "utils/deleteUser.php";


if(!isset($_SESSION['uid'])
|| !validateUID($pdo, $_SESSION['uid'])) {
  http_response_code(401);
  exit;
}

$uid = $_SESSION['uid'];


if(!isset($_REQUEST['op'])) {
  http_response_code(422);
	exit;
}

$op = $_REQUEST['op'];


if($op == "create")
{

  // NOTE: REST API should NOT be able to create users.
  //       This is done in the Active Directory on in the LDAP system
  http_response_code(401);

}
else
if($op == "get")
{

  $res = getUser($pdo, $uid);

  if(isset($_GET['uid'])) {
    if($res['type'] != "admin") {
      http_response_code(401);
      exit;
    }

    $res = getUser($pdo, $_GET['uid']);
  }

  echo json_encode($res);

}
else
if($op == "update")
{

  if(!isset($_POST['username'])
  || !isset($_POST['email'])) {
    http_response_code(422);
    exit;
  }

  $username = $_POST['username'];
  $email = $_POST['email'];

  $res = updateUser($pdo, $uid, $username, $email);

  echo json_encode($res);

}
else
if($op == "update_passwd")
{

  if(!isset($_POST['passwd'])) {
    http_response_code(422);
    exit;
  }

  $passwd = $_POST['passwd'];

  $stmt = $pdo->prepare("UPDATE \"User\" SET passwd = ? WHERE userid = ?");
  $res = $stmt->execute([$passwd, $uid]);

  echo json_encode($res);

}
else
if($op == "delete")
{

  $res = deleteUser($pdo, $_POST['uid']);

  if($res) http_response_code(200);
  else http_response_code(500);

}
else
{
  http_response_code(501);
}

exit;

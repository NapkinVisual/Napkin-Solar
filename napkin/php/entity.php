<?php
/*©agpl*************************************************************************
*                                                                              *
* Napkin Visual – Visualisation platform for the Napkin platform               *
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
* along with this program. If not, see <http://www.gnu.org/licenses/>.         *
*                                                                              *
*****************************************************************************©*/

session_start();

//error_reporting(E_ALL); ini_set('display_errors',1); ini_set('error_reporting', E_ALL); ini_set('display_startup_errors',1); error_reporting(-1);
// uniqid();

include "utils/init_db.php";
include_once "utils/validateUID.php";
include_once "utils/security.php";
include_once "utils/createEntity.php";
include_once "utils/getEntity.php";
include_once "utils/getAllEntities.php";
include_once "utils/updateEntity.php";
include_once "utils/deleteEntity.php";


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

  if(!isset($_POST['name'])
  || !isset($_POST['description'])) {
    http_response_code(422);
    exit;
  }

  $name = $_POST['name'];
  $description = $_POST['description'];

  $res = createEntity($pdo, $name, $description, $uid);

  echo json_encode($res);

}
else
if($op == "get"
|| $op == "get_all"
|| $op == "get_data"
|| $op == "share"
|| $op == "update"
|| $op == "delete")
{

  if(!isset($_REQUEST['eid'])) {
    http_response_code(422);
    exit;
  }

  $eid = $_REQUEST['eid'];
  $accessControl = null;

  if($op == "get"
  || $op == "get_all"
  || $op == "get_data")
  {
    $accessControl = entityGetAccess($pdo, $uid, $eid);
  }
  else
  if($op == "share"
  || $op == "update"
  || $op == "delete")
  {
    $accessControl = entitySetAccess($pdo, $uid, $eid);
  }


  if(!$accessControl) {
    http_response_code(401);
    exit;
  }



  if($op == "get")
  {

    $eid = $_GET['eid'];

    $res = getEntity($pdo, $eid);

    echo json_encode($res);

  }
  else
  if($op == "get_all")
  {

    $eid = $_GET['eid'];

    $res = array( "info" => getEntity($pdo, $eid) );

    $stmt = $pdo->prepare(
      "SELECT
        U.username,
        U.email
      FROM
        \"User_Entity\" AS UE INNER JOIN
        \"User\" AS U
          ON UE.userid = U.userid
      WHERE
        UE.userid != ? AND
        UE.entityid = ?"
    );
    $stmt->execute([$uid, $eid]);
    $rows = $stmt->fetchAll();

    $res['shared'] = $rows;

    echo json_encode($res);

  }
  else
  if($op == "get_data")
  {

    $eid = $_GET['eid'];

    $stmt = $pdo->prepare("SELECT data FROM \"Entity\" WHERE entityid = ?");
    $stmt->execute([$eid]);
    $res = $stmt->fetch();

    echo json_encode($res['data']);

  }
  else
  if($op == "share")
  {

    if(!isset($_POST['shareId'])) {
      http_response_code(422);
      exit;
    }

    $eid = $_POST['eid'];
    $shareId = $_POST['shareId'];

    $stmt = $pdo->prepare("INSERT INTO \"User_Entity\" (userid, entityid, status) VALUES (?, ?, ?)");
    $res = $stmt->execute([$shareId, $eid, 'viewer']);

    if($res) http_response_code(200);
    else http_response_code(500);

  }
  else
  if($op == "update")
  {

    if(!isset($_POST['name'])
    || !isset($_POST['description'])) {
      http_response_code(422);
      exit;
    }

    $eid = $_POST['eid'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    $res = updateEntity($pdo, $eid, $name, $description);

    if($res) http_response_code(200);
    else http_response_code(500);

  }
  else
  if($op == "delete")
  {

    $eid = $_POST['eid'];

    $res = deleteEntity($pdo, $eid);

    if($res) http_response_code(200);
    else http_response_code(500);

  }

}
else
{
  http_response_code(501);
}

exit;

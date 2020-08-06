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

include "utils/init_db.php";
include_once "utils/validateUID.php";
include_once "utils/security.php";
include_once "utils/createStock.php";
include_once "utils/getStock.php";
include_once "utils/updateStock.php";
include_once "utils/deleteStock.php";


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

  if(!isset($_POST['eid'])
  || !isset($_POST['type'])
  || !isset($_POST['name'])) {
    http_response_code(422);
    exit;
  }

  $eid = $_POST['eid'];
  $type = $_POST['type'];
  $name = $_POST['name'];

  $res = createStock($pdo, $eid, $type, $name);

  echo json_encode($res);

}
else
if($op == "get"
|| $op == "update"
|| $op == "delete")
{

  if(!isset($_REQUEST['sid'])) {
    http_response_code(422);
    exit;
  }

  $sid = $_REQUEST['sid'];
  $accessControl = stockAccess($pdo, $uid, $sid); // TODO: implement security checks in LDAP system


  if(!$accessControl) {
    http_response_code(401);
    exit;
  }



  if($op == "get")
  {

    $sid = $_GET['sid'];

    $res = getStock($pdo, $sid);
    //$res['owner'] = getDatasourceOwner($pdo, $sid);

    echo json_encode($res);

  }
  else
  if($op == "update")
  {

    if(!isset($_POST['name'])
    || !isset($_POST['amount'])) {
      http_response_code(422);
      exit;
    }

    $sid = $_POST['sid'];
    $name = $_POST['name'];
    $amount = $_POST['amount'];

    $res = updateStock($pdo, $sid, $name, $amount);

    if($res) http_response_code(200);
    else http_response_code(500);

  }
  else
  if($op == "delete")
  {

    $sid = $_POST['sid'];

    $res = deleteStock($pdo, $sid);

    if($res) http_response_code(200);
    else http_response_code(500);

  }

}
else
{
  http_response_code(501);
}

exit;

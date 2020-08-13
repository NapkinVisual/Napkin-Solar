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
* along with this program. If not, see <http://www.gnu.org/licenses/>.         *
*                                                                              *
*****************************************************************************©*/

session_start();

include "utils/init_db.php";
include_once "utils/validateUID.php";
include_once "utils/security.php";
include_once "utils/createLog.php";
include_once "utils/getLog.php";


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

  if(!isset($_POST['entityId'])
  || !isset($_POST['entityType'])
  || !isset($_POST['type'])
  || !isset($_POST['description'])) {
    http_response_code(422);
    exit;
  }

  $entityId = $_POST['entityId'];
  $entityType = $_POST['entityType'];
  $type = $_POST['type'];
  $description = $_POST['description'];

  $res = createLog($pdo, $entityId, $entityType, $type, $description);

  echo json_encode($res);

}
else
if($op == "get")
{

  if(!isset($_REQUEST['lid'])) {
    http_response_code(422);
    exit;
  }

  $lid = $_REQUEST['lid'];


  if($op == "get")
  {

    $lid = $_GET['lid'];

    $res = getLog($pdo, $lid);

    echo json_encode($res);

  }

}
else
{
  http_response_code(501);
}

exit;

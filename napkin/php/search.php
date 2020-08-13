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


if(!isset($_SESSION['uid'])
|| !validateUID($pdo, $_SESSION['uid'])) {
  http_response_code(401);
  exit;
}

$uid = $_SESSION['uid'];


if(!isset($_REQUEST['op'])
|| !isset($_REQUEST['term'])) {
  http_response_code(422);
	exit;
}

$op = $_REQUEST['op'];
$term = $_REQUEST['term'];


if(empty($term)) {
  exit;
}


if($op == "user")
{

  $stmt = $pdo->prepare(
    "SELECT
      U.*
    FROM
      \"User\" AS U LEFT JOIN
      \"User_Entity\" AS UE
        ON U.userid = UE.userid
    WHERE
      U.username LIKE ? AND
      U.userid != ? AND
      UE.userid IS NULL"
  );
  $stmt->execute(["$term%", $uid]);
  $res = $stmt->fetchAll();

  echo json_encode($res);

}
else
if($op == "all")
{

  $res = null;//createGroup($pdo, $uid, $name, $description);

  echo json_encode($res);

}
else
{
  http_response_code(501);
}

exit;

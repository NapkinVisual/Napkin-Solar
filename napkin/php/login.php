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
* along with this program.  If not, see <http://www.gnu.org/licenses/>.        *
*                                                                              *
*****************************************************************************©*/

session_start();

include "utils/init_db.php";


if(!isset($_POST['email'])
|| !isset($_POST['passwd'])) {
  http_response_code(422);
  exit;
}


$email = $_POST['email'];
$passwd = $_POST['passwd'];

// TODO: implement with LDAP

$stmt = $pdo->prepare("SELECT userid FROM \"User\" WHERE email = ? AND passwd = ?");
$stmt->execute([$email, $passwd]);
$num = $stmt->rowCount();

$res = null;
if($num == 1) {
  $row = $stmt->fetch();
  $userId = $row['userid'];

  $_SESSION['uid'] = $userId;

  $res = array(
    "status" => "passed",
    "userId" => $userId
  );
}else{
  $res = array(
    "status" => "failed"
  );
}

echo json_encode($res);

exit;

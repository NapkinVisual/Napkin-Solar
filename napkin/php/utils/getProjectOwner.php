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

//include "init_db.php";

include_once "getUser.php";


function getProjectOwner($pdo, $pid) {
  $stmt = $pdo->prepare("SELECT userid FROM \"User_Project\" WHERE status = 'owner' AND projectid = ?");
  $stmt->execute([$pid]);
  $num = $stmt->rowCount();
  $row = $stmt->fetch();

  if($num > 1) throw new Exception("Corrupted data in database; more than one owner found", 1);
  else if($num == 1) return getUser($pdo, $row['userid']);
}

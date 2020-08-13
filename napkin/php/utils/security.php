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

include_once "getUser.php";


/**
 * Checks if the user has entry in User_Entity table
 * i.e. if entity is shared with this user individually
 *
 * @param uid userId
 * @param eid entityId
 */
function entityGetAccess($pdo, $uid, $eid) {
  $user = getUser($pdo, $uid);
  if($user['type'] == "admin") return true;

  $stmt = $pdo->prepare("SELECT id FROM \"User_Entity\" WHERE userid = ? AND entityid = ?");
  $stmt->execute([$uid, $eid]);
  $num = $stmt->rowCount();

  if($num < 1) return false;

  return true;
}


/**
 * Checks if the user is the owner of the entity
 *
 * @param uid userId
 * @param eid entityId
 */
function entitySetAccess($pdo, $uid, $eid) {
  $user = getUser($pdo, $uid);
  if($user['type'] == "admin") return true;

  $stmt = $pdo->prepare("SELECT userid FROM \"User_Entity\" WHERE entityid = ? AND status = 'owner'");
  $stmt->execute([$uid, $eid]);
  $num = $stmt->rowCount();

  if($num != 1) throw new Exception("Corrupt data in \"User_Entity\"", 1);

  $row = $stmt->fetch();
  $isOwner = $uid == $row['userid'];

  return $isOwner;
}

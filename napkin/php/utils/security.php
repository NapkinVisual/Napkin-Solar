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
include_once "getProjectOwner.php";


/**
 * Checks if the user has entry in User_Project table
 * i.e. if project is shared with this user individually
 *
 * @param uid userId
 * @param pid projectId
 */
function entityGetAccess($pdo, $uid, $pid) {
  $user = getUser($pdo, $uid);
  if($user['type'] == "admin") return true;

  $stmt = $pdo->prepare("SELECT id FROM \"User_Project\" WHERE userid = ? AND projectid = ?");
  $stmt->execute([$uid, $pid]);
  $num = $stmt->rowCount();

  if($num < 1) return false;

  return true;
}


/**
 * Checks if the user is the owner of the project
 *
 * @param uid userId
 * @param pid projectId
 */
function entitySetAccess($pdo, $uid, $pid) {
  $user = getUser($pdo, $uid);
  if($user['type'] == "admin") return true;

  $owner = getProjectOwner($pdo, $pid);
  $isOwner = $uid == $owner['userid'];

  return $isOwner;
}



/**
 * Checks if the user has read/write access to a datasource
 *
 * @param uid userId
 * @param did datasource id
 */
function stockAccess($pdo, $uid, $did) {
  $user = getUser($pdo, $uid);
  if($user['type'] == "admin") return true;

  $stmt = $pdo->prepare("SELECT id FROM \"Datasource\" WHERE ownerid = ? AND id = ?");
  $stmt->execute([$uid, $did]);
  $num = $stmt->rowCount();

  if($num == 1) return true;

  return false;
}

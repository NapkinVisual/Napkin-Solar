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

//include "init_db.php";


function createEntity($pdo, $name, $description, $ownerId) {
  $stmt = $pdo->prepare("INSERT INTO \"Entity\" (name, description, data) VALUES (?, ?, ?, ?) RETURNING entityid");
  $res = $stmt->execute([
    $name,
    $description,
    '{}'
  ]);

  if(!$res) throw new Exception("Failed to execute insert on \"Entity\"", 1);

  $row = $stmt->fetch();
  $entityId = $row['entityid'];


  $stmt = $pdo->prepare("INSERT INTO \"User_Entity\" (userid, entityid, status) VALUES (?, ?, ?)");
  $res = $stmt->execute([$ownerId, $entityId, 'owner']);

  if(!$res) throw new Exception("Failed to execute insert on \"User_Entity\"", 1);

  return array(
    "entityId" => $entityId
  );
}

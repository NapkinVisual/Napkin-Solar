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

//include "init_db.php";


function searchUser($pdo, $uid, $username, $dateFrom, $dateTo) {
  $sql = "SELECT
    userid,
    type,
    username,
    email,
    created_on,
    last_login
  FROM
    \"User\"
  WHERE
    userid != ? AND
    username LIKE ?";

  if(!empty($dateFrom))
    $sql .= " AND created_on > TO_TIMESTAMP(?, 'MM/DD/YYYY')";

  if(!empty($dateTo))
    $sql .= " AND created_on < TO_TIMESTAMP(?, 'MM/DD/YYYY')";

  $sql .= "
    ORDER BY
      created_on DESC
  ";


  $params = [$uid, "$username%"];

  if(!empty($dateFrom))
    array_push($params, $dateFrom);

  if(!empty($dateTo))
    array_push($params, $dateTo);

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $rows = $stmt->fetchAll();

  return $rows;
}

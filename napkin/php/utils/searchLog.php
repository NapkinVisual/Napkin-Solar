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


function searchLog($pdo, $uid, $dateFrom, $dateTo, $type) {
  $sql = "SELECT
    L.*
  FROM
    (
      SELECT :userid AS entityid

      UNION

      SELECT projectid
      FROM \"User_Project\"
      WHERE userid = :userid

      UNION

      SELECT id
      FROM \"Datasource\"
      WHERE ownerid = :userid
    ) AS Sub INNER JOIN
    \"Log\" AS L
      ON Sub.entityid = L.entityid
  WHERE
    1 = 1";

  if(!empty($dateFrom))
    $sql .= " AND L.timestamp > TO_TIMESTAMP(:dateFrom, 'MM/DD/YYYY')";

  if(!empty($dateTo))
    $sql .= " AND L.timestamp < TO_TIMESTAMP(:dateTo, 'MM/DD/YYYY')";

  if(!empty($type))
    $sql .= " AND L.type = :type";

  $sql .= "
    ORDER BY
      L.timestamp DESC
  ";


  $params = array(
    "userid" => $uid,
  );

  if(!empty($dateFrom))
    $params['dateFrom'] = $dateFrom;

  if(!empty($dateTo))
    $params['dateTo'] = $dateTo;

  if(!empty($type))
    $params['type'] = $type;

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $rows = $stmt->fetchAll();

  return $rows;
}

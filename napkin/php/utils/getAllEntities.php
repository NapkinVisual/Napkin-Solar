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

//include_once "getProject.php";


function getAllEntities($pdo, $uid) {
  $stmt = $pdo->prepare(
    "SELECT
      E.entityid,
      E.name,
      E.description,
      E.created_on,
      E.data
    FROM
      \"User_Entity\" AS UE INNER JOIN
      \"Entity\" AS E
        ON UE.entityid = E.entityid
    WHERE
      UE.userid = ?
    ORDER BY
      E.created_on DESC"
  );
  $stmt->execute([$uid]);
  $rows = $stmt->fetchAll();

  return $rows;
}

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

--

BEGIN;

INSERT INTO "User" (type, username, email, passwd)
VALUES
  (
    'admin',
    'andreas',
    'andreas@napkingis.no',
    '240f2ec6918381f9e7393b18539f8a2c8bc60b3224004a37ee7057a62abc2efa'
  );

INSERT INTO "Entity" (name, description, data)
VALUES
  (
    'main',
    'main entity',
    '{}'
  );

END;



BEGIN;

INSERT INTO "Log" (entityid, entitytype, type, description)
VALUES
  (
    (SELECT entityid from "Entity" LIMIT 1),
    'Entity',
    'create',
    'Create Entity – Added a new entity; main'
  );

END;



BEGIN;

INSERT INTO "User_Entity" (userid, entityid, status)
VALUES
  (
    (SELECT userid from "User" LIMIT 1),
    (SELECT entityid from "Entity" LIMIT 1),
    'owner'
  );

END;

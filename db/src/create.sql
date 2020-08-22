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

/** commands to run before this script:
 *
 * $ CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
 * $ CREATE EXTENSION IF NOT EXISTS "postgis";
 */

BEGIN;

CREATE TABLE IF NOT EXISTS "User"(
	userid uuid DEFAULT uuid_generate_v4(),
	type varchar(10) DEFAULT 'user',
	username varchar(45) NOT NULL,
	email varchar(45),
	passwd varchar(64) NOT NULL,
	created_on timestamp DEFAULT NOW(),
	last_login timestamp DEFAULT NOW(),

	PRIMARY KEY (userid)
);

END;

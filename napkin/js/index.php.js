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

"use strict";

function login(ev) {
  let email = $("input#email").val(),
      passwd = $("input#password").val();

  $.ajax({
    type: "POST",
    url: "php/login",
    data: {
      "email": email,
      "passwd": sha256(passwd)
    },
    dataType: "json",
    success: function(result, status, xhr) {
      if(result.status === "passed")
        window.location.assign("/napkin/main");

      else if(result.status === "failed")
        window.location.reload();
    },
    error: function(xhr, status, error) {
      console.log(xhr.status);
      console.log(error);

      window.location.assign("/napkin");
    }
  });
}

window.addEventListener("load", function() {
  $("button#login").click(login);

  $(document).on("keypress", function(ev) {
    if(ev.which === 13)
      login(ev);
  });
});

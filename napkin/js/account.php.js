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

"use strict";

window.addEventListener("load", function() {
  feather.replace();
  init();


  $("button#save").click(function(ev) {
    let username = $("input#username").val(),
        email = $("input#email").val();

    $("#loadingModal").modal("show");

    $.ajax({
      type: "POST",
      url: "php/user",
      data: {
        "op": "update",
        "username": username,
        "email": email
      },
      success: function(result, status, xhr) {
        console.log(status);

        setTimeout(function() {
          $("#loadingModal").modal("hide");
          window.location.reload();
        }, 500);
      },
      error: function(xhr, status, error) {
        console.log(xhr.status);
        console.log(error);
      }
    });
  });


  $("button#changePasswd").click(function(ev) {
    let passwd = $("input#changePasswd").val(),
        confirmPasswd = $("input#confirmPasswd").val();

    if(passwd !== confirmPasswd) {
      $("input#changePasswd").addClass("is-invalid");
      $("input#confirmPasswd").addClass("is-invalid");
      return;
    }

    $("#loadingModal").modal("show");

    $.ajax({
      type: "POST",
      url: "php/user",
      data: {
        "op": "update_passwd",
        "passwd": sha256(passwd)
      },
      dataType: "json",
      success: function(result, status, xhr) {
        console.log(status);

        setTimeout(function() {
          $("#loadingModal").modal("hide");
          window.location.reload();
        }, 500);
      },
      error: function(xhr, status, error) {
        console.log(xhr);
        console.log(xhr.status);
        console.log(error);
      }
    });
  });
});

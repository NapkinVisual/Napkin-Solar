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


  $("#projectRow #projectInfo").click(function(ev) {
    let projectid = $(this).attr("data-projectid");

    $("#loadingModal").modal("show");

    $.ajax({
      type: "GET",
      url: "php/project",
      data: {
        "op": "get_all",
        "pid": projectid
      },
      dataType: "json",
      success: function(result, status, xhr) {
        let data = result;
        let project = data.info;

        let date = new Date(project.created_on);
        $("#projectInfoModal #projectName").html(project.name);
        $("#projectInfoModal #projectDescription").html(project.description);
        $("#projectInfoModal #projectCreatedOn").html(date.toDateString());

        let res = ``;
        for(let u of data.shared) {
          res += `${u.username} &nbsp;&nbsp; <em>${u.email}</em>, <br />`;
        }
        if(res == "") res = "<em>No users shared with</em>";
        $("#projectInfoModal #projectShared").html(res);

        setTimeout(function() {
          $("#loadingModal").modal("hide");

          $("#projectInfoModal").modal("show");
        }, 500);
      },
      error: function(xhr, status, error) {
        console.log(xhr.status);
        console.log(error);
      }
    });
  });


  $("#userRow #userInfo").click(function(ev) {
    let userid = $(this).attr("data-userid");

    $("#loadingModal").modal("show");

    $.ajax({
      type: "GET",
      url: "php/user",
      data: {
        "op": "get",
        "uid": userid
      },
      dataType: "json",
      success: function(result, status, xhr) {
        let user = result;

        let createdOn = new Date(user.created_on);
        let lastLogin = new Date(user.last_login);
        $("#userInfoModal #username").html(user.username);
        $("#userInfoModal #userEmail").html(user.email);
        $("#userInfoModal #userLastLogin").html(lastLogin.toDateString());
        $("#userInfoModal #userCreatedOn").html(createdOn.toDateString());

        setTimeout(function() {
          $("#loadingModal").modal("hide");

          $("#userInfoModal").modal("show");
        }, 500);
      },
      error: function(xhr, status, error) {
        console.log(xhr.status);
        console.log(error);
      }
    });
  });


  $("#userRow #userDelete").click(function(ev) {
    let userid = $(this).attr("data-userid");
    $("#deleteUserModal button#deleteUser").attr("data-userid", userid);
  });

  $("#deleteUserModal button#deleteUser").click(function(ev) {
    let userid = $(this).attr("data-userid");

    $("#loadingModal").modal("show");

    $.ajax({
      type: "POST",
      url: "php/user",
      data: {
        "op": "delete",
        "uid": userid
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
});

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


  $("#createdFrom").datepicker({ orientation: "bottom" });
  $("#createdTo").datepicker({ orientation: "bottom" });


  $("#newEntityModal form#createEntityForm").submit(function(ev) {
    ev.preventDefault();

    $("#newEntityModal").modal("hide");

    let name = $("#newEntityModal input#entityName").val(),
        description = $("#newEntityModal textarea#entityDescription").val();

    $("#loadingModal").modal("show");

    $.ajax({
      type: "POST",
      url: "php/entity",
      data: {
        "op": "create",
        "name": name,
        "description": description,
        "aoi": aoi
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


  $("#entityRow #infoEntity").click(function(ev) {
    let entityid = $(this).attr("data-entityid");

    $("#loadingModal").modal("show");

    $.ajax({
      type: "GET",
      url: "php/entity",
      data: {
        "op": "get_all",
        "eid": entityid
      },
      dataType: "json",
      success: function(result, status, xhr) {
        let data = result;
        let entity = data.info;

        let date = new Date(entity.created_on);
        $("#infoModal #entityName").html(entity.name);
        $("#infoModal #entityDescription").html(entity.description);
        $("#infoModal #entityCreatedOn").html(date.toDateString());

        let res = ``;
        for(let u of data.shared) {
          res += `${u.username} &nbsp;&nbsp; <em>${u.email}</em>, <br />`;
        }
        if(res == "") res = "<em>No users shared with</em>";
        $("#infoModal #entityShared").html(res);

        setTimeout(function() {
          $("#loadingModal").modal("hide");

          $("#infoModal").modal("show");
        }, 500);
      },
      error: function(xhr, status, error) {
        console.log(xhr.status);
        console.log(error);
      }
    });
  });


  $("#entityRow #shareEntity").click(function(ev) {
    let entityid = $(this).attr("data-entityid");
    $("#shareEntityModal form#shareEntityForm").attr("data-entityid", entityid);
  });

  let doneTypingInterval = 700, typingTimer;
  $("#shareEntityModal input#shareName").on("keyup", function(ev) {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => doneTyping(this), doneTypingInterval);
  });
  $("#shareEntityModal input#shareName").on("keydown", (ev) => clearTimeout(typingTimer));

  function doneTyping(self) {
    let term = $(self).val();

    if(term == "") return;

    $.ajax({
      type: "GET",
      url: "php/search",
      data: {
        "op": "user",
        "term": term
      },
      dataType: "json",
      success: function(result, status, xhr) {
        let data = result, res;

        res = ``;
        for(let u of data) {
          let date = new Date(u.created_on);
          res += `
            <button
              type=\"submit\"
              class=\"list-group-item list-group-item-action\"
              data-sharetype=\"user\"
              data-shareid=\"${u.userid}\"
            >
              <div class=\"d-flex w-100 justify-content-between\">
                <h5 class=\"mb-1\">${u.username}</h5>
                <small>${date.toDateString()}</small>
              </div>
              <p class=\"mb-1\">${u.email}</p>
            </button>
          `;
        }

        if(res !== "")
          $("#shareEntityModal #userList").html(res);
        else
          $("#shareEntityModal #userList").html(`
            <button type=\"button\" class=\"list-group-item list-group-item-action\" disabled>No entities found</button>
          `);
      },
      error: function(xhr, status, error) {
        console.log(xhr.status);
        console.log(error);
      }
    });
  }

  $("#shareEntityModal form#shareEntityForm").submit(function(ev) {
    ev.preventDefault();

    $("#shareEntityModal").modal("hide");

    let btn = ev.originalEvent.submitter;

    let entityid = $(this).attr("data-entityid"),
        shareType = $(btn).attr("data-sharetype"),
        shareId = $(btn).attr("data-shareid");

    $("#loadingModal").modal("show");

    $.ajax({
      type: "POST",
      url: "php/entity",
      data: {
        "op": "share",
        "eid": entityid,
        "shareId": shareId,
        "shareType": shareType
      },
      success: function(result, status, xhr) {
        console.log(status);

        setTimeout(function() {
          $("#loadingModal").modal("hide");
        }, 500);
      },
      error: function(xhr, status, error) {
        console.log(xhr.status);
        console.log(error);
      }
    });
  });


  $("#entityRow #editEntity").click(function(ev) {
    let entityid = $(this).attr("data-entityid");
    $("#editEntityModal button#editEntity").attr("data-entityid", entityid);

    $.ajax({
      type: "GET",
      url: "php/entity",
      data: {
        "op": "get",
        "eid": entityid
      },
      dataType: "json",
      success: function(result, status, xhr) {
        let data = result;

        $("#editEntityModal input#entityName").val(data.name);
        $("#editEntityModal textarea#entityDescription").val(data.description);
      },
      error: function(xhr, status, error) {
        console.log(xhr.status);
        console.log(error);
      }
    });
  });

  $("#editEntityModal form#editEntityForm").submit(function(ev) {
    ev.preventDefault();

    $("#editEntityModal").modal("hide");

    let entityid = $("#editEntityModal button#editEntity").attr("data-entityid"),
        name = $("#editEntityModal input#entityName").val(),
        description = $("#editEntityModal textarea#entityDescription").val();

    $("#loadingModal").modal("show");

    $.ajax({
      type: "POST",
      url: "php/entity",
      data: {
        "op": "update",
        "eid": entityid,
        "name": name,
        "description": description
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


  $("#entityRow #deleteEntity").click(function(ev) {
    let entityid = $(this).attr("data-entityid");
    $("#deleteEntityModal button#deleteEntity").attr("data-entityid", entityid);
  });

  $("#deleteEntityModal button#deleteEntity").click(function(ev) {
    let entityid = $(this).attr("data-entityid");

    $("#loadingModal").modal("show");

    $.ajax({
      type: "POST",
      url: "php/entity",
      data: {
        "op": "delete",
        "eid": entityid
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

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


  $("#newStockModal form#createStockForm").submit(function(ev) {
    ev.preventDefault();

    $("#newStockModal").modal("hide");

    let type = getFileType(_FILE),
        name = $("#newStockModal input#stockName").val(),
        description = $("#newStockModal textarea#stockDescription").val();

    $("#loadingModal").modal("show");

    $.ajax({
      type: "POST",
      url: "php/stock",
      data: {
        "op": "create",
        "type": type,
        "name": name,
        "amount": 0
      },
      contentType: false,
      processData: false,
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

        _FILE = null;
      }
    });
  });


  $("#stockRow #infoStock").click(function(ev) {
    let stockid = $(this).attr("data-stockid");

    $("#loadingModal").modal("show");

    $.ajax({
      type: "GET",
      url: "php/stock",
      data: {
        "op": "get",
        "sid": stockid
      },
      dataType: "json",
      success: function(result, status, xhr) {
        let data = result;
        let owner = data.owner;

        let date = new Date(data.last_ordered);
        $("#infoModal #stockType").html(data.type);
        $("#infoModal #stockName").html(data.name);
        $("#infoModal #stockDescription").html(data.amount);
        $("#infoModal #stockCreatedOn").html(date.toDateString());

        if($("#infoModal #stockOwner").length > 0) {
          $("#infoModal #stockOwner").html(`
            ${owner.username} &nbsp;&nbsp; <em>${owner.email}</em>
          `);
        }

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


  $("#stockRow #editStock").click(function(ev) {
    let stockid = $(this).attr("data-stockid");
    $("#editStockModal button#editStock").attr("data-stockid", stockid);

    $.ajax({
      type: "GET",
      url: "php/stock",
      data: {
        "op": "get",
        "sid": stockid
      },
      dataType: "json",
      success: function(result, status, xhr) {
        let data = result;

        $("#editStockModal input#stockName").val(data.name);
        $("#editStockModal textarea#stockDescription").val(data.description);
      },
      error: function(xhr, status, error) {
        console.log(xhr.status);
        console.log(error);
      }
    });
  });

  $("#editStockModal form#editStockForm").submit(function(ev) {
    ev.preventDefault();

    $("#editStockModal").modal("hide");

    let stockid = $("#editStockModal button#editStock").attr("data-stockid"),
        name = $("#editStockModal input#stockName").val(),
        description = $("#editStockModal textarea#stockDescription").val();

    $("#loadingModal").modal("show");

    let fd = new FormData();
    fd.append("op", "update");
    fd.append("sid", stockid);
    fd.append("name", name);
    fd.append("description", description);

    if(_FILE) {
      let type = getFileType(_FILE);

      fd.append("type", type);
      fd.append("file", _FILE);
    }

    $.ajax({
      type: "POST",
      url: "php/stock",
      data: fd,
      contentType: false,
      processData: false,
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

        _FILE = null;
      }
    });
  });


  $("#stockRow #deleteStock").click(function(ev) {
    let stockid = $(this).attr("data-stockid");
    $("#deleteStockModal button#deleteStock").attr("data-stockid", stockid);
  });

  $("#deleteStockModal button#deleteStock").click(function(ev) {
    let stockid = $(this).attr("data-stockid");

    $("#loadingModal").modal("show");

    $.ajax({
      type: "POST",
      url: "php/stock",
      data: {
        "op": "delete",
        "sid": stockid
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






function handleFile(file) {
  _FILE = file;

  $("small#dropzoneCont").html(file.name);
}


function getFileType(file) {
  let filetype = file.type;

  switch(filetype) {
    case "text/csv":
    case "application/csv":
      return "csv";

    case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
    case "application/vnd.ms-excel":
      return "excel";

    case "application/json":
    case "application/geo+json":
      return "geojson";

    case "application/x-esri-shape":
      return "shape";

    default:
      let isCSV = file.name.split(/\./ig).pop().toLowerCase() == "csv";

      if(isCSV) return "csv";
  }
}


function validFileType(file) {
  let type = getFileType(file);

  if(type == "csv"
  || type == "excel"
  || type == "geojson"
  || type == "shape"
  || type == "sap"
  || type == "sap_hana")
    return true;

  return false;
}

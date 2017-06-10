
/*
var date = new Date();
var day = date.getDate();
var month = date.getMonth() + 1;
var year = date.getFullYear();
if (month < 10) month = "0" + month;
if (day < 10) day = "0" + day;
var today = day + "/" + month + "/" + year;       
$("#currentDate").val(today);
*/

$(document).ready(function(){
         $(".preload").removeClass("preload");
    $("#adviceSearch").keyup(function() {
    var value = this.value;

    $("table").find("tr").each(function(index) {
        if (!index) return;
        var id = $(this).find("td").first().text();
        $(this).toggle(id.indexOf(value) !== -1);
    });
});
var date = new Date();
var day = date.getDate();
var month = date.getMonth() + 1;
var year = date.getFullYear();
if (month < 10) month = "0" + month;
if (day < 10) day = "0" + day;
var today = day + "/" + month + "/" + year;       
$("#currentDate").val(today);
    });


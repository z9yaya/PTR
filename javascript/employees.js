//function widthCompare()
//{
//    TableWidth = document.getElementsByClassName("tableHead")[0].offsetWidth;
//    windowWidth = document.documentElement.clientWidth;
//    if(TableWidth > windowWidth)
//    {
//        $(".employees.boxContainer").addClass("OverFlowX");
//    }
//}
function HideInactive()
{
    $("table tr").each(function (index) {
        if (!index) return;
        $(this).find("td").each(function () {
            console.log(this);
            var id = ''
            var not_found = (id.indexOf('') == -1);
            $(this).closest('tr').toggle(!not_found);
            return not_found;
        });
    });
}
$(document).ready(function(){
      width = (screen.width/2) - 200;
      height = (screen.height/3) - 250;
    //HideInactive();
  $(".createNew").on("click",function(){
      event.preventDefault();
      createNewPopUp =  window.open("createnewEmp.php", "newwindow", "width=400,height=750,left="+width+",top="+height);         
      });
    $("#SearchEmp").keyup(function () {
    var value = this.value.toLowerCase().trim();
    $("table tr").each(function (index) {
        if (!index) return;
        $(this).find("td").each(function () {
            var id = $(this).text().toLowerCase().trim();
            var not_found = (id.indexOf(value) == -1);
            $(this).closest('tr').toggle(!not_found);
            return not_found;
        });
    });
});
    $(".TableLink").on("click",function(){
        event.preventDefault();
        if (typeof editPopUp != 'undefined')
            {
                if(!editPopUp.closed)
                {
                    editPopUp.close();
                }
            }
        if (typeof editPopUp == 'undefined' || (editPopUp.closed))
            {
        editPopUp =  window.open("editEmp.php","newwindow", "width=400,height=825,left="+width+",top="+height);
        editPopUp.TransfempID = $(this).html().replace(/\s+/g, '');
            }
        
        
    });
//    $(".btn-group input").on("click",function(){
//        window.setTimeout(widthCompare,50);
//    })
});

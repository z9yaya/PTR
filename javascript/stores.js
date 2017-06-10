//function widthCompare()
//{
//    TableWidth = document.getElementsByClassName("tableHead")[0].offsetWidth;
//    windowWidth = document.documentElement.clientWidth;
//    if(TableWidth > windowWidth)
//    {
//        $(".employees.boxContainer").addClass("OverFlowX");
//    }
//}
function HideInactive(type = 0)
{
    if (type == 0) {
            $("table tr").each(function () {
                Td = $($(this).find("td")[7]);
                var id = Td.html();
                var not_found = (id == '');
                Td.closest('tr').toggle(not_found);
            });
        $(".showTerm").html("Show terminated");
        $(".showTerm").removeClass("show");
        
        } else {
            $("tr").toggle(true);
            $(".showTerm").html("Hide terminated");
            $(".showTerm").addClass("show");
        }

}
$(document).ready(function(){
    HideInactive();
      width = (screen.width/2) - 200;
      height = (screen.height/3) - 250;
    //HideInactive();
  $(".createNew").on("click",function(event){
      event.preventDefault();
      createNewPopUp =  window.open("editStore.php?q=1", "new_window", "width=400,height=750,left="+width+",top="+height);         
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
    $(".TableLink").on("click",function(event){
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
                form = $(this).find("form");
                form.attr('target', 'newwindow');
                editPopUp = window.open("","newwindow", "width=400,height=825,left="+width+",top="+height);
                form.submit();
            }
        
        
    });
    $(".TableLinkStores").on("click",function(event){
        console.log(event);
        event.preventDefault();
        if (typeof storePopUp != 'undefined')
            {
                if(!storePopUp.closed)
                {
                    storePopUp.close();
                }
            }
        if (typeof storePopUp == 'undefined' || (storePopUp.closed))
            {
                form = $(this).find("form");
                form.attr('target', 'da');
                storePopUp = window.open("","da","width=400,height=825,left="+width+",top="+height);
                form.submit();
            }
        
        
    });
    $(".showTerm").on("click", function(event){
        event.preventDefault();
        type = (!$(this).hasClass("show"));
        HideInactive(type);
        $(this).blur();
    })
//    $(".btn-group input").on("click",function(){
//        window.setTimeout(widthCompare,50);
//    })
});

//////////////////HTML5 input date check//////////////////////////
function LoadJqueryUI()
{
    $(".input[type=date]").attr("type","text");
    $(".label.start").removeClass("notEmpty");
              $( function() {  
				$("#start").datepicker({
                    changeYear: true,
                    minDate: '+1D',
                    dateFormat: 'dd/mm/yy',
                    beforeShow: function(input, inst) 
                    {
                        $(this).addClass("Focused");
                        $(this).attr("disabled", true);
                    },
                    onClose: function(dateText, inst) 
                    { 
                        $(this).attr("disabled", false);
                        $(this).removeClass("Focused");
                    },
                    onSelect: function(dateText, inst) {
                        $(".label[for='"+$(this).attr('id')+"']").addClass("notEmpty");
                    }
                   });
			  } );
}

function radioEvent(a) {
    if(a.checked) 
        {
            document.getElementById('EmpId').value = "";
            document.getElementById('StatsId').value = "";
            document.getElementById('RosterId').value = "";
            $("#startdate").val("");
            $("#enddate").val("");
            if ($(a).hasClass("EMP"))
                {
                    $(".EMPCont .checked").removeClass("checked");
                    $(a).parent().addClass("checked");
                    RadioID = $(a).attr("id");
                    $(".reportContainer").addClass("displayNone");
                    $(".reportContainer."+RadioID).removeClass("displayNone");
                    
                }
        }
}
///////////////////////////////////////////////////////////

$(document).ready(function(){
    ////DATE INPUTS CALENDAR SHOW//////
        $(".text").on("keyup change",function()
                {
                if (this.value == '' && this.getAttribute("type") != "date")
                    {
                $(".label[for='"+$(this).attr("id")+"']").removeClass("notEmpty");
                    }
                else
                $(".label[for='"+$(this).attr("id")+"']").addClass("notEmpty");
                
                })
        ///////////////////////////////

        /////////BUTTON CLICK//////////
        $('.hiddenRadio').on('change',function()
        {
            radioEvent(this);});
        $('.Radiocontainers').on('click',function(){
            $(this).find('.hiddenRadio').prop("checked", true);
            radioEvent($(this).find('.hiddenRadio')[0]);
        });
                             
    
    btn = document.getElementById("submit");
    $("#form").on('submit',function(event){
        var EMP_Id = document.getElementById("EmpId");
        var STATS_Id = document.getElementById("StatsId");
        var ROSTER_Id = document.getElementById("RosterId");
        $(this).prop("action","../functions/EliasPDF.php");
        if (EMP_Id.value != "" ){
           $("#hidden_empid").val(EMP_Id.value);
           $("#hidden_type").val($("#reportTypeEmp").val());
            if (!checkVal()) {
                return false;
            }
            return true;
        }
        
        if (STATS_Id.value != "" ){
            $(this).prop("action","storePDF.php");
            $("#hidden_empid").val(STATS_Id.value);
            $("#hidden_start").val($("#startdate").val());
            $("#hidden_end").val($("#enddate").val());
            $("#hidden_type").val('stats');
            if (!checkVal('stats')) {
                return false;
            }
            return true;
        };
        if (ROSTER_Id.value != "" ){
            $(this).prop("action","rosterPDF.php");
            $("#hidden_empid").val(ROSTER_Id.value);
            $("#hidden_roster").val($("#rosterType").val());
            $("#hidden_type").val('roster');
            if (!checkVal('roster')) {
                return false;
            }
            return true;
        };
    return false;
    })
    function checkVal(a = 'report') {
        if ($("#hidden_empid").val() == ''){
            return false;
            }
            if ($("#hidden_type").val() == ''){
                return false;
            }      
    if (a == 'roster') {
       if($("#hidden_roster").val() == ''){
            return false;
            }
    }
        return true;
   
    if (b == 'stats') {
       if($("#hidden_start").val() == '' && $("#hidden_end").val() == ''){
            return false;
            }
    }
        return true;
    }
});
 
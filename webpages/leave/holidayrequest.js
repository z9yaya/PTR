function LoadJqueryUI()
{
    $(".input[type=date]").attr("type","text");
              $( function() {  
				$( "#datepicker1" ).datepicker({
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
			  $( function() {
				$( "#datepicker2" ).datepicker({
                    changeYear: true,
                    minDate: '+2D',
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

LoadJqueryUI();
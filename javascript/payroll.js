$(document).ready(function() {
    $("#edit").on("click", function() {
        if ($(".selectemployees").val() != '') {
        $(".payrolltable .input").prop('disabled', false);
        $("#confirm").addClass('displayNone');
        $(".editOverlay").addClass('displayNone');
        $(".submit.button").removeClass('displayNone');
        $(".type").val('update');
        $(this).addClass('displayNone');
        }
    });
    $("#confirm").on("click", function() {
         if ($(".selectemployees").val() != '') {
        $(".payrolltable .type").prop('disabled', false);
        $(".payrolltable .payID").prop('disabled', false);
        $(".payrolltable .empID").prop('disabled', false);
        $("#edit").addClass('displayNone');
        $(".type").val('approved');
        $("#form").submit();
    };})
    $(".text").on("keyup change", function () {
        if (this.value == '' && this.getAttribute("type") != "date") {
            $(".label[for='" + $(this).attr("id") + "']").removeClass("notEmpty");
        } else
            $(".label[for='" + $(this).attr("id") + "']").addClass("notEmpty");
    });
    $(".text[value!='']").siblings(".label").addClass('notEmpty');
})

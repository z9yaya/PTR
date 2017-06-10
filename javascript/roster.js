function makeEdit(a) {
    Form = $(this).prev();
    $(".edit_bttn").not(this).addClass('displayNone');
    Form.addClass("edit");
    Form.find(".input").prop('disabled', false);
    inputVal = [];
    $.each(Form.find(".input"), function(k, v) {
        inputVal[$(v).prop('name')] = $(v).val();
    })
    
    $(this).siblings(".cancel").off();
    $(this).siblings(".cancel").on("click", function(){
        resetForm(inputVal, Form);});
    $(this).siblings(".cancel").removeClass('displayNone');
    $(this).addClass('displayNone');
    $(this).next().removeClass('displayNone');
    return false;
}
function saveShift(a) {
    Form = $(this).siblings(".edit");
    id = Form.find(".doneShiftID").val();
    if ($.isNumeric(Form.find(".start.H").val()) && $.isNumeric(Form.find(".start.M").val())){
        startM = parseInt(Form.find(".start.M").val());
        startH = parseInt(Form.find(".start.H").val());
        if (startH > 3 && startH < 24 && startM > -1 && startM < 60) {
            start = Form.find(".start.H").val()+":"+Form.find(".start.M").val();
        } else {Form.addClass('invalid');
            return false;}
    } else {
        Form.addClass('invalid');
        return false;
    }
    if ($.isNumeric(Form.find(".end.H").val()) && $.isNumeric(Form.find(".end.M").val())) {
        endM = parseInt(Form.find(".end.M").val());
        endH = parseInt(Form.find(".end.H").val());
        if (endH > 3 && endH < 24 && endM > -1 && endM < 60) {
            end =  endH+":"+endM;
        } else {
            Form.addClass('invalid');
            return false;}
    } else {
        Form.addClass('invalid');
        return false;
    }
    state = Form.find(".doneStoreState").val();
    submitShift(id, start, end, function(){
        parent.AddMessagePopUp("Shift saved", '');
        setTimeout(function() {
            Form.removeClass("edit");
            Form.find(".input").prop('disabled', true);
            Form.removeClass('edit');
            $(".cancel").addClass('displayNone');
            $(".edit_bttn").removeClass('displayNone');
            $(".saveShifts").addClass('displayNone');
        }, 1000);
        
    })  
    return false;
}

function submitShift(id, start, end, callback) {
    stm = 'SHIFTID='+id+'&SHIFTBEGIN='+start+'&SHIFTEND='+end+'&STATE='+state;
    $.post("../functions/updateDoneShift.php", stm, function(a) {
        if (a == 'success') {
            callback();
            return true;
        } else {
            parent.AddMessagePopUp(a, 'Error', 'showNotice');
            return false;
        }
    })
}

function removeInvalid(a) {
    $(this).parent().removeClass('invalid');
}

function resetForm(a, b) {
    for (key in inputVal) 
    {
        b.find(".input[name='"+key+"']").val(a[key]);
    }
    b.removeClass("edit");
    b.find(".input").prop('disabled', true);
    b.removeClass('edit');
    $(".edit_bttn").removeClass('displayNone');
    $(".saveShifts").addClass('displayNone');
    $(".cancel").addClass('displayNone');
}

$(document).ready(function(a) {
    $(".input").on('focus', removeInvalid);
    $(".edit_bttn").on("click",makeEdit);
    $(".saveShifts").on("click",saveShift);
    
    
})
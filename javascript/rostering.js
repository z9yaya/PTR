function fetchShifts(a = false) {
    $('.rosterOverlay').removeClass('displayNone');
    dest = "../functions/getShifts.php";
    if (a) {
        dest = "../functions/getShiftsC.php";
    }
    $.post(dest, Stores, function(data) {
        d = jQuery.parseJSON(data);
        $(".addOne:not(.displayNone)").remove();
        $.each(d, function(key,value){ //key = STOREID, value[0] = EMPID, value[1] = shifts
            table = $(".saveShifts[value = '"+key+"'").parent().find(".mainTable");
            cont = table;
//            if (!table.parent().hasClass("delete")) {
//                table.find(".addOne:not(.displayNone) .notStoreSelect").val('');
//                $.each(table.find(".addOne:not(.displayNone) .notStoreSelect"), function(k,v){
//                     removeOtherEmp($(v));
//                });
//                table.find(".addOne:not(.displayNone)").remove();
//            }
            
//                $.each(table.find(".addOne:not(.displayNone) .notStoreSelect"), function(k,v){
//                    $(v).val('');
//                     removeOtherEmp($(v));
//                });
                table.find(".addOne .notStoreSelect option").removeClass("displayNone");
                table.find(".addOne:not(.displayNone)").remove();
            $.each(value[0], function(kU,vU){ //vU = EMPID
                if (!table.find(".empLinkForm input[value='"+vU+"']").length > 0) {
                    if (!table.find('.addOne:not(.displayNone) .notStoreSelect option:selected[value="'+vU+'"]').length > 0) {
                        addEmpRow(vU, table);//used to add missing emps
                        table.find(".addOne:not(.displayNone) .notStoreSelect option:selected[value='"+vU+"']").removeClass("displayNone");
                    }
                    //addEmpRow(vU, table);
                    removeOtherEmp(table.find('.addOne:not(.displayNone) .notStoreSelect option:selected[value="'+vU+'"]').parent());
                }
                $.each(value[1], function(kS,vS){ //vS = day of week
                    if (vS[vU] !== undefined) {
                    $.each(vS[vU][0], function(kD,vD){ // vS[vU][0] = singular shift for one day of week and one emp, vD = begin or end time value of a shift
                        s = table.find(".input.notConfirmed[name='"+kD+"']");
                        s.val(vD);
                        s.attr('sid', vS[vU][1]['SHIFTID']); //vS[vU][1]['SHIFTID'] = ID of shift for that day and user.
                        if (kD.indexOf('start') != -1) {
                            updateEnd($(s), false, false);
                        }
                        s.parent().removeClass("displayNone");
                        s.parent().parent().find(".addShift").addClass("displayNone");
                    });
                }
                });
            }); 
            table.find('.addOne:not(.displayNone) .notStoreSelect option[action="remove"]').remove();
    })
         $('.rosterOverlay').addClass("displayNone");
            parent.PageLoader(false);
})
}

function updateEnd(a, d = true, c = true) {
    start = a;
    if (d) {
        start = $(a.target);
    }
    startVal = start.val();
    b = false;
    res = ""; 
    if (startVal != null) {
    sliceStart = startVal.slice(0,2);
    msliceStart = startVal.slice(3,5);
    end = start.parent().parent().find(".endSelect");
    endVal = end.val();
    slicedEnd = 0;
    mslicedEnd = 0;
    options = start.find("option");
    selected = start.find(":selected").index();
    countOptions = options.length;
    selectedplusOne = selected+1;
    if (endVal) {
        slicedEnd = endVal.slice(0,2);
        mslicedEnd = endVal.slice(3,5);
    }
    if (slicedEnd > sliceStart || mslicedEnd > msliceStart && slicedEnd == sliceStart) {
        selectedOption = endVal;
        b = true;
    }
    for (i = selected+1; i < countOptions; i++) {
        res += "<option>"+$(options[i]).html()+"</option>";
    }
    }
    res += "<option>21:00</option><option>21:30</option><option>22:00</option><option>22:30</option><option>23:00</option>";
    end.html(res);
    if (b === false) {
        selectedOption = end.find("option")[0].value;
        b = false;
    }
    end.val(selectedOption);
    if (c) {
    makeValid(end, true);
    }
}
//function updateEnd(a, d = true, c = true) {
//    start = a;
//    if (d) {
//        start = $(a.target);
//    }
//    startVal = start.val();
//    console.log(startVal);
//    if (startVal != null) {
//    sliceStart = startVal.slice(0,2);
//    msliceStart = startVal.slice(3,5);
//    end = start.parent().parent().find(".endSelect");
//    endVal = end.val();
//    slicedEnd = 0;
//    mslicedEnd = 0;
//    options = start.find("option");
//    selected = start.find(":selected").index();
//    countOptions = options.length;
//    selectedplusOne = selected+1;
//    b = false;
//    res = "";   
//    if (endVal) {
//        slicedEnd = endVal.slice(0,2);
//        mslicedEnd = endVal.slice(3,5);
//    }
//    if (slicedEnd > sliceStart || mslicedEnd > msliceStart && slicedEnd == sliceStart) {
//        selectedOption = endVal;
//        b = true;
//    }
//    for (i = selected+1; i < countOptions; i++) {
//        res += "<option>"+$(options[i]).html()+"</option>";
//    }
//    }
//    res += "<option>21:00</option><option>21:30</option><option>22:00</option><option>22:30</option><option>23:00</option>";
//    end.html(res);
//    if (b === false) {
//        selectedOption = end.find("option")[0].value;
//        b = false;
//    }
//    end.val(selectedOption);
//    if (c) {
//    makeValid(end, true);
//    }
//}

function UpdateEndBegin(start = $(".startSelect")) {
    $.each(start, function(key, value){
        end = $(value).parent().parent().find(".endSelect");
        selected = $(value).find(":selected").index();
        options = $(value).find("option");
        res = "";
        endVal = end.attr("value");
        end.removeAttr("value")
        for (i = selected+1; i < options.length; i++) {
            res += "<option>"+$(options[i]).html()+"</option>";
        }
        res += "<option>21:00</option><option>21:30</option><option>22:00</option><option>22:30</option><option>23:00</option>";
        end.html(res);
        end.val('');
        if (endVal !== null) {
             end.val(endVal);
        }
    }) 
}

function iniPageEvent() {
    $(".startSelect").off();
    $(".endSelect").off();
    $(".TableLink").off();
    $(".TableLinkStores").off();
    $(".addEmp").off();
    $(".addShift").off();
    $(".notStoreSelect").off();
    $(".saveShifts").off();
    $(".rosterTitle").off()
    
    $(".startSelect").on("change", updateEnd);
    $(".endSelect").on("change", makeValid);
    $(".TableLink").on("click",TableLinkUserEvent);
    $(".TableLinkStores").on("click",TableLinkEvent);
    $(".addEmp").on("click",addEmpEvent);
    $(".addShift").on("click",addShifts);
    $(".notStoreSelect").on("change",UserChangeEvent);
    $(".saveShifts").on("click", saveShifts);
    $(".rosterTitle").on("click", switchDateEvent);
}
function addEmpRow(empid, table){
        row = table.find(".addOne.displayNone");
        overlay = row.find(".nameOverlay");
        if (row.length > 0) {
            $(".notStoreSelect").off();
            $(".startSelect").off();
            $(".endSelect").off();
            $(".addShift").off();
            options = row.find(".dropdownBg").html();
            Empoptions = row.find(".notStoreSelect").html();
            UpdateEndBegin($(row.find(".startSelect")));
            row.removeClass("displayNone");
            table.append(newRow+newRowhtml+newRowRest);
            addedDrop = table.find(".addOne.displayNone .notStoreSelect");
            addedDrop.html(Empoptions);
            $(".notStoreSelect").on("change",UserChangeEvent);
            $(".startSelect").on("change", updateEnd);
            $(".endSelect").on("change", makeValid);
            $(".addShift").on("click", addShifts);
            row.find(".notStoreSelect").val(empid);
            row.find(".notStoreSelect").prop("oval",empid);
            if (row.find(".notStoreSelect option:selected").length > 0) {
            row.find(".notStoreSelect option:selected")[0].defaultSelected = true;
            }
            Username = $(row.find(".notStoreSelect").find(":selected")).attr('empname');
            overlay.html(Username);
            overlay.removeClass("displayNone");
            row.find(".Default").remove();
            selects = row.find("select");
            for (i = 1; i<selects.length;i++) {
                name = $(selects[i]).attr("id");
                $(selects[i]).attr("name", name+empid);
            }
        }
}

function removeOtherEmp(Obj){
    //otherObj = cont.find(".notStoreSelect[value='"+Obj.val()+"']");
    otherObj = cont.find(".notStoreSelect").not(Obj);
    //console.log(Obj);
    ///console.log(otherObj);
        $.each(otherObj,function(k,v) {
        $(v).find('option[value="'+Obj.val()+'"]').addClass("displayNone");
                if (Obj.val() != Obj.prop("oval")) {
                    $(v).find('option[value="'+Obj.prop("oval")+'"]').removeClass("displayNone");
                }
        
    })
}

function UserChangeEvent(event) {
    obj = $(this);
    if ($($(this).find(":selected")).attr("action") == 'remove') {
        table = obj.closest(".mainTable");
        obj.val('');
        removeOtherEmp(obj);
        $(this).closest(".tableRow").remove();
        //checkShifts(table);
        //showDelete(obj[0], false);
        return false;
    }
    otherObj = cont.find(".notStoreSelect").not($(this));
    
    td = $(this).closest("td");
    row = $(this).closest(".tableRow").find("select");
    overlay = $(this).closest("td").find(".nameOverlay");
    empid = $(this).val();
    if (empid != '') {
        removeOtherEmp(obj);
        obj.find(".Default").remove();
        Username = $($(this).find(":selected")).attr('empname');
        formatted = $($(this).find(":selected")).attr('formattedID');
        obj.find("option:selected")[0].defaultSelected = true;
        nonS =  obj.find("option:not(:selected)");
        $.each(nonS, function(kN, vN) {
            vN.defaultSelected = false;
        })
        for (i = 1; i<row.length;i++) {
            name = $(row[i]).attr("id");
            $(row[i]).attr("name", name+empid);
            makeValid($(row[i]), true);
        }
    obj.prop("oval", empid);
    overlay.html(Username);
    overlay.removeClass("displayNone");
    }
        return false;
}

function addEmpEvent(event) {
        row = $(this).prev().find(".addOne.displayNone");
        if (row.length > 0) {
            $(".notStoreSelect").off();
            $(".startSelect").off();
            $(".endSelect").off();
            $(".addShift").off();
            options = row.find(".dropdownBg").html();
            Empoptions = row.find(".notStoreSelect").html();
            UpdateEndBegin($(row.find(".startSelect")));
            row.removeClass("displayNone");
            $(this).prev().append(newRow+newRowhtml+newRowRest);
            $(this).prev().find(".addOne.displayNone .notStoreSelect").html(Empoptions);
            $(".notStoreSelect").on("change",UserChangeEvent);
            $(".startSelect").on("change", updateEnd);
            $(".endSelect").on("change", makeValid);
            $(".addShift").on("click", addShifts);
        }
        return false;
}

function TableLinkUserEvent(event) {
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
        return false;
}

function TableLinkEvent(event) {
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
            return false;
}

function addShifts(event) {
    $(this).addClass("displayNone");
    $(this).parent().find(".containers").removeClass("displayNone");
}

function saveShifts(event) {
    $(this).blur();
    bttn = $(this);
    ovl = $(this).parent().find(".rosterOverlay");
    ovl.removeClass("displayNone");
    table = '';
    store = $(this).attr('value');
    week = $('.rosterTitle.Active').data('week');
    inputs = $(this).parent().find(".mainTable").find('select:not(.notConfirmed,.notStoreSelect)');
    if (!inputs.length > 0) {
        deleteThese = {shift: {}, emp: {}};
        Del = $(this).siblings(".mainTable").find('.removeShift.confirmDelete').parent();
        if (Del.length > 0) {
            delInputsS = Del.find("select.dropTime.end");
            $.each(delInputsS, function(k,v) {
                deleteThese["shift"][k] = $(v).attr("sid");
            });
           delInputsE = Del.find("select.notStoreSelect").parents('.tableRow').find(".posRel .dropTime.end").not(".displayNone");
            $.each(delInputsE, function(k,v) {
                deleteThese["emp"][k] = $(v).attr("sid");
            });
            table = "DELETE="+JSON.stringify(deleteThese); 
        }
    } else {
        $.each(inputs, function(k, s){
        if ($(s).attr('sid')) {
            n = $(s).attr('name');
            if (n.split(".").length == 3) {
            $(s).attr('name', n+'.'+$(s).attr('sid'))
                }
            }
        })
        table = $(this).parent().find(".mainTable").find('select:not(.notConfirmed)').serialize();        
    }
    
    if (table != '') {
         $.post('../functions/saveStoreShifts.php', table+"&STOREID="+store+"&WEEK="+week, function(res){
             if (res == 'success' || res == 'successsuccess') {
                 if (inputs.length > 0) {
                 inputs.addClass("notConfirmed");
                     $.each(inputs, function(k, s){
                         n = $(s).attr('name');
                         l = n.split(".");
                        if (l.length == 4) {
                        $(s).attr('name', l[0]+"."+l[1]+"."+l[2]);
                        }
                    });
                     //cont.html(oriTable);
                 } else {
                     //
                     $.each(deleteThese['emp'], function(k, v){
                         d = Del.find("select.notStoreSelect").parents('.tableRow').find(".posRel .dropTime[sid = '"+v+"']").not(".displayNone")
                         d[0].removeAttribute('sid');
                         d[1].removeAttribute('sid');
                         $(d[0]).parent().addClass("displayNone");
                         $(d[1]).parent().addClass("displayNone");
                         if (k == Object.keys(deleteThese['emp']).length -1) {
                             $.each(Del.find("select.notStoreSelect"), function(rK, rV) {
                                 $(rV).val('');
                                 removeOtherEmp($(rV));
                                 $(rV).parents(".addOne.confirmDelete").remove();
                             })
                         }
                     })
                     $.each(deleteThese['shift'], function(k, v){
                         d = Del.find(".dropTime[sid='"+v+"']");
                         d[0].removeAttribute('sid');
                         d[1].removeAttribute('sid');
                         $(d[1]).val('');
                         $(d[0]).val('');
                         $(d[0]).parent().addClass("displayNone");
                         $(d[1]).parent().addClass("displayNone");
                     })
                     switchDelete(cont, false);
                 }
                 //fetchShifts();
                 resetTable(a = false)
                 bttn.addClass('displayNone');
                 ovl.addClass("displayNone");
                 cont.addClass("view");
                 cont.find(".smallMenu").removeClass("expand");
                 cont.find(".smallMenu").html(oriMenu);
                 iniMenu();
                 resetDateSel();
                 iniPageEvent();
                 window.onbeforeunload = false;
             } else {
                 console.log(res);
             }
        })
    } else {
        ovl.addClass("displayNone");
        bttn.addClass("displayNone");
        
    }
    return false;
}

function checkShifts(obj) {
    saveButton = obj.closest(".storesCont").find(".saveShifts");
    table = obj.closest(".mainTable").find('select:not(.notConfirmed, .notStoreSelect)').length;
    if (table != 0) {
        saveButton.removeClass("displayNone");
    } else {
        saveButton.addClass("displayNone");
    }
}

function makeValid(event, a = false) {
    //console.log(event);
    if (a && event.hasClass("endSelect")) {
        endS = event;
    } else if (!a) {
        endS = $(this);
    } else {
        return false;
    }
    if (endS.hasClass(".notStoreSelect")) {
        
    }
    startS = endS.parent().parent().find(".startSelect");
    if (endS.hasClass("startSelect")) {
        startS = endS;
        endS = startS.parents(".tableCell").find(".endSelect");
    }
    if (endS.val() !== '' && endS.val() !== null  ) {
        if (startS.val() !== '' && startS.val() !== null) {
        isvalid = startS.attr("name").split(".");
            if (isvalid[2] !== '') {
                //saveButton = endS.closest(".storesCont").find(".saveShifts");
                endS.removeClass("notConfirmed");
                startS.removeClass("notConfirmed");
                //saveButton.removeClass("displayNone");
            }
        } else {
            endS.addClass("notConfirmed");
            startS.addClass("notConfirmed");
        }
    }
    checkShifts(endS);
}

function switchDelete(b, a=true) {
    if (a) {
        b.removeClass("view");
        b.addClass("delete");
        bttnD = b.find(".addShift.displayNone");
        Empbttn = b.find(".addOne:not(.displayNone) .cellSelect .removeEmpBt");
        spanCont = bttnD.parents(".tableCell");
        spanEmpCont = Empbttn.parents(".tableCell");
        bttnD.prop("title", 'Delete shift');
        bttnD.addClass("removeShift");
        Empbttn.addClass("removeShift");
        spanCont.addClass("posRel");
        spanEmpCont.addClass("posRel");
        bttnD.removeClass("displayNone");
        Empbttn.removeClass("displayNone");
        bttnD.off();
        Empbttn.off();
        bttnD.on("click",showDelete);
        Empbttn.on("click",showDelete);
    } else {
        b.removeClass("delete");
        b.addClass("view");
        bttnD = b.find(".addShift.removeShift");
        bttnD.prop("title", 'Add shift');
        spanCont = bttnD.parents(".tableCell");
        //bttnD.addClass("displayNone");
        spanCont.removeClass("posRel");
        bttnD.removeClass("confirmDelete");
        bttnD.removeClass("removeShift");
    }
}

function showDelete(a,b = true) {
    if (b) {
        a = this;
    }
    if ($(a).hasClass("removeEmpBt")) {
        if ($(a).hasClass("confirmDelete")) {
            $(a).removeClass("confirmDelete");
            $(a).parents('.addOne').removeClass("confirmDelete");
        } else {
            $(a).addClass("confirmDelete");
            $(a).parents('.addOne').addClass("confirmDelete");
            $(a).parents('.addOne').find(".addShift.confirmDelete").removeClass('confirmDelete');
        }
    } else {
        if ($(a).hasClass("confirmDelete")) {
            $(a).removeClass("confirmDelete");
        } else {
            $(a).addClass("confirmDelete");
        }
    }
    makeValidDelete($(a));
}

function makeValidDelete(a, b = 'bttn') {
    if (b === 'bttn') {
        par = a.parents(".storesCont.delete");
        saveButton = par.find(".saveShifts");
        confirmed = par.find(".confirmDelete");
        if (confirmed.length > 0) {
            saveButton.removeClass("displayNone");
        } else {
            saveButton.addClass("displayNone");
        }
    }
}

function sendRoster(storeid, storename) {
    
    parent.showPopUp('Do you want to send the roster to the '+storename+' store staff?', 'Sending roster, please wait...', function() {
    $.post('../functions/sendRoster.php', "STORE="+storeid+"&WEEK="+$('.rosterTitle.Active').data('week'), function(res){
        if (res == 'success') {
            parent.hidePopUp();
        } else if(res == 'noEmp'){
            parent.AddMessagePopUp('No staff member has a shift', 'Error', "showNotice");
        } else {
            parent.AddMessagePopUp("<span class='textFit'>"+res+"</span>", 'System error, contact administrator', "showNotice");
        }
    })});
    resetDateSel();
    iniMenu();
}

function switchDateEvent(a) {
    if ($(this).hasClass('Active')) {
        return false;
    }
    $('.rosterTitle.Active').removeClass('Active');
    $(this).addClass('Active');
    resetTable();
    return false;
}
 

function resetDateSel(a) {
    $(".rosterTitle").not('.Active').removeClass("displayNone");
}

function resetTable(a = false) {
    thisTable = $('.roster');
    if (a !== false) {
        thisTable = a;
    }
    thisTable.find(".dropTime").val('');
    thisTable.find(".dropTime").parent().addClass("displayNone");
    fetchShifts($('.rosterTitle.Active').data('week'));
    thisTable.find(".addShift").removeClass("displayNone");
}
//////////////////////////////// Menu Related/////////////////////////////////////////////////
function iniMenu() {
    $(".Menu3dots").off();
    $("body").off();
    $(".smallOptionsLinks").off();
    $(".Menu3dots").on("click", setMenu3dotsEvent);
    $("body").on("focus click", bodyClick);
    $(".smallOptionsLinks").on("click", configureMenu);
    $(".Menu3dots").removeClass('displayNone');
    
}
function setMenu3dotsEvent(event)
{
    $(this).parent().addClass("expand");
    return false;
}

function configureMenu(event) {
    menu3dots = $(this).parent(".smallOptionsCont").prev();
    cont = $(this).parents(".storesCont");
    $(".rosterTitle").not('.Active').addClass("displayNone");
    if ($(this).attr('clickValue') == 'edit') {
        oriTable = cont.html();
        hide = $('.Menu3dots').not(menu3dots);
        hide.addClass('displayNone');
        cont.removeClass("view");
        ChangeMenu($(this));
        window.onbeforeunload = function () {
            return true;
        };
    } else if ($(this).attr('clickValue') == 'send'){
        hide = $('.Menu3dots').not(menu3dots);
        hide.addClass('displayNone');
        sendRoster(cont.find(".saveShifts").attr('value'), cont.find(".rosterStore .storeNameSpan").html());
    } else if ($(this).attr('clickValue') == 'delete') {
        oriTable = cont.html();
        hide = $('.Menu3dots').not(menu3dots);
        //hide.css("display",'none');
        hide.addClass('displayNone');
        //cont.removeClass("view");
        //cont.addClass("delete");
        ChangeMenu($(this));
        switchDelete(cont);
        window.onbeforeunload = function () {
            return true;
        };
    }
    $(".smallMenu").removeClass("expand");
    return false;
}

function bodyClick (event) {
        if (!$(event.target).hasClass("smallOptions") && !$(event.target).hasClass("Menu3dots") && !$(event.target).hasClass("smallOptionsCont")) {
            $(".smallMenu").removeClass("expand");
        }

    }

function ChangeMenu(a, Type = 'cancel')
{
    if (Type == 'normal')
        {
            menu = a;
            opCont = a.parent().find(".smallOptionsCont");
            menu.off();
            menu.addClass("Menu3dots");
            menu.removeClass("Cancel");
            menu.attr("title", '');
            opCont.parent().removeClass("expand");
            menu.off();
            menu.on("click", setMenu3dotsEvent);
        }
    else if(Type == 'cancel')
        {
            menu = a.parents(".smallMenu").find(".Menu3dots");
            opCont = a.parents(".smallOptionsCont");
            menu.off();
            opCont.parent().removeClass("expand");
            menu.removeClass("hideMenu3dots");
            menu.addClass("Cancel");
            menu.attr("title", 'Cancel');
            menu.removeClass("Menu3dots");
            $(".Cancel").on("click", CancelChanges);
        }
}

function CancelChanges(event) {
    cont.addClass("view");
    cont.html(oriTable);
    switchDelete(cont, false);
    //fetchShifts();
    resetTable(cont);
    smallmenu = cont.find(".smallMenu");
    smallmenu.removeClass("expand");
    smallmenu.html(oriMenu);
    iniMenu();
    iniPageEvent();
    resetDateSel();
    window.onbeforeunload = false;
    return false;
}
//Task to execute when the document is ready//
$(document).ready(function () {
    UpdateEndBegin();
    fetchShifts();
    newRow = "<tr class='tableRow addOne displayNone'>";
    newRowRest = "</tr>";
    row = $($(".mainTable")[0]).find(".addOne.displayNone");
    newRowhtml = row.html();
    oriMenu = $($(".smallMenu")[0]).html();
    width = (screen.width/2) - 200;
    height = (screen.height/3) - 250;
    iniPageEvent();
    $(".preload").removeClass("preload");
    //Configuring event handlers for the "Menu"//
    iniMenu();
});

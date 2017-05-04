//script.js by Yannick Mansuy

//used to check if a file exists at the specified location
//INPUT url: path of file eg: "root/folder/file.extension"
//OUTPUT: true when the file is found and accessible or false when the file cannot be accessed
function UrlExists(url)
{
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status!=404;
}

//used to create a event listening for server sent events, when data is received, the function LoadDoc is executed with default parameters except the serverSent parameter which is set to true
//INPUT file: the name of the file being watched by the server.
function AutoCheck(file)
{
    if (!!window.EventSource)
        {
        if (typeof(sourceNew) != "undefined")
            {
                try {
                    if (!sourceNew['URL'].includes(file))
                        {
                            sourceNew.close();
                        }
                    }
                catch(err)
                    {
                        if (!sourceNew['url'].includes(file))
                        {
                            sourceNew.close();
                        }
                    }
            }
            sourceNew = new EventSource("functions/chat/fileCheck.php?file="+file);
            sourceNew.onmessage = function(event) {
                 loadDoc(undefined, undefined, true);}
        }
    else 
        {
             chat.innerHTML= "Sorry, your browser does not support instant messaging...";
        }
}

//used to display conversation when they exist or executes the pushChanges function while specifying that the file does not exist
//INPUT creator: default is the global email variable, email of logged in user.
//INPUT receiver: default is the global user variable, email of target user
//INPUT serverSent: default is set to false, used to specify if the function was executed from the AutoCheck function, false when executed from somewhere else
//OUTPUT file: the name of the file being viewed by the user.
function loadDoc(creator = email, receiver = user, serverSent = false) 
    {
        if (creator!='' && receiver !='')
            {
            saveLocation = "functions/chat/";
            var file = creator+"-"+receiver+".xml";
            if (!UrlExists(saveLocation + file))
                {
                    var possibleFile = receiver+"-"+creator+".xml";
                    if (!UrlExists(saveLocation + possibleFile))
                        {
                            pushChanges(false, file, receiver);
                            
                        }
                    else
                        {
                            file = possibleFile;
                        }
                }
            var enter = document.getElementById("submitText");  
            enter.setAttribute("onClick","pushChanges(true, '" + file + "')");
            var chat =  document.getElementById("id");
            var xhttp;
            if (window.XMLHttpRequest)
                {
                    xhttp = new XMLHttpRequest();
                }
            xhttp.onreadystatechange = function(){
                 if (this.readyState == 4 && this.status == 200 && this.status != 404)
                        {
                            if (chat.innerHTML != xhttp.responseText)
                                {
                                    chat.innerHTML = xhttp.responseText;
                                    if (chat.scrollTop != chat.scrollHeight)
                                        {
                                    chat.scrollTop = chat.scrollHeight;
                                        }
                                }

                        }
            };
            xhttp.open("POST", saveLocation+file, true);
            xhttp.send();
            if (serverSent == false)
                {
                    AutoCheck(file);
                }
            return file;
            
            }
    }

//used to create a new conversation or write to a conversation
//INPUT exist: default is true, used to specify if the file needs to be created or not
//INPUT Document: variable containing the name of the file being modified
//INPUT user: default is null, only specified when a file does not exist, used to push current user details to database
function pushChanges(exist = true, Document, user = '')
{
    if (email)
        {
            if (exist)
                {
                    var chat =  document.getElementById("id");
                    var input = document.getElementById("chat_input").value;
                    if (input != '')
                        {
                        var d = new Date();
                        var currentTime = d.getTime();
                        var timestamp = new Date(currentTime);
                        var time = timestamp.toLocaleTimeString() + " " + timestamp.getDate()+"/"+(timestamp.getMonth() + 1)+"/"+timestamp.getFullYear();
                        var formated_input = '<message><div class="container"><div class="' + email + '" title="'+time+'">' + input + '</div></div></message>';
                        var data = 'data='+ formated_input + "&document=" + Document;
                        document.getElementById("chat_input").value='';
                        chat.innerHTML += formated_input;
                        chat.scrollTop = chat.scrollHeight;
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.open('POST','functions/chat/save.php', true);
                        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xmlhttp.send(data);
                        } 
                }
            else if (!exist)
                {
                    
                    var data = 'data='+ '' + "&document=" + Document  + "&user=" + user;
                     var xmlhttp = new XMLHttpRequest();
                        xmlhttp.open('POST','functions/chat/save.php', true);
                        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xmlhttp.send(data);
                }

        }
}



var email = '';
var contacts = '';
var user = '';

//used to set the global variable email, the value of the logged in user
function grabSession()
{
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
            {
            if (this.readyState == 4 && this.status == 200)
                {
                    email = xmlhttp.responseText;
                    loadContacts();
                }
            };
        xmlhttp.open('POST','functions/chat/session.php', true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send();
}

//used to read the contacts from database and add elements to the contacts bar, also sets the function CheckMissed to be executed every 10 seconds.
function loadContacts()
{
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
            {
            if (this.readyState == 4 && this.status == 200)
                {
                    contacts =  JSON.parse(xmlhttp.responseText);
                    for (var i=0; i <Object.keys(contacts["EMAIL"]).length; i++)
                        {
                          document.getElementById("contacts_bar").innerHTML += '<div class="contact" onclick=\'openChat("'+i+'",this)\' data-myValue="'+contacts["EMAIL"][i]+'">'+contacts["F_NAME"][i]+" "+contacts["L_NAME"][i]+'</div>';
                        }                    
                }
            };
        xmlhttp.open('POST','functions/chat/load.php', true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send();
        MakeCss(email, 'originator');
        CheckMissed();
        setInterval(CheckMissed, 10000);     
}

//used to display the user messageBox and execute the loadDoc functions to display the conversation
//INPUT User: index of the email in global variable contacts
//INPUT Contact: HTML object, the object that executed this function
function openChat(User, Contact)
{
    if (id.style.display=="none")
        {
            hideChat();
        }
    if (Contact.classList != "contact active")
        {
            var Contacts = document.getElementsByClassName("active");
            if (Contacts.length == 1)
                {
                Contacts[0].className = "contact";
                }

            user = contacts['EMAIL'][User];
            loadDoc();
            MakeCss(user, 'receiver');
            ChangeMainTitle(contacts['F_NAME'][User]+" "+contacts["L_NAME"][User]);
            Contact.className = "contact active";
            contactsButton.classList.remove("unreadHid");
            Contact.classList.remove('unread');
            if (id.scrollTop != id.scrollHeight)
            {
                id.scrollTop = id.scrollHeight;
            }
        }
    HideContactsBar();
}

//used to hide the messageBox
function closeChat()
{
    messageBox.style.display = "none";
    var Contacts = document.getElementsByClassName("active");
    sourceNew.close();
    if (Contacts.length == 1)
        {
        Contacts[0].className = "contact";
        }
}

//used to hide the message container but keep it active and easily accessible
function hideChat()
{
    if (id.style.display != "none")
        {
            id.style.display = "none";
            chat_input.style.display = "none";
        }
    else
        {
            id.style.display = "block";
            chat_input.style.display = "inline-block";
        }
}

//used to query the database to check for any missed messages
function CheckMissed()
{
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
            {
            if (this.readyState == 4 && this.status == 200)
                {
                    var UserContacts= document.getElementsByClassName("contact");
                    var missed =  JSON.parse(xmlhttp.responseText);
                    if (!Object.keys(missed["CHATFILE"]).length)
                        {
                            var OutdatedNotifications = document.getElementsByClassName("unread");
                            for (var i=0; i< OutdatedNotifications.length; i++)
                                {
                                    OutdatedNotifications[i].classList.remove("unread");
                                }
                        }
                    for (var i=0; i< Object.keys(missed["CHATFILE"]).length; i++)
                        {
                            if(hideContacts.checked && !contactsButton.classList.contains("unreadHid"))
                                {
                                    contactsButton.classList.add("unreadHid");
                                }
                                if ($("#MessageContent").hasClass("HiddenContent"))
                                    {
                                        messagesLink.classList.add("unreadHid");
                                    }
                            for (var j = 0; j < UserContacts.length;j++)
                                {
                                    if (missed["CHATFILE"][i].includes(UserContacts[j].getAttribute('data-myValue')))
                                        {
                                            UserContacts[j].classList.add('unread');
                                        }
                                    else{
                                            UserContacts[j].classList.remove("unread");
                                    }
                                }
                        }                    
                }
            };
        xmlhttp.open('POST','functions/chat/missedCheck.php', true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send();
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////

//used to block body scrolling when over scrolling in conversation container
//INPUT block, true to lock the body scrolling to its current position, false to restore body scrolling
function stopScrolls(block)
{
    if (block == true)
        {
            if (typeof Scroll === 'undefined')
                {
                    Scroll = document.body.scrollTop;
                }            
            document.body.classList.add('noscroll');
            scrollLock = "-"+Scroll+"px";
            document.body.style.top = scrollLock;   
        }
    else if (block == false)
        {
            document.body.classList.remove('noscroll');
            document.body.scrollTop = Scroll;
            delete Scroll;
        }
}

//used to generate styling for the MessageBox object, using the class set on the individual messages, can differentiate between sender and receiver
//INPUT className: email address of the user
//INPUT actor: originator or receiver, used to set the appropriate styling for the messages
function MakeCss(className, actor)
{
    if (actor == "originator")
        {
        if(!document.getElementById("me"))
            {
            var style = document.createElement('style');
            style.type = 'text/css';
            style.id = 'me';
            style.innerHTML = 'div[class="'+className+'"] { padding-right: 10px; padding-left: 10px; padding-top: 4px; padding-bottom: 6px; background: #016e96; max-width: 70%; color:white; float: right; border-radius: 15px 0px 15px 15px; word-break: break-all; margin-top: 3px;margin-right: 3px; }';
            document.getElementsByTagName('head')[0].appendChild(style);
            }
        }
    else if(actor == 'receiver')
        {
         if(!document.getElementById("you"))
            { $(document).ready(function() 
                    {
                        $('#loadingPageOverlay').removeClass('PageLoadingAdd');
                    });
            var style = document.createElement('style');
            style.type = 'text/css';
            style.id = 'you';
            document.getElementsByTagName('head')[0].appendChild(style);
            }
        document.getElementById("you").innerHTML = 'div[class="'+className+'"] {padding-right: 10px; padding-left: 10px; padding-top: 6px; padding-bottom: 6px; background: #f1f0f0; max-width: 70%; color: black; float: left; border-radius: 0px 15px 15px 15px; word-break: break-all; margin-top: 3px;margin-left: 3px}';       
        }
}

//used to the contacts bar to reduce the screen space used by the messaging system when not in use
function HideContactsBar(show = false)
{
    if (show)
        {
             document.getElementById("hideContacts").checked = false;
        }
    else
    {
        if(!document.getElementById("hideContacts").checked)
        {
            document.getElementById("hideContacts").checked = true;
        }
        else
        {
            document.getElementById("hideContacts").checked = false;
        }
    }
    
}
//used to force the enter key to submit the form instead of creating a new line
//INPUT pressedKey: event object triggered by firing DOM object
function SubmitFormEnter(pressedKey)
{
    if ((window.event ? event.keyCode : e.which) == 13)
        {
            event.preventDefault();
            var SubmitFunction = document.getElementById("submitText").onclick;
            SubmitFunction();
        }
}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
grabSession();

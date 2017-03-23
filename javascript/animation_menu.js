function ExpandMenu()
{
    var menu = document.getElementById("MainSideMenu");
    if (menu.classList.contains("HiddenIcons"))
        {
            click_overlay.style.zIndex="-1";
            menu.classList.remove("HiddenIcons");
        }
    else
        {
            menu.classList.add("HiddenIcons");
            click_overlay.style.zIndex="2"
        }
    
}
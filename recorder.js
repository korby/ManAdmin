debug=false;
jQuery( document ).ready(function() {
    jQuery(document).on("click", "a", function () {
        menuName = jQuery( this).text();
        menuHref = jQuery( this).attr("href")
        parent = getParent(jQuery( this))
        record("Click-" + parent + menuName + "|" + menuHref);
    });

    jQuery(document).on("click", "button", function () {
        buttonName = jQuery( this).text();
        record("Press-" + buttonName);
    });

    jQuery(document).on("click", "input[type='submit']", function () {
        buttonName = jQuery( this).attr("value");
        record("Press-" + buttonName);
    });

});

function getParent(a) {
    if(a.parent().parent().parent().parent() && a.parent().parent().parent().hasClass("wp-has-submenu")) {
        menuParentHref = a.parent().parent().parent().find("a").first().attr("href")
        menuParentName = a.parent().parent().parent().find("a").first().text()

        if(menuParentName == menuName || menuParentHref == "index.php") {
            return "";
        } else {
            return menuParentName + "|" + menuParentHref + ",";
        }

    } else {
        return "";
    }
}

function record(item) {
    itemSanitized = item.replace(/\s{2,}/g, ' ');
    itemSanitized = item.replace(/\t/g, '');
    itemSanitized = item.toString().trim().replace(/(\r\n|\n|\r)/g,"");
    if(debug) {
        alert(itemSanitized);
    }
    document.cookie = "visited=" + getCookie() + "@" + itemSanitized;
}

function getCookie() {
    var name = "visited=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

function removeCookie() {
    document.cookie = "visited=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}
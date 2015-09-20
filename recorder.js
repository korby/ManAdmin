debug=false ;
jQuery( document ).ready(function() {
    jQuery(document).on("click", function (e) {

        if(jQuery.inArray(e.currentTarget.activeElement.nodeName, ["DIV", "BODY"]) == -1) {
            type = jQuery(e.currentTarget.activeElement).prop('nodeName')
            if(type ==  "A") {
                name = jQuery(e.currentTarget.activeElement).text();
                if(jQuery(e.currentTarget.activeElement).attr("value")) {
                    name += jQuery(e.currentTarget.activeElement).attr("value")
                }
                parent = ""
                parent = getParent(jQuery(e.currentTarget.activeElement))
                record("Click-" + parent + name + "|" + jQuery(e.currentTarget.activeElement).attr("href"));
            }
            if(type ==  "SELECT") {
                jQuery(e.currentTarget.activeElement).find('option').each(function(){
                    if(jQuery(this).val() == "-1"){
                        name = jQuery(this).text();
                    }
                });

                if(jQuery(e.currentTarget.activeElement).find(":selected").attr("value") != "-1") {

                    choice = jQuery(e.currentTarget.activeElement).find(":selected").text()
                    record("Select-" + name + "|[" +choice + "]")
                }
            }

        } else if(e.originalEvent) {
            type = jQuery(e.originalEvent.explicitOriginalTarget).prop('nodeName')
            name = jQuery(e.originalEvent.explicitOriginalTarget).text()
            if(jQuery(e.originalEvent.explicitOriginalTarget).attr("value")) {
                name += jQuery(e.originalEvent.explicitOriginalTarget).attr("value")
            }

            record("Press-" + name);
        }
        if(debug) {
            console.debug(type + " > " + name);
            if(type !=  "SELECT") {
                alert("Show js console to see debug");
            }
        }

    });

});

function getParent(a) {
    if(a.parent().parent().parent().parent() && a.parent().parent().parent().hasClass("wp-has-submenu")) {
        menuParentHref = a.parent().parent().parent().find("a").first().attr("href")
        menuParentName = a.parent().parent().parent().find("a").first().text()

        if(menuParentName == a.text() || menuParentHref == "index.php") {
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
        if (item.indexOf("Select") == -1) {
            alert(itemSanitized);
        } else {

            console.debug(itemSanitized)
        }

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
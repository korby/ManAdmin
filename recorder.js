debug=false ;
jQuery( document ).ready(function() {

    // For chrome compatibility, handle these elements this way
    jQuery(document).on("click", 'button', function (e) {
        name = jQuery(this).text();

        record("Press-" + name);
        return;
    });
    jQuery(document).on("click", "input[type='submit']", function (e) {
        name = jQuery(this).text();
        if(jQuery(this).attr("value")) {
            name += jQuery(this).attr("value")
        }

        record("Press-" + name);
        return;
    });

    // For chrome compatibility, handle select this way
    jQuery(document).on("mousemove", function () {
        if(triggered_select != "") {

            jQuery(triggered_select).find('option').each(function(){
                if(jQuery(this).val() == "-1"){
                    name = jQuery(this).text();
                }
            });
            if(jQuery(triggered_select).find(":selected").attr("value") != "-1") {

                choice = jQuery(triggered_select).find(":selected").text()

                record("Select-" + name + "|[" +choice + "]")
            }

        }
        triggered_select = "";
    });
    jQuery(document).on("click", function (e) {

        if(jQuery.inArray(e.currentTarget.activeElement.nodeName, ["DIV", "BODY", "BUTTON", "SUBMIT"]) == -1) {
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
            // For chrome compatibility, handle select this way
            if(type ==  "SELECT") {
                triggered_select = jQuery(e.currentTarget.activeElement);
            }

        }

        excluded = ["A", "SELECT", "BUTTON", "INPUT"];
        object_source = get_by_original_event(e);

        if(jQuery.inArray(object_source[0], excluded) == -1 && jQuery.inArray(e.currentTarget.activeElement.nodeName, excluded) == -1){
            object_source = get_by_original_event(e);
            name = object_source[1];
            record("Click-" + name);
        }

        if(debug) {
            console.debug(type + " > " + name);
            if(type !=  "SELECT") {
                alert("Show js console to see debug");
            }
        }

    });

});

function get_by_original_event(e) {
    if(e.originalEvent) {

        if(e.originalEvent.srcElement) {
            target = e.originalEvent.srcElement;
        } else {
            target = e.originalEvent.explicitOriginalTarget;
        }
        type = jQuery(target).prop('nodeName')

        name = jQuery(target).text()
        if(jQuery(target).attr("value")) {
            name += jQuery(target).attr("value")
        }

        return [type,name]
    }
}

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
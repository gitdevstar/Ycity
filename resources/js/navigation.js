import $ from 'jquery';
window.jQuery = $;
window.$ = $;

$(document).ready(function() {
    // Benachrichtigungen zählen und anzeigen
    const numMessages = $('.unread').length;
    $("#messagesBubble").html(numMessages)

    // Tab Title anpassen und Bubble anzeigen
    var title = $("meta[name='title']").attr("content");
    if(numMessages === 1) {
        document.title = "("+numMessages+") Nachricht - "+title;
        $("#messagesBubble").fadeIn();
    } else if(numMessages > 1){
        document.title = "("+numMessages+") Nachrichten - "+title;
        $("#messagesBubble").fadeIn();
    }

    // Dropdown Hamburger Navi
    $(".menu-icon").on("click", function() {
        $(".menu-icon").toggleClass("open");
        $("nav ul").toggleClass("showing");
        if($("nav ul").hasClass("showing")){
            $("nav ul").animate({ maxHeight: '100vh'});
        } else {
            $("nav ul").animate({ maxHeight: '0vh'});
        }

    });

    // Navi Profilbild onclick Dropdown anzeigen
    $("#navbarDropdown").on("click", function() {
        $("#profileMenu").toggle();
        $("#profilePicture").toggleClass("open");
        $("#bell-icon").removeClass("open");
        if($("#messagesMenu").is(":visible")){
            $("#messagesMenu").toggle();
        }
    });

    // Navi Glocke onclick Benachrichtigungen anzeigen
    $("#messagesDropdown").on("click", function() {
        $("#messagesMenu").toggle();
        $("#bell-icon").toggleClass("open");
        $("#profilePicture").removeClass("open");
        if($("#profileMenu").is(":visible")){
            $("#profileMenu").toggle();
        }

        if($("#messagesMenu").is(":visible")){
            const numMessages = $('.unread').length;
            // Benachrichtigung auf gesehen ändern, wenn einige vorhanden
            if(numMessages !== 0){
                setTimeout(function(){
                    // Benachrichtigungen ausblenden und oranger Punkt entfernen nach einer halben Sekunde
                    $("#messagesBubble").fadeOut();
                    $(".unread").fadeOut();
                    document.title = title;
                }, 500);
                axios.post('/clearUnseenMessages')
                    .then((res) => {
                        $("#messagesBubble").html(0);
                    })
                    .catch((error) => {
                        console.log(error.data);
                    })
            }
        }
    });

    $(document).mouseup(function(e)
    {
        // bei Klick ausserhalb des Profil-PopUps
        if ($("#profileMenu").is(":visible") && (!$("#profileMenu").is(e.target) && $("#profileMenu").has(e.target).length === 0))
        {
            // ausblenden
            $("#profileMenu").hide();
            $("#profilePicture").removeClass("open");
        }

        // bei Klick ausserhalb des Benachrichtigung-PopUps
        if ($("#messagesMenu").is(":visible") && (!$("#messagesMenu").is(e.target) && $("#messagesMenu").has(e.target).length === 0))
        {
            // ausblenden
            $("#messagesMenu").hide();
            $("#bell-icon").removeClass("open");
        }
    });

    // Navi Scrolling Up Effect
    /*
    var lastScrollTop = 0;
    $(window).on("scroll", function() {
        if ($(window).width() > 992) {
            var st = $(this).scrollTop();
            var headerHeight = $('header').height();
            if (st > lastScrollTop){
                $('nav').css("top", "-"+headerHeight+"px");
                $("#blockRight:not(.notFixed)").css("margin-top", "-"+headerHeight+"px").css("max-height", "calc(100vh)");
            } else {
                $('nav').css("top", "0px");
                $("#blockRight:not(.notFixed)").css("margin-top", "0px").css("max-height", "calc(100vh - "+headerHeight+"px)");
            }
            lastScrollTop = st;
        } else {
            $("#blockRight:not(.notFixed)").css("margin-top", "-1px").css("max-height", "");
        }

    });*/
});

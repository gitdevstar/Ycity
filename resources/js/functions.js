function moveDomElements() {
    // Rechte Spalte verschieben je nach Bildschirmbreite
    if($(window).width() < 992) {
        // wenn zB eine Navigation, dann vor linke Spalte verschieben
        if($(".moveBefore").length){
            $('#blockRight').insertBefore('#blockLeft');
            $('#blockRight').css("margin-top", "-1px");
            $('#blockRight').css("border-bottom", "1px solid #DDD");
        } else {
            // ansonsten nach linke Spalte verschieben
            $('footer').insertAfter('#blockRight');
        }
    } else {
        if($(".moveBefore")){
            $('#blockRight').insertAfter('#blockLeft');
            $('#blockRight').css("margin-top", "0px");
            $('#blockRight').css("border-bottom", "0px solid #DDD");
        } else {
            $('footer').insertAfter('#blockLeftInner');
        }

    }

    if($(window).width() < 768) {
        if ($(".mobileMenu").length) {
            $("#messagesContainer").appendTo(".mobileMenu");
            $("#profileContainer").appendTo(".mobileMenu");
        }
    } else {
        if($(".mobileMenu").length){
            $("#messagesContainer").appendTo("#messagesNavpoint");
            $("#profileContainer").appendTo("#profileNavpoint");
        }
    }
}

// Banner schliessen
$(document).on("click", ".showImage, .showPdf", function(e){

    let pdfLink = $(this).attr('data-src');
    console.log(pdfLink);

    if (pdfLink === '') return console.warn('[PDF Viewer] Invalid link to pdf');

    $('<div>', {
        id:    'imageviewer-wrapper',
        css: {
            position:        'fixed',
            top:             '0',
            left:            '0',
            zIndex:          '99999',
            display:         'flex',
            justifyContent:  'center',
            alignItems:      'center',
            width:           '100%',
            height:          '100vh',
            padding:          '1rem',
            backgroundColor: 'rgba(0,0,0,0.5)',
        },
        append: $('<iframe>', {
            id:  'imageviewer-image',
            frameBorder:  '0',
            src: pdfLink,
            css: {
                width:              '100%',
                height:              '100%',
                maxHeight:            '90vh',
                maxWidth:            '85vw',
            },
        }).add($('<div>', {
            id:  'imageviewer-close',
            css: {
                position:           'fixed',
                top:                '0',
                right:              '0',
                zIndex:             '100000',
                width:              '55px',
                height:             '55px',
                borderRadius:       '6px',
                padding:            '20px',
                margin:            '10px',
                backgroundColor:    'rgba(0,0,0,0.5)',
                cursor:             'pointer',
                translation:        '0.5s ease',
            },
            append: $('<div>', {
                css: {
                    width:              '15px',
                    height:             '15px',
                    float:              'right',
                    backgroundImage:    'url(/images/icons/close.png)',
                    backgroundPosition: 'center center',
                    backgroundSize:     '15px',
                    backgroundRepeat:     'no-repeat',
                }
            }),
            on: {
                click: function(event) {
                    $('#imageviewer-wrapper').remove();
                }
            }
        })),
    }).appendTo('body');
});

$(window).on("resize", moveDomElements);
$(document).ready(function(){
    
    if($(window).width() < 992) {
        if($(".moveBefore").length){
            $('#blockRight').addClass("showOnLoad");
        }
    }

    // Rechte Spalte verschieben je nach Bildschirmbreite
    moveDomElements();

    if($('#blockLeftInner').children(':visible').length == 0) {
        $("#blockLeft").hide();
    }

    if($('#blockRightInner').children(':visible').length == 0) {
        $("#blockRight").hide();
    }

    // Loader Animation ganz oben
    $("#loader").animate({width: '100%'}, 500, function() {
        if($('#chatContainer').length !== 0){

            setTimeout(function(){
                $("#chatContainer").animate({ scrollTop: $('#chatContainer').prop("scrollHeight")}, 750);
            },500);

        }
        $("#loader").fadeOut();

        // Linker Teil der App anzeigen lassen mit fadein effect
        $('.showOnLoad').removeClass('showOnLoad');
    });

    // Banner schliessen
    $(".close-banner").on("click", function(e){
        $(this).parent().animate({ height: '0px', paddingTop: '0px', paddingBottom: '0px', margin: '0px' }, function(e){
            $(this).hide();
        });
    });


    // Passwort anzeigen
    $("#showPassword").on("click", function(){
        var pwInput = document.getElementById("password");
        if (pwInput.type === "password") {
            pwInput.type = "text";
            this.src = "/images/icons/no-eye.png";
        } else {
            pwInput.type = "password";
            this.src = "/images/icons/eye.png";
        }
    });


    $("#showPassword").on("change", function(){
        var pwInput = document.getElementById("password");
        if (pwInput.type === "password") {
            pwInput.type = "text";
            this.src = "/images/icons/no-eye.png";
        } else {
            pwInput.type = "password";
            this.src = "/images/icons/eye.png";
        }
    });


});

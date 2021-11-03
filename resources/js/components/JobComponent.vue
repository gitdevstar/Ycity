<template>

</template>

<script>
var specificationPrices;
var totalPrice = 0;

function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '\'' + '$2');
    }
    return x1 + x2;
}

$( function() {
    // Draggable Spezifikationen mit jQuery UI sortable
    $( "#chosenSpecifications, #specifications" ).sortable({
        connectWith: ".connectedSortable",
        delay: 200,
        cancel: '.not-draggable',
        update: function (event, ui) {
            var price = totalPrice;
            var cost = specificationPrices[ui.item.attr('data-content')]["price"];
            // nachdem gedraggt wurde, entweder Preis hinzu- oder abrechnen
            if(ui.item.parent().is("#chosenSpecifications")){
                price = parseFloat(price) + parseFloat(cost);
                totalPrice = price;
                price = addCommas(price.toFixed(2));
                $("#priceTotal").html(price);

                if(ui.item.hasClass("contact")){
                    $("#contact").show();
                    $("#noContact").hide();
                }
            } else if(ui.item.parent().is("#specifications")){
                price = parseFloat(price) - parseFloat(cost);
                totalPrice = price;
                price = addCommas(price.toFixed(2));
                $("#priceTotal").html(price);

                if(ui.item.hasClass("contact")){
                    $("#noContact").show();
                    $("#contact").hide();
                }
            }

        }
    });
} );

function md5(inputString) {
    var hc="0123456789abcdef";
    function rh(n) {var j,s="";for(j=0;j<=3;j++) s+=hc.charAt((n>>(j*8+4))&0x0F)+hc.charAt((n>>(j*8))&0x0F);return s;}
    function ad(x,y) {var l=(x&0xFFFF)+(y&0xFFFF);var m=(x>>16)+(y>>16)+(l>>16);return (m<<16)|(l&0xFFFF);}
    function rl(n,c)            {return (n<<c)|(n>>>(32-c));}
    function cm(q,a,b,x,s,t)    {return ad(rl(ad(ad(a,q),ad(x,t)),s),b);}
    function ff(a,b,c,d,x,s,t)  {return cm((b&c)|((~b)&d),a,b,x,s,t);}
    function gg(a,b,c,d,x,s,t)  {return cm((b&d)|(c&(~d)),a,b,x,s,t);}
    function hh(a,b,c,d,x,s,t)  {return cm(b^c^d,a,b,x,s,t);}
    function ii(a,b,c,d,x,s,t)  {return cm(c^(b|(~d)),a,b,x,s,t);}
    function sb(x) {
        var i;var nblk=((x.length+8)>>6)+1;var blks=new Array(nblk*16);for(i=0;i<nblk*16;i++) blks[i]=0;
        for(i=0;i<x.length;i++) blks[i>>2]|=x.charCodeAt(i)<<((i%4)*8);
        blks[i>>2]|=0x80<<((i%4)*8);blks[nblk*16-2]=x.length*8;return blks;
    }
    var i,x=sb(inputString),a=1732584193,b=-271733879,c=-1732584194,d=271733878,olda,oldb,oldc,oldd;
    for(i=0;i<x.length;i+=16) {olda=a;oldb=b;oldc=c;oldd=d;
        a=ff(a,b,c,d,x[i+ 0], 7, -680876936);d=ff(d,a,b,c,x[i+ 1],12, -389564586);c=ff(c,d,a,b,x[i+ 2],17,  606105819);
        b=ff(b,c,d,a,x[i+ 3],22,-1044525330);a=ff(a,b,c,d,x[i+ 4], 7, -176418897);d=ff(d,a,b,c,x[i+ 5],12, 1200080426);
        c=ff(c,d,a,b,x[i+ 6],17,-1473231341);b=ff(b,c,d,a,x[i+ 7],22,  -45705983);a=ff(a,b,c,d,x[i+ 8], 7, 1770035416);
        d=ff(d,a,b,c,x[i+ 9],12,-1958414417);c=ff(c,d,a,b,x[i+10],17,     -42063);b=ff(b,c,d,a,x[i+11],22,-1990404162);
        a=ff(a,b,c,d,x[i+12], 7, 1804603682);d=ff(d,a,b,c,x[i+13],12,  -40341101);c=ff(c,d,a,b,x[i+14],17,-1502002290);
        b=ff(b,c,d,a,x[i+15],22, 1236535329);a=gg(a,b,c,d,x[i+ 1], 5, -165796510);d=gg(d,a,b,c,x[i+ 6], 9,-1069501632);
        c=gg(c,d,a,b,x[i+11],14,  643717713);b=gg(b,c,d,a,x[i+ 0],20, -373897302);a=gg(a,b,c,d,x[i+ 5], 5, -701558691);
        d=gg(d,a,b,c,x[i+10], 9,   38016083);c=gg(c,d,a,b,x[i+15],14, -660478335);b=gg(b,c,d,a,x[i+ 4],20, -405537848);
        a=gg(a,b,c,d,x[i+ 9], 5,  568446438);d=gg(d,a,b,c,x[i+14], 9,-1019803690);c=gg(c,d,a,b,x[i+ 3],14, -187363961);
        b=gg(b,c,d,a,x[i+ 8],20, 1163531501);a=gg(a,b,c,d,x[i+13], 5,-1444681467);d=gg(d,a,b,c,x[i+ 2], 9,  -51403784);
        c=gg(c,d,a,b,x[i+ 7],14, 1735328473);b=gg(b,c,d,a,x[i+12],20,-1926607734);a=hh(a,b,c,d,x[i+ 5], 4,    -378558);
        d=hh(d,a,b,c,x[i+ 8],11,-2022574463);c=hh(c,d,a,b,x[i+11],16, 1839030562);b=hh(b,c,d,a,x[i+14],23,  -35309556);
        a=hh(a,b,c,d,x[i+ 1], 4,-1530992060);d=hh(d,a,b,c,x[i+ 4],11, 1272893353);c=hh(c,d,a,b,x[i+ 7],16, -155497632);
        b=hh(b,c,d,a,x[i+10],23,-1094730640);a=hh(a,b,c,d,x[i+13], 4,  681279174);d=hh(d,a,b,c,x[i+ 0],11, -358537222);
        c=hh(c,d,a,b,x[i+ 3],16, -722521979);b=hh(b,c,d,a,x[i+ 6],23,   76029189);a=hh(a,b,c,d,x[i+ 9], 4, -640364487);
        d=hh(d,a,b,c,x[i+12],11, -421815835);c=hh(c,d,a,b,x[i+15],16,  530742520);b=hh(b,c,d,a,x[i+ 2],23, -995338651);
        a=ii(a,b,c,d,x[i+ 0], 6, -198630844);d=ii(d,a,b,c,x[i+ 7],10, 1126891415);c=ii(c,d,a,b,x[i+14],15,-1416354905);
        b=ii(b,c,d,a,x[i+ 5],21,  -57434055);a=ii(a,b,c,d,x[i+12], 6, 1700485571);d=ii(d,a,b,c,x[i+ 3],10,-1894986606);
        c=ii(c,d,a,b,x[i+10],15,   -1051523);b=ii(b,c,d,a,x[i+ 1],21,-2054922799);a=ii(a,b,c,d,x[i+ 8], 6, 1873313359);
        d=ii(d,a,b,c,x[i+15],10,  -30611744);c=ii(c,d,a,b,x[i+ 6],15,-1560198380);b=ii(b,c,d,a,x[i+13],21, 1309151649);
        a=ii(a,b,c,d,x[i+ 4], 6, -145523070);d=ii(d,a,b,c,x[i+11],10,-1120210379);c=ii(c,d,a,b,x[i+ 2],15,  718787259);
        b=ii(b,c,d,a,x[i+ 9],21, -343485551);a=ad(a,olda);b=ad(b,oldb);c=ad(c,oldc);d=ad(d,oldd);
    }
    return rh(a)+rh(b)+rh(c)+rh(d);
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

export default {
    name: "JobComponent",
    data(){
        return{
            timer: null,
            success: false,
            error: false,
            contactAdmin: false,
            errors: {},
            formData: {
                name: $('#name').val(),
                description: $('#description').html(),
                categories_fk: $('#categories_fk').val(),
                subcategories_fk: $('#subcategories_fk').val(),
                specifications_fk: $('#specifications_fk').val(),
                complexities_fk: $('#complexities_fk').val(),
                cost: $('#cost').val(),
                payment_types_fk: $('#payment_types_fk').val(),
                status_fk: $('#status_fk').val(),
                end: $('#end').val(),
                eventdate: $('#eventdate').val(),
                location: false,
                street: $('#street').val(),
                plz: $('#plz').val(),
                attachments: 0,
                /*skills: [],
                activeSkills: true,
                skillFilter: $('#skillFilter').val(),*/
                specificationsText: "-",
                categoryText: "",
                subcategoryText: "",
                //skillsText: "-",
            },
            jobSearch: {
                term: $("#jobSearch").val(),
                category: $("#jobCategory").val(),
                subcategory: $("#jobSubcategory").val(),
                date: $("#date").val(),
            }
        }
    },
    methods:{
        /*
        filterSkills(){
            $.extend($.expr[":"], {
                "containsIN": function(elem, i, match, array) {
                    return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
                }
            });

            clearTimeout(this.timer);
            const term = this.formData.skillFilter;
            this.timer = setTimeout(() => {
                $("#noSkillsFound").hide();

                $('.skill:containsIN('+ term +')').parent().show();
                $('.skill:not(:containsIN('+ term +'))').parent().hide();

                if($('#skillsContainer').children(':visible').length == 0) {
                    $("#noSkillsFound").fadeIn();
                }
            }, 1000);
        },
        toggleSelectedSkill(name, e){
            if(!$(e.currentTarget).hasClass("hidden")){
                $(e.currentTarget).toggleClass("selected");
                var chosenSkillsText = "";

                $('.skill.selected').each(function(i, obj) {
                    chosenSkillsText += $(obj).html() + ", "
                });
                chosenSkillsText = chosenSkillsText.replace(/,\s*$/, "");

                if(chosenSkillsText !== ""){
                    this.formData.skillsText = chosenSkillsText;
                } else {
                    this.formData.skillsText = "-";
                }
            }
        },*/
        // einzelnes Eingabefeld validieren
        validateInputField(event, fieldName){
            axios.post('/ycity/job', this.formData)
                .then((res) => {
                    this.onSuccess(res.data.message);
                })
                .catch((error) => {
                    if(error.response.status == 422 || error.response.status == 405){
                        // wenn der Input keine Fehler hat
                        if(error.response.data.errors[fieldName] === undefined){
                            // nächste Seite anzeigen und aktuelle ausblenden
                            $(event.target).parent().parent().parent().fadeOut(500).promise().done(function(){
                                $(event.target).parent().parent().parent().next(".row").fadeIn(500);
                            });
                        } else {
                            // wenn der Input Fehler hat, Fehler anzeigen
                            var fieldError = {};
                            fieldError[fieldName] = error.response.data.errors[fieldName];
                            this.setErrors(fieldError);
                        }
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })

        },
        // einzelner Select validieren
        validateSelect(event, fieldName){
            axios.post('/ycity/job', this.formData)
                .then((res) => {
                    this.onSuccess(res.data.message);
                })
                .catch((error) => {
                    if(error.response.status == 422 || error.response.status == 405){
                        // wenn der Input keine Fehler hat
                        if(error.response.data.errors[fieldName] === undefined){
                            // nächste Seite anzeigen und aktuelle ausblenden
                            $(event.target).parent().parent().parent().fadeOut(500).promise().done(function(){
                                $(event.target).parent().parent().parent().next(".row").css("display", "flex").hide().fadeIn(500);
                                // Kontaktaufnahme anstelle der Auswahl von der Subkategorie einblenden, wenn Subkategorien existieren
                                if($("#subcategories").is(":visible")) {
                                    if($('#subcategories').children(':visible').length == 0){
                                        $("#categoryContact").show();
                                        $(".noContactCategory").hide();
                                    }else {
                                        $("#categoryContact").hide();
                                        $(".noContactCategory").show();
                                    }
                                }
                            });
                        } else {
                            // wenn der Input Fehler hat, Fehler anzeigen
                            var fieldError = {};
                            fieldError[fieldName] = error.response.data.errors[fieldName];
                            this.setErrors(fieldError);
                        }
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })

        },
        // Klick auf Kategorie: auswählen und Subkategorien ein und ausblenden
        toggleCategory(name, event, catId){
            if(!$(event.currentTarget).hasClass("selected")){
                Vue.delete(this.errors, "categories_fk");
                $(".categoryBoxItem").removeClass("selected");
                $('#categories_fk option').removeAttr("selected");
                // Seite zurück
                $(event.target).addClass("selected");
                $('#categories_fk').val(catId).change();

                this.formData.categories_fk = catId;
                this.formData.categoryText = name;
                $('.subcategoryBoxItem').each(function(i, obj) {
                    if($(obj).attr("data-category") === md5(catId)){
                        $(obj).removeClass("hidden");
                    } else {
                        $(obj).addClass("hidden");
                    }
                });
                /*
                $('#skillsContainer label').each(function(i, obj) {
                    if($(obj).attr("data-category") === md5(catId)){
                        $(obj).removeClass("hidden");
                    } else {
                        $(obj).addClass("hidden");
                    }
                });
                */
                this.getSubcategoryPrices();
            }
        },
        // Klick auf Subkategorie: auswählen und Spezifikationen aus der DB herauslesen
        toggleSubcategory(name, event, subcatId){
            if(!$(event.currentTarget).hasClass("selected")) {
                Vue.delete(this.errors, "subcategories_fk");
                $(".subcategoryBoxItem").removeClass("selected");
                $('#subcategories_fk option').removeAttr("selected");
                // Seite zurück
                $(event.target).addClass("selected");
                $('#subcategories_fk').val(subcatId).change();

                this.formData.subcategories_fk = subcatId;
                this.formData.subcategoryText = name;

                var priceSubcategory = this.subcategoryPrices[md5(subcatId)];
                totalPrice = priceSubcategory;
                $("#priceTotal").html(addCommas(priceSubcategory));
                this.getSpecifications($(event.target).hasClass("hidden"));
                this.getSpecificationPrices();
            }
        },
        // Klick auf Location: div mit Strasse und PLZ ein und ausblenden
        toggleLocation(){
            $("#locationInfo").toggleClass("open");
            if(!$("#locationInfo").hasClass("open")){
                Vue.delete(this.errors, "street");
                Vue.delete(this.errors, "plz");
            }
        },
        getSubcategoryPrices(){
            const headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                '_token': $('meta[name="csrf-token"]').attr('content'),
            }
            axios.post('/getSubcategoryPrices', {"categories_fk": this.formData.categories_fk}, {
                headers: headers
            }).then((res) => {
                    this.subcategoryPrices = res.data;

                    var category = getUrlParameter('category');
                    var subcategory = getUrlParameter('subcategory');
                    if(subcategory !== false && category !== false){
                        $( ".subcategoryBoxItem[data-subcategory='"+md5(subcategory)+"']" ).addClass("selected");
                        this.formData.subcategories_fk = subcategory;
                        this.formData.subcategoryText = $( ".subcategoryBoxItem[data-subcategory='"+md5(subcategory)+"']" ).text();

                        $('.subcategoryBoxItem').each(function(i, obj) {
                            if($(obj).attr("data-category") == md5(category)){
                                $(obj).removeClass("hidden");
                            } else {
                                $(obj).addClass("hidden");
                            }
                        });
/*
                        $('#skillsContainer label').each(function(i, obj) {
                            if($(obj).attr("data-category") == md5(category)){
                                $(obj).removeClass("hidden");
                            } else {
                                $(obj).addClass("hidden");
                            }
                        });*/
                        var priceSubcategory = this.subcategoryPrices[md5(subcategory)];
                        totalPrice = priceSubcategory;
                        $("#priceTotal").html(addCommas(priceSubcategory));
                        this.getSpecifications(false);
                        this.getSpecificationPrices();
                    }
                })
                .catch((error) => {
                    if(error.response.status == 422 || error.response.status == 405){
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })
        },
        getSpecificationPrices(){
            const headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                '_token': $('meta[name="csrf-token"]').attr('content'),
            }
            axios.post('/getSpecificationPrices', {"subcategories_fk": this.formData.subcategories_fk}, {
                headers: headers
            }).then((res) => {
                    specificationPrices = res.data;
                })
                .catch((error) => {
                    if(error.response.status == 422 || error.response.status == 405){
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })
        },
        // Kontaktanfrage anzeigen / ausblenden
        toggleContactContainer(event){
            if($(event.target).next(".contactContainer").is(":visible")){
                $(event.target).next(".contactContainer").fadeOut(500);
                $(event.target).next(".contactContainer")
            } else {
                $(event.target).next(".contactContainer").fadeIn(500);
                $('html, body').animate({
                    scrollTop: $(event.target).next(".contactContainer").offset().top
                }, 600);
            }
        },
        // weiter Button
        goNext(event){
            // Seite weiter
            $(event.target).closest(".row").fadeOut(500).promise().done(function(){
                $(event.target).closest(".row").next(".row").css("display", "flex").hide().fadeIn(500);
            });

        },
        // zurück Button
        goBack(event){
            $("#categoryContact").hide();
            // Seite zurück
            $(event.target).closest(".row").fadeOut(500).promise().done(function(){
                $(event.target).closest(".row").prev(".row").css("display", "flex").hide().fadeIn(500);
            });
        },
        // nach Klick auf weiter bei Spezifikationen: ausgewählte Spezifikationen hinzufügen
        addSpecifications(event){
            var chosenSpecifications = "";
            var chosenSpecificationsText = "";

            $('#chosenSpecifications').children().each(function(i, obj) {
                chosenSpecifications += $(obj).attr("data-content") + ", "
                chosenSpecificationsText += specificationPrices[$(obj).attr("data-content")]["name"] + ", "
            });

            chosenSpecifications = chosenSpecifications.replace(/,\s*$/, "");
            this.formData.specifications_fk = chosenSpecifications;

            chosenSpecificationsText = chosenSpecificationsText.replace(/,\s*$/, "");
            if(chosenSpecificationsText !== ""){
                this.formData.specificationsText = chosenSpecificationsText;
            } else {
                this.formData.specificationsText = "-";
            }
            // Preis hinzufügen
            this.formData.cost = totalPrice;
            // Seite weiter
            this.goNext(event);

        },
        // Job erstellen
        createJob(){
            // prüfen, ob Anhang existiert
            this.formData.attachments = sessionStorage.getItem("jobAttachmentUpload");
            const headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                '_token': $('meta[name="csrf-token"]').attr('content'),
            }
            axios.post('/ycity/job', this.formData, {
                headers: headers
            }).then((res) => {
                    //this.onSuccess(res.data.message);
                    $(".hideOnSubmit").fadeOut(500).promise().done(function(){
                        $("#jobSuccess").fadeIn(500);
                    });
                })
                .catch((error) => {
                    if(error.response.status == 422 || error.response.status == 405){
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })
        },
        // Job bearbeiten
        editJob(id){
            const headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                '_token': $('meta[name="csrf-token"]').attr('content'),
            }
            axios.put('/ycity/job/'+id, this.formData, {
                headers: headers
            }).then((res) => {
                    this.onSuccess(res.data.message);
                })
                .catch((error) => {
                    if(error.response.status == 422 || error.response.status == 405){
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })

        },
        // Job Auflistung für Creator
        getJobs(type){
            clearTimeout(this.timer);

            // bei geänderter Kategorie, Subkategorie dieser auswählen
            if(type === "category"){
                var catId = $("#jobCategory").val();
                $('#jobSubcategory option').hide(); // Alle Optionen ausblenden
                $('#jobSubcategory option[data-id="' + catId + '"]').show(); // alle zugehörige Subkategorien einblenden
            }

            this.timer = setTimeout(() => {
                axios.post('/getJobs', this.jobSearch)
                    .then((res) => {
                        $("#jobList").fadeOut().promise().done(function(){
                            $("#jobList").html(res.data).fadeIn();
                        });
                    })
                    .catch((error) => {
                        if(error.response.status == 422 || error.response.status == 405){
                            this.setErrors(error.response.data.errors);
                        } else {
                            this.onFailure(error.response.data.message);
                        }
                    })
            }, 1000);
        },
        getPlace() {
            if(this.formData.plz == ""){
                Vue.delete(this.errors, "plz");
            } else {
                axios.post('/getPlaceFromDB', { key: this.formData.plz })
                    .then((place) => {

                        if(place.data == ""){
                            this.placeFound = false;
                            this.errors.plz = {0: "Ort nicht gefunden"};
                            this.error = true;
                        } else {
                            this.placeFound = true;
                            Vue.delete(this.errors, "plz");
                        }
                        $("#plz_ort").val(place.data);
                    })
                    .catch((error) => {
                        console.log(error.response.data.errors);
                    })
            }

            this.hasError("plz");
        },
        // Spezifikationen herauslesen
        getSpecifications(hidden){
            if(!hidden){
                axios.post('/getSpecifications', {"subcategories_fk": this.formData.subcategories_fk})
                    .then((res) => {
                        $("#chosenSpecification").empty();
                        $("#specifications").html(res.data).fadeIn();
                    })
                    .catch((error) => {
                        if(error.response.status == 422 || error.response.status == 405){
                            this.setErrors(error.response.data.errors);
                        } else {
                            this.onFailure(error.response.data.message);
                        }
                    })
            }
        },
        // Job löschen
        deleteJob(id){
            axios.delete('/ycity/job/'+id)
                .then((res) => {
                    location.href = '/ycity';
                })
                .catch((error) => {
                    this.contactAdmin = true;
                })
        },
        // Job löschen
        closeJob(id){
            axios.post('/closeJob', {"job_id": id})
                .then((res) => {
                    $("#jobFinishDecision").fadeOut().promise().done(function(){
                        $("#jobFinishSuccess").fadeIn();
                    });
                })
                .catch((error) => {
                    if(error.response.status == 422 || error.response.status == 405){
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })
        },
        onSuccess(){
            this.error = false;
            this.success = true;
            $(".hideOnSubmit").fadeOut();
        },

        onFailure(message){
            this.success = false;
            this.error = true;
        },
        setErrors(errors) {
            this.errors = errors;
        },
        hasError(fieldName){
            return (fieldName in this.errors);
        },
        clearError(event){
            if(event.target.name !== "plz"){
                Vue.delete(this.errors, event.target.name);
            }
        }
    },
    computed:{
        hasAnyErrors(){
            return Object.keys(this.errors).length > 0;
        }
    },
    created: function () {

    },
    mounted: function () {

        if(document.getElementById("createJobContainer")) {
            $("body").append('<link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">\n' +
                '                <script src="https://assets.calendly.com/assets/external/widget.js" type="text/javascript" async />');
        }
/*
        if(document.getElementById("editJob")){
            // aktive Skills herauslesen und vueJS mitgeben
            const job_id = document.querySelector("meta[name='job-id']").getAttribute('content');
            axios.post('/getActiveSkills', {"type": "job", "id": job_id})
                .then((res) => {
                    let activeSkills = res.data;

                    activeSkills.forEach(function(value, item) {
                        this.formData.skills.push(value);
                    }.bind(this));

                    //console.log(this.formData.skills);
                })
                .catch((error) => {
                    console.log(error);
                })
        }
*/
        // Vorauswahl der Kategorie falls in URL
        var category = getUrlParameter('category');
        if(category !== false){
            $( ".categoryBoxItem[data-category='"+md5(category)+"']" ).addClass("selected");
            $('#categories_fk').val(category).change();
            this.formData.categories_fk = category;
            this.formData.categoryText = $( ".categoryBoxItem[data-category='"+md5(category)+"']" ).text();
            this.getSubcategoryPrices();

        }


    },
    beforeMount(){

    },
}


</script>

<style scoped>

</style>

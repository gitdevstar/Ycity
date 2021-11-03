<template>

</template>

<script>
export default {
    name: "CreatorComponent",
    data(){
        return{
            timer: null,
            job: $('#job').val(),
            formType: $('#formType').val(),
            placeFound: false,
            organisationPlaceFound: false,
            success: false,
            contactAdmin: false,
            error: false,
            errors: {},
            noOrganisation: {
                ahv_nr: $('#ahv_nr').val(),
                nationality: $('#nationality').val(),
                children: $('input[name="children"]').val(),
            },
            organisation: {
                organisation_type: $('#organisation_type').val(),
                organisation_name: $('#organisation_name').val(),
                organisation_uid: $('#organisation_uid').val(),
                organisation_sva: $('#svaBase64String').val(),
                organisation_street: $('#organisation_street').val(),
                organisation_plz: $('#organisation_plz').val(),
                organisation_telephone: $('#organisation_telephone').val(),
            },
            formData: {
                birthdate: $('#birthdate').val(),
                description: $('#description').val(),
                street: $('#street').val(),
                plz: $('#plz').val(),
                telephone: $('#telephone').val(),
                apply_text:  $.trim($("#apply_text").val()),
                skills: [],
                skillsText: "-",
                website: $('#website').val(),
            },
            searchTerm: $("#creatorSearch").val(),
            creatorType: $('#creatorType').val(),
        }
    },
    methods:{
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
        },
        creatorPreviousPage(){
            $("#creatorsSkills").fadeOut(500).promise().done(function(){
                $("#creatorsInfo").fadeIn(500);
            });
        },
        // weiter Button
        goNext(event){
            // Seite weiter
            $(event.target).closest(".creatorContent").fadeOut(500).promise().done(function(){
                $(event.target).closest(".creatorContent").next(".creatorContent").fadeIn(500);
            });
        },
        // zurück Button
        goBack(event){
            // Seite zurück
            $(event.target).closest(".creatorContent").fadeOut(500).promise().done(function(){
                $(event.target).closest(".creatorContent").prev(".creatorContent").fadeIn(500);
            });
        },
        validatePreCreatorInfos(event){
            if(this.creatorType == "company"){
                const image = $("#svaBase64String").val();
                if(image !== ""){
                    $("#sva-feedback").fadeOut();
                    this.organisation.organisation_sva = image;
                    this.organisation.creatorType = "company";

                    axios.post('/validatePreCreatorInfos', this.organisation)
                        .then((res) => {
                            this.error = false;
                            this.goNext(event);
                        })
                        .catch((error) => {
                            if(error.response.status == 422 || error.response.status == 405){
                                this.setErrors(error.response.data.errors);
                            } else {
                                this.onFailure(error.response.data.message);
                            }
                        })
                } else {
                    $("#sva-feedback").fadeIn();
                }
            } else {
                this.organisation.creatorType = "no company";
                axios.post('/validatePreCreatorInfos', this.noOrganisation)
                    .then((res) => {
                        this.error = false;
                        this.goNext(event);
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
        validateCreatorInfos(){
            axios.post('/validateCreatorInfos', this.formData)
                .then((res) => {
                    this.error = false;
                    $("#creatorsInfo").fadeOut(500).promise().done(function(){
                        $("#creatorsSkills").fadeIn(500);
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
        createCreator(){
            let formData = $('form').serialize();
            formData += '&organisation_sva=' + this.organisation.organisation_sva;
            formData += '&skills=' + this.formData.skills;
            formData += '&skills_text=' + this.formData.skillsText;
            $("#creatorLoad").fadeIn();
            axios.post('/ycity/creator', formData)
                .then((res) => {
                    this.error = false;
                    $(".hideOnSubmit").fadeOut(500).promise().done(function(){
                        //$(".hideOnSubmit").remove();
                        $("#creatorSuccess").fadeIn(500);
                    });
                })
                .catch((error) => {
                    $("#creatorLoad").fadeOut();
                    if(error.response.status == 422 || error.response.status == 405){
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })

        }, updateCreator(id){
            axios.put('/ycity/creator/'+id, this.formData)
                .then((res) => {
                   this.onSuccess(res.data.message);
                })
                .catch((error) => {
                    if(error.response.status == 422 || error.response.status == 405){
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })

        }, deleteCreator(e){
            let id = e.currentTarget.getAttribute("data-id");
            axios.delete('/ycity/creator/'+id)
                .then((res) => {
                   this.onSuccess(res.data.message);
                   $("#"+id).remove();
                })
                .catch((error) => {
                    this.contactAdmin = true;
                })
        },
        // Creator Suche für Client
        getCreators(hashed_id){
            clearTimeout(this.timer);

            this.timer = setTimeout(() => {
                axios.post('/getCreators', {"term": this.searchTerm, "job_hash": hashed_id}, {
                    headers: headers
                })
                    .then((res) => {
                        $("#creatorList").fadeOut().promise().done(function(){
                            $("#creatorList").html(res.data).fadeIn();
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
        uploadPortfolioFiles(){
            // prüfen, ob Dateien hochgeladen wurden
            if(sessionStorage.getItem("portfolioUpload")){
                Vue.delete(this.errors, "portfolioUpload");

                const headers = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                axios.post('/storeTempPortfolioUploads', {
                    headers: headers
                })
                    .then((res) => {
                        $(".hideOnSubmit").fadeOut(500).promise().done(function(){
                            $(".hideOnSubmit").remove();
                            $("#creatorSuccess").fadeIn(500);
                        });
                    })
                    .catch((error) => {
                        if(error.response.status == 500){
                            this.errors.portfolioUpload = {0: "Bild existiert bereits."};
                        } else {
                            this.errors.portfolioUpload = {0: "Fehler, bitte kontaktieren Sie den Administrator."};
                        }
                    })
            } else {
                this.errors.portfolioUpload = {0: "Keine Bilder ausgewählt."};
            }
        }, deletePortfolioFile(div, creator, image){
            const headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            // prüfen, ob Dateien hochgeladen wurden
            axios.post('/deletePortfolioFile', {"creator":creator, "image": image})
                .then((res) => {
                    $(".portfolioItem:nth-of-type("+div+")").remove();
                })
                .catch((error) => {
                    console.log(error.response.data.message);
                })
        }, updateCreatorSkills(creator_id){
            // prüfen, ob Dateien hochgeladen wurden
            axios.post('/updateCreatorSkills', {"id":creator_id, "skills": this.formData.skills})
                .then((res) => {
                    $(".hideOnSubmit").fadeOut(500).promise().done(function(){
                        $(".hideOnSubmit").remove();
                        $("#creatorSuccess").fadeIn(500);
                    });
                })
                .catch((error) => {
                    console.log(error.response.data.message);
                })
        }, getPlace(id) {
            let key;
            let place_input;
            if(id == "plz"){
                key = this.formData.plz;
                place_input = "plz_ort";
            } else {
                key = this.organisation.organisation_plz;
                place_input = "organisation_plz_ort";
            }
            if(key !== ""){
                //console.log("PLZ ausgefüllt");
                axios.post('/getPlaceFromDB', { key: key})
                .then((place) => {
                    if(place.data == ""){
                        console.log("kein Ort gefunden");

                        if(id == "plz"){
                            this.placeFound = false;
                        } else {
                            this.organisationPlaceFound = false;
                        }
                        this.errors[id] = {0: "Ort nicht gefunden"};
                        //$('button[type="submit"]').attr("disabled", true);
                    } else {
                        //console.log("Ort gefunden");

                        if(id == "plz"){
                            this.placeFound = true;
                        } else {
                            this.organisationPlaceFound = true;
                        }

                        Vue.delete(this.errors, id);
                        //$('button[type="submit"]').removeAttr("disabled");
                    }
                    $("#"+place_input).val(place.data);
                })
                .catch((error) => {
                    //console.log("error");
                    console.log(error);
                })
            } else {
                //console.log("keine PLZ ausgefüllt");
                $("#"+place_input).val("");
                this.errors[id] = {0: "Ort nicht gefunden"};
            }
            this.hasError(id);
        },applyToJob() {
            axios.post('/applyToJob', { "apply_text": this.formData.apply_text, "job": this.job })
                .then((res) => {
                    if(res.data){
                        this.onSuccess();
                    } else {
                        this.onFailure();
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
        onSuccess(){
            this.error = false;
            this.success = true;
            $(".hideOnSubmit").fadeOut();
        },

        onFailure(message){
            this.success = false;
            this.error = true;
        },
        setErrors(errors, id = "") {
            this.errors = errors;
            if(this.formType !== "apply"){
                $('button[type="submit"]').attr("disabled", true);
            } else {
                $('button[type="submit"]').removeAttr("disabled");
            }
        },
        hasError(fieldName){
            return (fieldName in this.errors);
        },
        clearError(event){
            if(event.target.name !== "plz" && event.target.name !== "organisation_plz"){
                Vue.delete(this.errors, event.target.name);
            }
        }
    },
    computed:{
        hasAnyErrors(){
            return Object.keys(this.errors).length > 0;
        }
    },
    mounted: function () {
        if(document.getElementById("organisation_uid")) {
            $('#organisation_uid').keydown(function() {
                if(this.value.length === 3 || this.value.length === 7){
                    this.value = this.value + ".";
                }
            });
        }

        if(document.getElementById("ahv_nr")) {
            $('#ahv_nr').keydown(function() {
                if(this.value.length === 4 || this.value.length === 9){
                    this.value = this.value + ".";
                }
            });
        }

        if(document.getElementById("editSkillsContainer")){
            // aktive Skills herauslesen und vueJS mitgeben
            const creator_id = document.querySelector("meta[name='creator-id']").getAttribute('content');
            axios.post('/getActiveSkills', {"type": "creator", "id": creator_id})
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
    },
    beforeMount(){
        if (this.formType == "edit") {
            this.getPlace('plz');
        }
    },
}


</script>

<style scoped>

</style>

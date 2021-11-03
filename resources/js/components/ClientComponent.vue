<template>

</template>

<script>
export default {
    name: "ClientComponent",
    data(){
        return{
            deleted: false,
            formType: $('#formType').val(),
            placeFound: false,
            success: false,
            contactAdmin: false,
            error: false,
            errors: {},
            formData: {
                name: $('#name').val(),
                description: $('#description').val(),
                street: $('#street').val(),
                plz: $('#plz').val(),
                email: $('#email').val(),
                website: $('#website').val(),
                telephone: $('#telephone').val(),
            }
        }
    },
    methods:{
        createClient(){
            axios.post('/ycity/client', this.formData)
                .then((res) => {
                   this.onSuccess(res.data.message);
                    $(".hideOnSubmit").fadeOut(500).promise().done(function(){
                        $(".hideOnSubmit").remove();
                        $("#clientSuccess").fadeIn(500);
                    });
                })
                .catch((error) => {
                    if(error.response.status == 422 || error.response.status == 405){
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })

        }, updateClient(id){
            axios.put('/ycity/client/'+id, this.formData)
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

        }, deleteClient(id){
            axios.delete('/ycity/client/'+id)
                .then((res) => {
                    this.deleted = true;
                })
                .catch((error) => {
                    this.deleted = true;
                })
        }, getPlace() {
            axios.post('/getPlaceFromDB', { key: this.formData.plz })
                .then((place) => {
                    if(place.data == ""){
                        this.placeFound = false;
                        this.errors.plz = {0: "Ort nicht gefunden"};
                        this.error = true;
                        $('button[type="submit"]').attr("disabled", true);
                    } else {
                        this.placeFound = true;
                        Vue.delete(this.errors, "plz");
                        $('button[type="submit"]').removeAttr("disabled");
                    }
                    $("#plz_ort").val(place.data);
                })
                .catch((error) => {
                    console.log(error.response.data.errors);
                })

            this.hasError("plz");
        },hireApplicant(job_id, creator_id) {
            axios.post('/hireApplicant', { "job_id": job_id,"creator_id": creator_id })
                .then((res) => {
                    this.onSuccess();
                })
                .catch((error) => {
                    this.onFailure();
                })
        },sendRequestToCreator(creator_id, job_id) {
            axios.post('/sendRequestToCreator', { "job_id": job_id,"creator_id": creator_id })
                .then((res) => {
                    $("#sendRequestToCreatorButton").remove();
                    this.onSuccess();
                })
                .catch((error) => {
                    this.onFailure();
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
            if(!this.placeFound){
                this.errors.plz = {0: "Ort nicht gefunden"};
                $('button[type="submit"]').attr("disabled", true);
            } else {
                $('button[type="submit"]').removeAttr("disabled");
            }
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
    beforeMount(){
        if (this.formType == "edit") {
            this.getPlace();
        }
    },
}


</script>

<style scoped>

</style>

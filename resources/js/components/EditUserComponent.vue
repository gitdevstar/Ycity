<template>

</template>

<script>

export default {
    name: "EditUserComponent",
    data(){
        return{
            success: false,
            error: false,
            errors: {},
            formData: {
                firstname: $('#firstname').val(),
                lastname: $('#lastname').val(),
                email: $('#email').val(),
                password: null,
                image: null,
            }
        }
    },
    methods:{
        editUser(){

            this.formData.image = sessionStorage.getItem("profileUpload");
            console.log(this.formData.image);
            axios.patch('/ycity/user/update', this.formData)
                .then((res) => {
                   this.onSuccess(this.formData.firstname, res.data);
                })
                .catch((error) => {
                    if(error.response.status == 422){
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })
        },
        onSuccess(firstname, imageSrc){
            this.success = true;
            $("#previewPicture").fadeOut();
            $('#loggedUser').html(firstname);
            if(imageSrc !== ""){
                $("#profilePicture").attr("src", imageSrc);
                $("#dropdown-profilePicture").attr("src", imageSrc);
                $(".filepond--file-action-button.filepond--action-revert-item-processing").remove();
            }
        },

        onFailure(message){
            this.error = true;
        },
        setErrors(errors) {
            this.errors = errors;
        },
        hasError(fieldName){
            return (fieldName in this.errors);
        },
        clearError(event){
            Vue.delete(this.errors, event.target.name);
        }
    },
    computed:{
        hasAnyErrors(){
            return Object.keys(this.errors).length > 0;
        }
    }
}


</script>

<style scoped>

</style>

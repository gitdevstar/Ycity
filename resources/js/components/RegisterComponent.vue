<template>

</template>

<script>
export default {
    name: "RegisterComponent",
    data(){
        return{
            error: false,
            errors: {},
            formData: {
                firstname: null,
                lastname: null,
                email: null,
                password: null,
                image: null
            }
        }
    },
    methods:{
        storeUser(){
            axios.post('/register', this.formData)
                .then((res) => {
                   this.onSuccess(res.data.message);
                })
                .catch((error) => {
                    if(error.response.status == 422){
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })
        },

        checkPwConfError(){
            Vue.delete(this.errors, "password");
        },

        onSuccess(message){
            window.location.href = '/ycity';
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

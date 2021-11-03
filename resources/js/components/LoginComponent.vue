<template>

</template>

<script>
export default {
    name: "LoginComponent",
    data(){
        return{
            error: false,
            errors: {},
            formData: {
                firstname: null,
                password: null,
            }
        }
    },
    methods:{
        checkUser(){
            const headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            axios.post('/login', this.formData, {
                headers: headers
            })
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

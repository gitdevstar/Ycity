<template>
</template>


<script>
export default {
    name: "UpdateActiveClientComponent",
    data(){
        return{
            error: false,
            errors: {},
        }
    },
    methods:{
        updateActiveClient(id){
            axios.post('/updateActiveClient', { key: id })
                .then((res) => {
                    window.location.reload(false);
                })
                .catch((error) => {
                    console.log(error.response.data.errors);
                    if(error.response.status == 422){
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.onFailure(error.response.data.message);
                    }
                })
        },

        onSuccess(message){
        },

        onFailure(message){
            this.error = true;
        },
        setErrors(errors) {
            this.errors = errors;
        },
    },
    computed:{

    }
}


</script>

<style scoped>

</style>

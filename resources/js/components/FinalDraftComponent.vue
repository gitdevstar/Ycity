<template>
    <final-draft
        post-action="/finalDraftUpload"
        :headers="{'X-CSRF-TOKEN': this.token}"
        :data="{'job': this.job}"
        ref="upload"
        input-id="finalDraft"
        v-model="files"
        @input-file="checkFile"
        accept="application/octet-stream">
    </final-draft>
</template>

<script>
export default {
    name: 'FinalDraftComponent',
    props: ['job'],
    data() {
        return {
            files: [],
            token: $('meta[name="csrf-token"]').attr('content')
        }
    },
    methods: {
        checkFile(newFile, oldFile) {
            // Automatic upload
            if (newFile && !oldFile) {
                if (!/\.(zip|7zip|rar)$/i.test(newFile.name)) {
                    $("#uploadFinalSuccess").hide();
                    $("#uploadFinalFail").fadeIn();

                } else {
                    $("#uploadFinalFail").hide();

                    $("#finalDraftContainer").fadeOut().promise().done(function(){
                        $("#finalDraftUploaded").fadeIn();
                    });
                    this.$refs.upload.active = true
                }
            }
        },
    }
};
</script>

<style scoped>

</style>

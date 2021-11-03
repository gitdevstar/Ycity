<template>
    <div class="input-group">
        <span id="uploadFeedback" role="alert" class="invalid-feedback text-right py-2"><strong>Es können nur Dokumente und Bilder gesendet werden.</strong></span>
        <textarea id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Nachricht eingeben..." v-model="newMessage" @keydown.ctrl.enter="sendMessage()" ></textarea>
        <div class="input-group-append">
            <div>
                <label id="btn-upload" for="file" class="float-left">
                    <img class="mt-sm-3" src="/images/icons/paperclip.png">
                </label>
                <chat-upload
                    ref='upload'
                    @input-file="checkFile"
                    post-action="/chatUpload"
                    :headers="{'X-CSRF-TOKEN': this.token}"
                    :data="{'job': this.job, 'id': this.user_id, 'type': 'file'}"
                    accept="application/pdf, images/*">
                </chat-upload>
                <button id="btn-chat" @click="sendMessage"><img src="/images/icons/send.png"></button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user_id', 'job', 'firstname', 'image', 'time'],
    data() {
        return {
            newMessage: '',
            token: $('meta[name="csrf-token"]').attr('content')
        }
    },
    methods: {
        checkFile(newFile, oldFile) {
            // Automatic upload
            if (newFile && !oldFile) {
                if (!/\.(pdf|docx|jpeg|jpe|jpg|gif|png|webp)$/i.test(newFile.name)) {
                    $("#uploadFeedback").fadeIn();
                } else {
                    if (!this.$refs.upload.active) {
                        $("#uploadFeedback").fadeOut();
                        this.$refs.upload.active = true;
                        this.$emit('messagesent', {
                            job: this.job,
                            id: this.user_id,
                            firstname: this.firstname,
                            image: this.image,
                            file: newFile.name,
                            created_at: this.time,
                            type: "file",
                            onlineUsers: sessionStorage.getItem("onlineUsers"),
                        });
                    }
                }
            }
        },
        sendMessage() {
            if(this.newMessage !== ''){
                console.log("test");
                this.$emit('messagesent', {
                    job: this.job,
                    id: this.user_id,
                    firstname: this.firstname,
                    image: this.image,
                    message: this.newMessage,
                    created_at: this.time,
                    type: "message",
                    onlineUsers: sessionStorage.getItem("onlineUsers"),
                });

                this.newMessage = '';
            } else {
                //console.log("leer");
            }
        }
    }
}
</script>

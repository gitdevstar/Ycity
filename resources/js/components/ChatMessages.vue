<template>
    <div id="chatContainer" class="container-fluid mb-3 pl-0 mt-0">
        <div v-if="messages.length === 0">
            <p id="noMessage">Beginne die Unterhaltung mit einer Nachricht!</p>
        </div>
        <div v-for="message in messages" v-else class="message">
            <div v-if="message.id !== user" class="row m-0">
                <div class="col-2 col-sm-1 col-md-1 col-lg-1 p-0">
                    <div class="profileImage">
                        <div v-if="message.type == 'message'">
                            <img v-if="message.image == 'avatar.png'" :src="'/images/profiles/' + message.image"/>
                            <img v-else :src="'/images/profiles/' + message.id + '/' + message.image"/>
                        </div>
                        <div v-else>
                            <img src="/images/icons/activity/file.svg">
                        </div>
                    </div>
                </div>
                <div class="col-8 col-sm-9 col-md-9 col-lg-9 p-0">
                    <div class="w-100 d-block"><h4 class="d-inline-block pr-2">{{ message.firstname }}</h4> <small style="color: #A9A9A9;">{{ message.created_at }}</small></div>
                    <p v-if="message.type == 'message'" >{{ message.message }}</p>
                    <div v-else>
                        <div v-if="message.file.indexOf('pdf') !== -1" class="attached_file showPdf" :data-src="'/uploads/chat/'+ message.job +'/'+ message.file">
                            <img src="/images/icons/pdf-file.png">
                            <span class="text-center d-block">{{ message.file }}</span>
                        </div>
                        <div v-else-if="message.file.indexOf('png') !== -1 || message.file.indexOf('jpg') !== -1 || message.file.indexOf('jpeg') !== -1" class="attached_file showImage" :data-src="'/uploads/chat/'+ message.job +'/'+ message.file">
                            <img src="/images/icons/image-file.png">
                            <span class="text-center d-block">{{ message.file }}</span>
                        </div>
                        <div v-else class="attached_file showFile">
                            <a :href="'/uploads/chat/'+message.job+'/'+message.file" :download="message.file">
                                <img src="/images/icons/file.png">
                                <span class="text-center d-block">{{ message.file }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="row m-0">
                <div class="col-8 col-sm-9 col-md-9 col-lg-9 p-0 text-right">
                    <div class="w-100 d-block"><h4 class="d-inline-block pr-2">Du</h4> <small style="color: #A9A9A9;">{{ message.created_at }}</small></div>
                    <p v-if="message.type == 'message'">{{ message.message }}</p>
                    <div v-else>
                        <div v-if="message.file.indexOf('pdf') !== -1" class="attached_file showPdf float-right" :data-src="'/uploads/chat/'+ message.job +'/'+ message.file">
                            <img src="/images/icons/pdf-file.png">
                            <span class="text-center d-block">{{ message.file }}</span>
                        </div>
                        <div v-else-if="message.file.indexOf('png') !== -1 || message.file.indexOf('jpg') !== -1 || message.file.indexOf('jpeg') !== -1" class="attached_file showImage float-right" :data-src="'/uploads/chat/'+ message.job +'/'+ message.file">
                            <img src="/images/icons/image-file.png">
                            <span class="text-center d-block">{{ message.file }}</span>
                        </div>
                        <div v-else class="attached_file showFile float-right" :data-src="'/'+ message.job +'/'+ message.file">
                            <a :href="'/uploads/chat/'+message.job+'/'+message.file" :download="message.file">
                                <img src="/images/icons/file.png">
                                <span class="text-center d-block">{{ message.file }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-2 col-sm-1 col-md-1 col-lg-1 p-0">
                    <div class="profileImage">
                        <div v-if="message.type == 'message'">
                            <img v-if="message.image !== 'avatar.png'" :src="'/images/profiles/' + message.id + '/' + message.image" class="float-right"/>
                            <img v-if="message.image == 'avatar.png'" :src="'/images/profiles/' + message.image" class="float-right"/>
                        </div>
                        <div v-else>
                            <img src="/images/icons/activity/file.svg" class="float-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['messages', 'user']
};
</script>

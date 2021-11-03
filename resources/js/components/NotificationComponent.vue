<template>

</template>

<script>
import $ from "jquery";

export default {
    name: "NotificationComponent",
    mounted() {

        var userId = $('meta[name="userId"]').attr('content');

        /* Array mit allen Online Users */
        var onlineUsers = [];
        Echo.join("Online")
        .here((users) => {
            $( users ).each(function( index ) {
                onlineUsers.push(users[index]["id"]);
            });
            sessionStorage.setItem("onlineUsers", onlineUsers);
        })
        .joining((user) => {
            onlineUsers.push(user["id"]);
            sessionStorage.setItem("onlineUsers", onlineUsers);
        })
        .leaving((user) => {
            onlineUsers.pop(user["id"]);
            sessionStorage.setItem("onlineUsers", onlineUsers);
        });

        Echo.private('App.User.'+userId)
        .listen('NewNotification', (e) => {
            console.log(e.notification)

            // wenn eine neue Chat Aktivität einkommt und der Chat offen ist, nicht benachrichtigen
            if(e.notification.type == "job" || (!$("#chat").length && e.notification.type == "message") || (!$("#chat").length && e.notification.type == "file")){

                // Notifications
                var messages = $("#messagesBubble").html();
                var newMessages = parseInt(messages) + 1;

                // Tab Title anpassen
                var title = $("meta[name='title']").attr("content");
                if(newMessages > 1){
                    document.title = "("+newMessages+") Nachrichten - "+title;
                } else {
                    document.title = "("+newMessages+") Nachricht - "+title;
                }

                // Anzahl der Nachrichten ausblenden - updaten und anzeigen
                $("#messagesBubble").fadeOut().html(newMessages).fadeIn();
                $('#popupActivities').fadeOut();

                // PopUp Aktivität hinzufügen
                if($("#no_popup_messages").length){
                    $('#popupActivities').html('<table>' +
                        '<tbody>' +
                        '<tr>' +
                        '<td class="align-top"><a href="'+e.notification.link+'"><img src="/images/icons/activity/'+e.notification.type+'.svg" /></a></td>' +
                        '<td class="pl-2 align-middle"><a href="'+e.notification.link+'"><h4 class="mb-0">'+e.notification.title+'</h4></a></td>' +
                        '<td class="align-middle"><div class="unread"></div></td>' +
                        '</tr>' +
                        '</tbody>' +
                        '</table>').fadeIn();
                } else {
                    $('#popupActivities table tr:first').before('<tr>' +
                        '<td class="align-top"><a href="'+e.notification.link+'"><img src="/images/icons/activity/'+e.notification.type+'.svg" /></a></td>' +
                        '<td class="pl-2 align-middle"><a href="'+e.notification.link+'"><h4 class="mb-0">'+e.notification.title+'</h4></a></td>' +
                        '<td class="align-middle"><div class="unread"></div></td>' +
                        '</tr>');
                }
                $('#popupActivities').fadeIn();

                // Dashboard Aktivität hinzufügen
                if($("#no_dashboard_activities").length){
                    $("#dashboardActivities").fadeOut().html('<div class="row row-flex m-0">\n' +
                        '  <div class="col col-2 col-md-1 pl-0 pr-0 pt-0 w-100 overflow-hidden">\n' +
                        '       <img src="/images/icons/activity/'+e.notification.type+'.svg">\n' +
                        '       <div class="d-none activityLine"></div>\n' +
                        '  </div>\n' +
                        '  <div class="col col-8 col-sm-8 col-md-9 p-0 w-100 ">\n' +
                        '       <h3>'+e.notification.title+'</h3>\n' +
                        '       <p><a href="'+e.notification.link+'">'+e.notification.message+'</a></p>\n' +
                        '  </div>\n' +
                        '</div>').fadeIn();
                } else {
                    $("#dashboardActivities").fadeOut().prepend('<div class="row row-flex m-0">\n' +
                        '  <div class="col col-2 col-md-1 pl-0 pr-0 pt-0 w-100 overflow-hidden">\n' +
                        '       <img src="/images/icons/activity/'+e.notification.type+'.svg">\n' +
                        '       <div class="activityLine"></div>\n' +
                        '  </div>\n' +
                        '  <div class="col col-8 col-sm-8 col-md-9 p-0 w-100 ">\n' +
                        '       <h3>'+e.notification.title+'</h3>\n' +
                        '       <p><a href="'+e.notification.link+'">'+e.notification.message+'</a></p>\n' +
                        '  </div>\n' +
                        '</div>').fadeIn();
                }
            }

            // Neuer Chat Nachricht anzeigen, wenn der Chat offen ist
            if($("meta[name='job-id']").attr("content") == e.notification.job && (e.notification.type == "message" || e.notification.type == "file")) {
                $("#noMessage").remove();
                var imgSrc;

                if(e.notification.image == "avatar.png"){
                    imgSrc = '/images/profiles/'+ e.notification.image;
                } else {
                    imgSrc = '/images/profiles/'+ e.notification.uId +'/'+ e.notification.image;
                }

                // Nachricht jenachdem links oder rechts ausrichten
                if(e.notification.user_id == e.notification.uId){

                    if(e.notification.type == "file"){
                        var attached_file;
                        if(e.notification.file.indexOf('pdf') !== -1){
                            attached_file =  '      <div class="attached_file showPdf float-right" data-src="/uploads/chat/'+ e.notification.job +'/'+ e.notification.file +'">' +
                                '                       <img src="/images/icons/pdf-file.png">' +
                                '                       <span class="text-center d-block">'+ e.notification.file +'</span>' +
                                '                   </div>\n';
;
                        } else if(message.file.indexOf('png') !== -1 || message.file.indexOf('jpg') !== -1 || message.file.indexOf('jpeg') !== -1){
                            attached_file =  '      <div class="attached_file showImage float-right" data-src="/uploads/chat/'+ e.notification.job +'/'+ e.notification.file +'">' +
                                '                       <img src="/images/icons/image-file.png">' +
                                '                       <span class="text-center d-block">'+ e.notification.file +'</span>' +
                                '                   </div>\n';
                        } else {
                            attached_file =  '      <div class="attached_file showImage float-right" data-src="/uploads/chat/'+ e.notification.job +'/'+ e.notification.file +'">' +
                                '                       <a href="/uploads/chat/'+e.notification.job +'/'+ e.notification.file +' download="'+e.notification.file+'"><img src="/images/icons/image-file.png">' +
                                '                       <span class="text-center d-block">'+ e.notification.file +'</span>' +
                                '                   </a></div>\n';
                        }

                        $("#chatContainer").append('<div class="message"><div class="row m-0">\n' +
                            '                <div class="col-8 col-sm-9 col-md-9 col-lg-9 p-0 text-right">\n' +
                            '                    <div class="w-100 d-block"><h4 class="d-inline-block pr-2">Du</h4> <small style="color: #A9A9A9;">' + e.notification.time + '</small></div>\n' +
                            attached_file +
                            '                </div>\n' +
                            '                <div class="col-2 col-sm-1 col-md-1 col-lg-1 p-0">\n' +
                            '                    <div class="profileImage">\n' +
                            '                        <img class="float-right" src="/images/icons/activity/file.svg" />\n' +
                            '                    </div>\n' +
                            '                </div>\n' +
                            '            </div></div>');
                    } else {
                        $("#chatContainer").append('<div class="message"><div class="row m-0">\n' +
                            '                <div class="col-8 col-sm-9 col-md-9 col-lg-9 p-0 text-right">\n' +
                            '                    <div class="w-100 d-block"><h4 class="d-inline-block pr-2">Du</h4> <small style="color: #A9A9A9;">' + e.notification.time + '</small></div>\n' +
                            '                    <p>' + e.notification.chatMsg + '</p>\n' +
                            '                </div>\n' +
                            '                <div class="col-2 col-sm-1 col-md-1 col-lg-1 p-0">\n' +
                            '                    <div class="profileImage">\n' +
                            '                        <img class="float-right" src="' + imgSrc + '" />\n' +
                            '                    </div>\n' +
                            '                </div>\n' +
                            '            </div></div>');
                    }
                } else {
                    if(e.notification.type == "file"){
                        var attached_file;
                        if(e.notification.file.indexOf('pdf') !== -1){
                            attached_file =  '      <div class="attached_file showPdf" data-src="/uploads/chat/'+ e.notification.job +'/'+ e.notification.file +'">' +
                                '                       <img src="/images/icons/pdf-file.png">' +
                                '                       <span class="text-center d-block">'+ e.notification.file +'</span>' +
                                '                   </div>\n';
                            ;
                        } else if(e.notification.file.indexOf('png') !== -1 || e.notification.file.indexOf('jpg') !== -1 || e.notification.file.indexOf('jpeg') !== -1){
                            attached_file =  '      <div class="attached_file showImage" data-src="/uploads/chat/'+ e.notification.job +'/'+ e.notification.file +'">' +
                                '                       <img src="/images/icons/image-file.png">' +
                                '                       <span class="text-center d-block">'+ e.notification.file +'</span>' +
                                '                   </div>\n';
                        } else {
                            attached_file =  '      <div class="attached_file showImage" data-src="/uploads/chat/'+ e.notification.job +'/'+ e.notification.file +'">' +
                                '                       <a href="/uploads/chat/'+e.notification.job +'/'+ e.notification.file +' download="'+e.notification.file+'"><img src="/images/icons/image-file.png">' +
                                '                       <span class="text-center d-block">'+ e.notification.file +'</span>' +
                                '                   </a></div>\n';
                        }

                        $("#chatContainer").append('<div class="message"><div class="row m-0">\n' +
                            '                <div class="col-2 col-sm-1 col-md-1 col-lg-1 p-0">\n' +
                            '                    <div class="profileImage">\n' +
                            '                        <img src="/images/icons/activity/file.svg" />\n' +
                            '                    </div>\n' +
                            '                </div>\n' +
                            '                <div class="col-8 col-sm-9 col-md-9 col-lg-9 p-0 text-left">\n' +
                            '                    <div class="w-100 d-block"><h4 class="d-inline-block pr-2">'+ e.notification.firstname +'</h4> <small style="color: #A9A9A9;">'+ e.notification.time +'</small></div>\n' +
                            attached_file+
                            '                </div>\n' +
                            '            </div></div>');
                    } else {
                        $("#chatContainer").append('<div class="message"><div class="row m-0">\n' +
                            '                <div class="col-2 col-sm-1 col-md-1 col-lg-1 p-0">\n' +
                            '                    <div class="profileImage">\n' +
                            '                        <img src="'+imgSrc+'" />\n' +
                            '                    </div>\n' +
                            '                </div>\n' +
                            '                <div class="col-8 col-sm-9 col-md-9 col-lg-9 p-0 text-left">\n' +
                            '                    <div class="w-100 d-block"><h4 class="d-inline-block pr-2">'+ e.notification.firstname +'</h4> <small style="color: #A9A9A9;">'+ e.notification.time +'</small></div>\n' +
                            '                    <p>'+ e.notification.chatMsg +'</p>\n' +
                            '                </div>\n' +
                            '            </div></div>');
                    }

                }

                $("#chatContainer").animate({ scrollTop: $('#chatContainer').prop("scrollHeight")}, 750);

            } else if($("meta[name='job-id']").attr("content") !== e.notification.job){
                // Activity Table Eintrag nur dann, wenn der Chat nicht offen ist (ansonsten unnötig)
                axios.post('/insertActivity', e.notification)
                .then((res) => {
                    //console.log("Success");
                })
                .catch((error) => {
                    console.log("error");
                })
            } else {
                console.log(!$("#chat").length);
                console.log(e.notification.type);
            }
        });
    }
}
</script>

<style scoped>

</style>









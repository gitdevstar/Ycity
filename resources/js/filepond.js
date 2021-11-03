// Import FilePond
import * as FilePond from 'filepond';

// Import the plugin code
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
import FilePondPluginImageEdit from 'filepond-plugin-image-edit';
import FilePondPluginFileEncode from 'filepond-plugin-file-encode';

// Import the plugin styles
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import 'filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css';


// Import the plugin styles

$(document).ready(function (){
var dataUrl;
// Register the plugin
    FilePond.registerPlugin(
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginImageCrop,
        FilePondPluginImageResize,
        FilePondPluginImageTransform,
        FilePondPluginImageEdit,
        FilePondPluginFileEncode
    );

    // Portfolio
    const portfolioPond = FilePond.create( document.querySelector('#portfolioFiles'));
    sessionStorage.setItem("portfolioUpload", 0);

    portfolioPond.setOptions({
        labelIdle: 'Hineinziehen oder <span class="filepond--label-action">durchsuchen</span>',
        maxFiles: 6,
        required: false,
        allowMultiple: true,
        allowFileEncode:true,
        allowFileTypeValidation: true,
        allowProcess:false,
        maxFileSize:"100MB",
        labelMaxFileSizeExceeded: "Die Datei ist zu gross.",
        labelMaxTotalFileSize: 'maximal erlaubte Dateigrösse: {filesize}',
        acceptedFileTypes:["image/*"],
    });


    // wenn eine Datei dem Upload hinzugefügt wird
    portfolioPond.onaddfile = (err, item) => {
        if (err) {
            return;
        } else {
            var dataUrl = item.getFileEncodeDataURL();
            var filename = item.filename;
            $.ajax({
                url : '/addTempPortfolioUpload',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type : 'POST',
                data : { 'file': dataUrl, 'filename': filename },
                success: function (res) {/*console.log(res);*/ },
                failure: function(res) {/*console.log(res);*/}
            });

            sessionStorage.setItem("portfolioUpload", 1);
        }
    }

    // wenn eine Datei vom Upload entfernt wird
    portfolioPond.onremovefile= (err, item) => {
        var files = portfolioPond.getFiles();
        if (err) {
            return;
        } else {
            var filename = item.filename;
            $.ajax({
                url : '/deleteTempPortfolioUpload',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type : 'POST',
                data : {'filename': filename },
                success: function (res) {/*console.log(res);*/ },
                failure: function(res) {/*console.log(res);*/}
            });

            if(files == 0){
                sessionStorage.setItem("portfolioUpload", 0);
            }
        }
    }

    // Job Anhang
    const jobAttachmentPond = FilePond.create( document.querySelector('#jobFiles'));
    jobAttachmentPond.setOptions({
        labelIdle: 'Hineinziehen oder <span class="filepond--label-action">durchsuchen</span>',
        maxFiles: 6,
        required: false,
        allowMultiple: true,
        allowFileEncode:true,
        allowFileTypeValidation: true,
        allowProcess:true,
        maxFileSize:"100MB",
        labelMaxFileSizeExceeded: "Die Datei ist zu gross.",
        labelMaxTotalFileSize: 'maximal erlaubte Dateigrösse: {filesize}',
        acceptedFileTypes:["application/docx","application/pdf","application/doc", "image/*"],
    });

    sessionStorage.setItem("jobAttachmentUpload", 0);

    // wenn eine Datei dem Upload hinzugefügt wird
    jobAttachmentPond.onaddfile = (err, item) => {
        if (err) {
            return;
        } else {
            var dataUrl = item.getFileEncodeDataURL();
            var filename = item.filename;
            $.ajax({
                url : '/storeTempJobAttachments',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type : 'POST',
                data : { 'file': dataUrl, 'filename': filename },
                success: function (res) {/*console.log(res);*/ },
                failure: function(res) {/*console.log(res);*/}
            });

            sessionStorage.setItem("jobAttachmentUpload", 1);
        }
    }

    // wenn eine Datei vom Upload entfernt wird
    jobAttachmentPond.onremovefile= (err, item) => {
        var files = jobAttachmentPond.getFiles();
        if (err) {
            return;
        } else {
            var filename = item.filename;
            $.ajax({
                url : '/deleteTempJobAttachments',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type : 'POST',
                data : {'filename': filename },
                success: function (res) {/*console.log(res);*/ },
                failure: function(res) {/*console.log(res);*/}
            });

            if(files == 0){
                sessionStorage.setItem("jobAttachmentUpload", 0);
            }
        }
    }

    // Profilbild
    sessionStorage.setItem("profileUpload", 0);
    const profileImagePond = FilePond.create( document.querySelector('#image'));
    profileImagePond.setOptions({
        labelIdle: 'Hineinziehen oder <span class="filepond--label-action">durchsuchen</span>',
        allowImageCrop:true,
        allowMultiple:false,
        imagePreviewHeight: 50,
        imageCropAspectRatio: '1:1',
        imageResizeTargetWidth: 200,
        imageResizeTargetHeight: 200,
        imageResizeMode:'cover',
        stylePanelLayout: 'compact circle',
        styleLoadIndicatorPosition: 'center bottom',
        styleProgressIndicatorPosition: 'right bottom',
        styleButtonRemoveItemPosition: 'right top',
        styleButtonProcessItemPosition: 'right bottom',
        allowImageEdit:true,
        styleImageEditButtonEditItemPosition:'	bottom center',
        allowFileEncode:true,
        server: {
            url: '/profileImage',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    // Request encoded data
    profileImagePond.onaddfile = (err, item) => {

        if (err) {
            $("button[type='submit']").attr("disabled", true);
            //console.warn(err);
            $("#image-feedback").fadeIn();
            return;
        } else {
            $("button[type='submit']").removeAttr("disabled");
            $("#image-feedback").fadeOut();
            dataUrl = item.getFileEncodeDataURL();
            sessionStorage.setItem("profileUpload", 1);
        }

    }

    // Request encoded data
    profileImagePond.onremovefile= (err, item) => {
        if (err) {
            //console.warn(err);
            $("button[type='submit']").attr("disabled", true);
            return;
        } else {
            $("button[type='submit']").removeAttr("disabled");
            $("#image-feedback").fadeOut();
            sessionStorage.setItem("profileUpload", 0);
        }

    }

    // SVA
    const svaPond = FilePond.create( document.querySelector('#sva'));
    svaPond.setOptions({
        labelIdle: 'Hineinziehen oder <span class="filepond--label-action">durchsuchen</span>',
        allowMultiple:false,
        allowFileEncode:true,
        required: true,
        allowFileTypeValidation: true,
        maxFileSize:"100MB",
        labelMaxFileSizeExceeded: "Die Datei ist zu gross.",
        labelMaxTotalFileSize: 'maximal erlaubte Dateigrösse: {filesize}',
        acceptedFileTypes:["application/pdf"],
        server: {
            url: '/sva',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    // Request encoded data
    svaPond.onaddfile = (err, item) => {

        if (err) {
            $("button[type='submit']").attr("disabled", true);
            //console.warn(err);
            $("#sva-feedback").fadeIn();
            return;
        } else {
            $("button[type='submit']").removeAttr("disabled");
            $("#sva-feedback").fadeOut();
            dataUrl = item.getFileEncodeDataURL();
            $("#svaBase64String").val(dataUrl);
        }
    }

    // Request encoded data
    svaPond.onremovefile= (err, item) => {
        if (err) {
            //console.warn(err);
            $("button[type='submit']").attr("disabled", true);
            return;
        } else {
            $("button[type='submit']").removeAttr("disabled");
            $("#sva-feedback").fadeOut();
            $("#svaBase64String").val("");
        }
    }

})

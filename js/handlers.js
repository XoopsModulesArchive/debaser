function swfUploadPreLoad() {
var self = this;
var loading = function () {
document.getElementById("divLoadingContent").style.display = "";
var longLoad = function () {
document.getElementById("divLoadingContent").style.display = "none";
document.getElementById("divLongLoading").style.display = "";
};
this.customSettings.loadingTimeout = setTimeout(function () {
longLoad.call(self)
},
15 * 1000
);
};
this.customSettings.loadingTimeout = setTimeout(function () {
loading.call(self);
},
1*1000
);
}
function swfUploadLoaded() {
var self = this;
clearTimeout(this.customSettings.loadingTimeout);
document.getElementById("divLoadingContent").style.display = "none";
document.getElementById("divLongLoading").style.display = "none";
document.getElementById("divAlternateContent").style.display = "none";
document.getElementById("btnCancel").onclick = function () { self.cancelQueue(); };
}
function swfUploadLoadFailed() {
clearTimeout(this.customSettings.loadingTimeout);
document.getElementById("divLoadingContent").style.display = "none";
document.getElementById("divLongLoading").style.display = "none";
document.getElementById("divAlternateContent").style.display = "";
}
function fileQueued(file) {
try {
var progress = new FileProgress(file, this.customSettings.progressTarget);
progress.setStatus("Pending...");
progress.toggleCancel(true, this);
} catch (ex) {
this.debug(ex);
}
}
function fileQueueError(file, errorCode, message) {
try {
if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
return;
}
var progress = new FileProgress(file, this.customSettings.progressTarget);
progress.setError();
progress.toggleCancel(false);
switch (errorCode) {
case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
progress.setStatus("File is too big.");
this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
break;
case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
progress.setStatus("Cannot upload Zero Byte files.");
this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
break;
case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
progress.setStatus("Invalid File Type.");
this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
break;
default:
if (file !== null) {
progress.setStatus("Unhandled Error");
}
this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
break;
}
} catch (ex) {
this.debug(ex);
}
}
function fileDialogComplete(numFilesSelected, numFilesQueued) {
try {
if (numFilesSelected > 0) {
document.getElementById(this.customSettings.cancelButtonId).disabled = false;
}
this.startUpload();
} catch (ex)  {
this.debug(ex);
}
}
function uploadStart(file) {
try {
var progress = new FileProgress(file, this.customSettings.progressTarget);
progress.setStatus("Uploading...");
progress.toggleCancel(true, this);
}
catch (ex) {}
// frankblack says: adapt this to your needs
this.addFileParam(file.id, 'genrefrom', document.getElementById("genrefrom").options[document.getElementById("genrefrom").selectedIndex].value); 
this.addFileParam(file.id, 'artist', document.getElementById("artist").value);
this.addFileParam(file.id, 'title', document.getElementById("title").value);
this.addFileParam(file.id, 'album', document.getElementById("album").value);
this.addFileParam(file.id, 'year', document.getElementById("year").value);
if (this.customSettings.editortype == 'ckeditor' || this.customSettings.editortype == 'tinymce') {
if (this.customSettings.editortype == 'ckeditor') {
//if multilang feature is used you have to add some lines
//this.addFileParam(file.id, 'english_description', CKEDITOR.instances.english_description.getData());
//this.addFileParam(file.id, 'german_description', CKEDITOR.instances.english_description.getData());
this.addFileParam(file.id, 'description', CKEDITOR.instances.description.getData());
}
if (this.customSettings.editortype == 'tinymce') {
//if multilang feature is used you have to add some lines
//this.addFileParam(file.id, 'english_description', tinyMCE.get('english_description').getContent());
//this.addFileParam(file.id, 'german_description', tinyMCE.get('german_description').getContent());
this.addFileParam(file.id, 'description', tinyMCE.get('description').getContent());	
}
} else {
//if multilang feature is used you have to add some lines
//this.addFileParam(file.id, 'english_description', document.getElementById("english_description").value);
//this.addFileParam(file.id, 'german_description', document.getElementById("german_description").value);
this.addFileParam(file.id, 'description', document.getElementById("description").value);	
}
this.addFileParam(file.id, 'track', document.getElementById("track").value);
this.addFileParam(file.id, 'length', document.getElementById("length").value);
this.addFileParam(file.id, 'bitrate', document.getElementById("bitrate").value);
this.addFileParam(file.id, 'frequence', document.getElementById("frequence").value);
return true;
}
function uploadProgress(file, bytesLoaded, bytesTotal) {
try {
var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
var MBLoaded = Math.round((bytesLoaded / 1024 / 1024) * 100) / 100 ;
var MBTotal = Math.round((bytesTotal / 1024 / 1024) * 100) / 100 ;
var TimeLeft = SWFUpload.speed.formatTime(file.timeRemaining);
var progress = new FileProgress(file, this.customSettings.progressTarget);
progress.setProgress(percent);
progress.setStatus('Uploading... ' + percent + '% (' + MBLoaded + ' of ' + MBTotal + ' MB done) ' + 'Time Remaining: ' +TimeLeft);
document.title = percent + '% uploaded for ' + file.name;
} catch (ex) {
this.debug(ex);
}
}
function uploadSuccess(file, serverData) {
try {
var progress = new FileProgress(file, this.customSettings.progressTarget);
progress.setComplete();
progress.setStatus("Complete.");
progress.toggleCancel(false);
} catch (ex) {
this.debug(ex);
}
}
function uploadError(file, errorCode, message) {
try {
var progress = new FileProgress(file, this.customSettings.progressTarget);
progress.setError();
progress.toggleCancel(false);
switch (errorCode) {
case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
progress.setStatus("Upload Error: " + message);
this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
break;
case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
progress.setStatus("Upload Failed.");
this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
break;
case SWFUpload.UPLOAD_ERROR.IO_ERROR:
progress.setStatus("Server (IO) Error");
this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
break;
case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
progress.setStatus("Security Error");
this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
break;
case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
progress.setStatus("Upload limit exceeded.");
this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
break;
case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
progress.setStatus("Failed Validation.  Upload skipped.");
this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
break;
case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
if (this.getStats().files_queued === 0) {
document.getElementById(this.customSettings.cancelButtonId).disabled = true;
}
progress.setStatus("Cancelled");
progress.setCancelled();
break;
case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
progress.setStatus("Stopped");
break;
default:
progress.setStatus("Unhandled Error: " + errorCode);
this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
break;
}
} catch (ex) {
this.debug(ex);
}
}
function uploadComplete(file) {
if (this.getStats().files_queued === 0) {
document.getElementById(this.customSettings.cancelButtonId).disabled = true;
window.location.href = this.customSettings.debaserreturnurl;
}
}
function queueComplete(numFilesUploaded) {
var status = document.getElementById("divStatus");
status.innerHTML = numFilesUploaded;
}
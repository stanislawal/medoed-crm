/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./resources/js/project/files.js ***!
  \***************************************/
window.ajaxStatus = true;
window.saveFile = function (e, projectId, url) {
  if (ajaxStatus) {
    window.ajaxStatus = false;
    var loadFile = e.target.files[0];
    var formData = new FormData();
    formData.append('file', loadFile);
    formData.append('project_id', projectId);
    console.log(formData);
    $.ajax({
      url: url,
      type: 'POST',
      contentType: false,
      processData: false,
      data: formData,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done(function (response) {
      if (response.result) {
        window.showFileList(response.html);
      }
      window.ajaxStatus = true;
    }).fail(function (error) {
      console.log(error);
      window.ajaxStatus = true;
    });
  }
};
window.deleteFile = function (fileId, url) {};
window.showFileList = function (html) {
  $('.container__files').empty().html(html);
};
/******/ })()
;
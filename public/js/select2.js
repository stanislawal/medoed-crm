/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************!*\
  !*** ./resources/js/select2.js ***!
  \*********************************/
$(document).ready(function () {
  $('.select-2').select2();
  $('.select-2-modal').select2({
    dropdownParent: $('.modal')
  });
  window.formatState = function (state) {
    if (!state.id) {
      return state.text;
    }
    var color = state.element.dataset.color;
    if (color !== '' && color !== undefined) {
      return $("<span class='nowrap select-2-custom-state-color' style='background-color: " + color + "; '>" + state.text + "</span>");
    } else {
      return state.text;
    }
  };
  $('.select2-with-color').select2({
    templateSelection: window.formatState,
    templateResult: window.formatState,
    minimumResultsForSearch: -1
  });
});
/******/ })()
;
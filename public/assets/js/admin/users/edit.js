$(document).ready(function () {
  $(".image-popup-no-margins").magnificPopup({
    type: "image",
    closeOnContentClick: !0,
    closeBtnInside: !1,
    fixedContentPos: !0,
    mainClass: "mfp-no-margins mfp-with-zoom",
    image: { verticalFit: !0 },
    zoom: { enabled: !0, duration: 300 },
  });
});

function previewImage(event) {
  $("#projectlogo-img").attr("src", URL.createObjectURL(event.target.files[0]));
}

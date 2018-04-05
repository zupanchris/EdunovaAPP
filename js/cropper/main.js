
$(function () {
  'use strict';
  var URL = window.URL || window.webkitURL;
  var $image = $('#image');
  var options = {
        preview: '.img-preview',
        aspectRatio: 3 / 4,
        minCropBoxWidth: 300,
        minCropBoxHeight: 400
      };

  var uploadedImageType = 'image/png';
  var uploadedImageURL;
  $image.on({}).cropper(options);

  var $inputImage = $('#inputImage');

  if (URL) {
    $inputImage.change(function () {
      var files = this.files;
      var file;

      if (!$image.data('cropper')) {
        return;
      }

      if (files && files.length) {
        file = files[0];

        if (/^image\/\w+$/.test(file.type)) {
          uploadedImageType = file.type;

          if (uploadedImageURL) {
            URL.revokeObjectURL(uploadedImageURL);
          }

          uploadedImageURL = URL.createObjectURL(file);
          $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
          $inputImage.val('');
        } else {
          window.alert('Odaberite slike!');
        }
      }
    });
  } else {
    $inputImage.prop('disabled', true).parent().addClass('disabled');
  }
});




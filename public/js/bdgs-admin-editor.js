(function () {
  'use strict';

  var editorConfigs = [
    { selector: '#bdgs-content-editor', height: 520 },
    { selector: '#bdgs-email-html-editor', height: 640 },
  ];

  function initEditor(config) {
    var el = document.querySelector(config.selector);
    if (!el || typeof tinymce === 'undefined') return;
    if (tinymce.get(el.id)) return;

    tinymce.init({
      selector: config.selector,
      height: config.height || 520,
      menubar: false,
      plugins: 'lists link image media table code fullscreen autolink',
      toolbar: [
        'fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | link',
        'alignleft aligncenter alignright alignjustify | indent outdent | bullist numlist | table image media | code fullscreen'
      ],
      font_family_formats: 'DM Sans=DM Sans,sans-serif; Arial=arial; Helvetica=helvetica; Georgia=georgia; Times=times new roman; Courier=courier new',
      font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt',
      branding: false,
      promotion: false,
      convert_urls: false,
      relative_urls: false,
      paste_data_images: true,
      content_style: 'body { font-family: "DM Sans", -apple-system, BlinkMacSystemFont, sans-serif; font-size: 14px; line-height: 1.6; color: #2C2C3A; } img { max-width: 100%; height: auto; }',
      placeholder: 'Type Something',
      images_upload_handler: function (blobInfo, progress) {
        return new Promise(function (resolve, reject) {
          var xhr = new XMLHttpRequest();
          var formData = new FormData();
          formData.append('file', blobInfo.blob(), blobInfo.filename());
          formData.append('editor_upload', '1');

          xhr.open('POST', '/admin/media');
          xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
          xhr.setRequestHeader('Accept', 'application/json');

          var token = document.querySelector('meta[name="csrf-token"]');
          if (token) {
            xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
          }

          xhr.onload = function () {
            if (xhr.status < 200 || xhr.status >= 300) {
              reject('Upload failed');
              return;
            }
            try {
              var json = JSON.parse(xhr.responseText);
              resolve(json.location);
            } catch (e) {
              reject('Invalid upload response');
            }
          };

          xhr.onerror = function () {
            reject('Upload failed');
          };

          xhr.send(formData);
        });
      },
    });
  }

  function initEditors() {
    editorConfigs.forEach(initEditor);
  }

  function togglePricingFields(select) {
    if (!select) return;
    var value = select.value;
    document.querySelectorAll('.bdgs-sf__pricing-extra').forEach(function (row) {
      var show = row.getAttribute('data-pricing') === value;
      row.style.display = show ? '' : 'none';
    });
  }

  window.bdgsInitPricingFields = function (select) {
    if (!select) return;
    togglePricingFields(select);
    select.addEventListener('change', function () {
      togglePricingFields(select);
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initEditors);
  } else {
    initEditors();
  }
})();

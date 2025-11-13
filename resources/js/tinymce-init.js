import tinymce from 'tinymce/tinymce';
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';

import 'tinymce/plugins/autoresize';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/table';
import 'tinymce/plugins/code';
import 'tinymce/plugins/codesample';

// skin & content css
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/content/default/content.min.css';

window.initTiny = (uploadUrl = null) => {
  // Prevent TinyMCE from attempting to load plugins/skins relative to the
  // current page. If you prefer a different public path, set
  // `window.tinymceBaseUrl = '/your/path'` before calling initTiny.
  // Default to the public vendor path where we copy TinyMCE assets.
  tinymce.baseURL = window.tinymceBaseUrl || '/vendor/tinymce';

  const cfg = {
    selector: 'textarea.tinymce',
    menubar: false,
    height: 260,
    plugins: 'lists link image table code codesample autoresize',
    toolbar: 'undo redo | blocks bold italic underline | bullist numlist outdent indent | link image table | code',
    branding: false,
    setup: (ed) => ed.on('change keyup', () => ed.save()),
    convert_urls: false,
    images_upload_url: uploadUrl || undefined,
    images_upload_credentials: true,
    // We import the skin/content CSS via the bundler, so prevent TinyMCE
    // from trying to load the files at runtime (avoids page-relative
    // requests for /plugins/... and /skins/...).
    skin: false,
    content_css: false,
  };

  // Only add license_key to the configuration if a key is provided. Leaving
  // an empty string here can cause TinyMCE to display the "disabled" message
  // in some builds. If you have a TinyMCE license, set `window.tinymceLicenseKey`
  // (e.g. from server-side env) before calling initTiny.
  try {
    if (window.tinymceLicenseKey && String(window.tinymceLicenseKey).trim().length > 0) {
      cfg.license_key = window.tinymceLicenseKey;
    }
  } catch (e) {
    // ignore
  }

  tinymce.init(cfg);
};

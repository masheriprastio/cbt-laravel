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

  tinymce.init({
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
    // Ensure the editor won't be disabled by a missing license manager
    // during development. If you have a valid TinyMCE license, set it
    // here (or via window.tinymceLicenseKey) instead of leaving it
    // blank.
    license_key: window.tinymceLicenseKey || '',
    // We import the skin/content CSS via the bundler, so prevent TinyMCE
    // from trying to load the files at runtime (avoids page-relative
    // requests for /plugins/... and /skins/...).
    skin: false,
    content_css: false,
  });
};

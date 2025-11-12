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
  });
};

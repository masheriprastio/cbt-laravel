import Quill from 'quill';
import 'quill/dist/quill.snow.css';

// Initialize Quill editors. Usage:
// - Add class `quill-editor` to a <textarea> you want replaced.
// - Optionally call window.initQuill(uploadUrl) to pass an image upload endpoint.
window.initQuill = (uploadUrl = null) => {
  document.querySelectorAll('textarea.quill-editor').forEach((textarea) => {
    // Avoid double-initializing
    if (textarea.dataset.quillInitialized) return;

    // Create container for Quill
    const editorContainer = document.createElement('div');
    editorContainer.className = 'quill-container mb-2';

    // Prefer inserting the editor inside an explicit .editor-wrapper if present.
    // This ensures choices and other following elements remain outside the editor.
    const wrapper = textarea.closest('.editor-wrapper');
    const parent = wrapper || textarea.parentNode;

    // Hide the original textarea (we'll keep it in DOM for form submit)
    textarea.style.display = 'none';

    if (wrapper) {
      // Insert editorContainer as the first element inside the wrapper before the textarea
      wrapper.insertBefore(editorContainer, textarea);
      // Move textarea to be immediately after the editor container inside wrapper
      wrapper.insertBefore(textarea, editorContainer.nextSibling);
    } else {
      // Legacy behaviour: replace textarea with editor then reinsert textarea after
      parent.replaceChild(editorContainer, textarea);
      parent.insertBefore(textarea, editorContainer.nextSibling);
    }

    // Set initial contents from textarea
    const initialHtml = textarea.value || '';

    const toolbarOptions = [
      [{ 'header': [1, 2, 3, false] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      ['link', 'image', 'code-block'],
      ['clean']
    ];

    const quill = new Quill(editorContainer, {
      modules: {
        toolbar: toolbarOptions
      },
      theme: 'snow'
    });

    // Put initial HTML into editor
    quill.clipboard.dangerouslyPasteHTML(initialHtml);

    // Tweak editor appearance to ensure a clear separation from the
    // elements that follow (choices etc.). This is defensive in case
    // global styles change.
    const qlContainer = editorContainer.querySelector('.ql-container');
    if (qlContainer) {
      qlContainer.style.minHeight = '160px';
      qlContainer.style.background = '#ffffff';
    }

    // On form submit, copy HTML back to textarea
    const form = textarea.closest('form');
    if (form) {
      form.addEventListener('submit', () => {
        textarea.value = quill.root.innerHTML;
      });
    }

    // Optional: image upload handler
    if (uploadUrl) {
      quill.getModule('toolbar').addHandler('image', () => {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();
        input.onchange = async () => {
          const file = input.files[0];
          if (!file) return;
          const fd = new FormData();
          fd.append('file', file);
          try {
            const resp = await fetch(uploadUrl, {
              method: 'POST',
              body: fd,
              credentials: 'include'
            });
            const data = await resp.json();
            // Expect upload endpoint to return { url: 'https://...' }
            if (data && data.url) {
              const range = quill.getSelection(true);
              quill.insertEmbed(range.index, 'image', data.url);
            }
          } catch (e) {
            console.error('Image upload failed', e);
          }
        };
      });
    }

    // Mark as initialized
    textarea.dataset.quillInitialized = '1';
    // Store reference in DOM for debugging
    textarea._quillEditor = quill;
  });
};

// Auto-init on page load without upload URL
window.addEventListener('DOMContentLoaded', () => window.initQuill());

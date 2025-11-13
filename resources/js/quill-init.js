import Quill from 'quill';
import 'quill/dist/quill.snow.css';

// Initialize Quill editors. Usage:
// - Add class `quill-editor` to a <textarea> you want replaced.
// - Optionally call window.initQuill(uploadUrl) to pass an image upload endpoint.
window.initQuill = (uploadUrl = null) => {
  document.querySelectorAll('textarea.quill-editor').forEach((textarea) => {
    // Avoid double-initializing
    if (textarea.dataset.quillInitialized) return;

  // Create container for Quill and place it where the textarea was so
  // following siblings (like the choices block) remain outside the editor.
  const editorContainer = document.createElement('div');
  editorContainer.className = 'quill-container mb-2';
  // Replace the textarea in the DOM with the editor container, then
  // re-insert the textarea (hidden) after the editor so form submission
  // still includes the value.
  const parent = textarea.parentNode;
  textarea.style.display = 'none';
  parent.replaceChild(editorContainer, textarea);
  parent.insertBefore(textarea, editorContainer.nextSibling);

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

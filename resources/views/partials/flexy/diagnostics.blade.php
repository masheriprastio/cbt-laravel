@php
// Small diagnostics partial to help during development.
// Shows presence of Bootstrap bundle and TinyMCE assets when APP_DEBUG is true.
$bootstrapPath = public_path('vendor/flexy/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js');
$tinymceSkin = public_path('vendor/tinymce/skins/ui/oxide/skin.min.css');
$tinymcePluginsDir = public_path('vendor/tinymce/plugins');
$bootstrapExists = file_exists($bootstrapPath);
$tinymceExists = file_exists($tinymceSkin) && is_dir($tinymcePluginsDir);
@endphp

@if(config('app.debug'))
  <div class="mb-3">
    @if($bootstrapExists)
      <div class="alert alert-success small mb-1" role="alert">
        Bootstrap JS: <strong>OK</strong>
      </div>
    @else
      <div class="alert alert-warning small mb-1" role="alert">
        Bootstrap JS: <strong>MISSING</strong> — expected at <code>public/vendor/flexy/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js</code>.
      </div>
    @endif

    @if($tinymceExists)
      <div class="alert alert-success small mb-1" role="alert">
        TinyMCE assets: <strong>OK</strong>
      </div>
    @else
      <div class="alert alert-warning small mb-1" role="alert">
        TinyMCE assets: <strong>MISSING</strong> — expected under <code>public/vendor/tinymce</code>. Run <code>npm run copy-tinymce-assets</code> or ensure your build copies them.
      </div>
    @endif
  </div>
@endif

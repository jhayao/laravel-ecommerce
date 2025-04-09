(function(){const a=`<div class="dz-preview dz-file-preview">
<div class="dz-details">
  <div class="dz-thumbnail">
    <img data-dz-thumbnail>
    <span class="dz-nopreview">No preview</span>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
    <div class="progress">
      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
    </div>
  </div>
  <div class="dz-filename" data-dz-name></div>
  <div class="dz-size" data-dz-size></div>
</div>
</div>`,r=document.querySelector("#dropzone-basic");r&&new Dropzone(r,{previewTemplate:a,parallelUploads:1,maxFilesize:5,addRemoveLinks:!0,maxFiles:1});const i=document.querySelector("#dropzone-multi");if(i&&new Dropzone(i,{previewTemplate:a,parallelUploads:1,maxFilesize:5,addRemoveLinks:!0}),typeof Dropzone<"u"){Dropzone.autoDiscover=!1;const s=e=>{new Dropzone(e,{url:"/upload",maxFilesize:2,addRemoveLinks:!0,dictDefaultMessage:"Drop files here or click to upload"})};s("#dropzone-basic"),document.querySelector(".form-repeater").addEventListener("click",e=>{e.target&&e.target.hasAttribute("data-repeater-create")&&setTimeout(()=>{const o=document.querySelector(".dropzone.needsclick:not(.dz-started)");o&&s(o)},100)})}else console.error("Dropzone library is not loaded.")})();

/**
 * File Upload
 */

'use strict';

(function () {
  // previewTemplate: Updated Dropzone default previewTemplate
  // ! Don't change it unless you really know what you are doing
  const previewTemplate = `<div class="dz-preview dz-file-preview">
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
</div>`;

  // ? Start your code from here

  // Basic Dropzone
  // --------------------------------------------------------------------
  const dropzoneBasic = document.querySelector('#dropzone-basic');
  if (dropzoneBasic) {
    const myDropzone = new Dropzone(dropzoneBasic, {
      previewTemplate: previewTemplate,
      parallelUploads: 1,
      maxFilesize: 5,
      addRemoveLinks: true,
      maxFiles: 1
    });
  }

  // Multiple Dropzone
  // --------------------------------------------------------------------
  const dropzoneMulti = document.querySelector('#dropzone-multi');
  if (dropzoneMulti) {
    const myDropzoneMulti = new Dropzone(dropzoneMulti, {
      previewTemplate: previewTemplate,
      parallelUploads: 1,
      maxFilesize: 5,
      addRemoveLinks: true
    });
  }

  // Ensure Dropzone is reinitialized for dynamically added elements
  if (typeof Dropzone !== 'undefined') {
    Dropzone.autoDiscover = false;

    const initializeDropzone = selector => {
      new Dropzone(selector, {
        url: '/upload', // Replace with your upload URL
        maxFilesize: 2, // Maximum file size in MB
        addRemoveLinks: true,
        dictDefaultMessage: 'Drop files here or click to upload'
      });
    };

    // Initialize Dropzone for existing elements
    initializeDropzone('#dropzone-basic');

    // Reinitialize Dropzone for dynamically added elements
    document.querySelector('.form-repeater').addEventListener('click', event => {
      if (event.target && event.target.hasAttribute('data-repeater-create')) {
        setTimeout(() => {
          const newDropzone = document.querySelector('.dropzone.needsclick:not(.dz-started)');
          if (newDropzone) {
            initializeDropzone(newDropzone);
          }
        }, 100); // Delay to ensure the new element is added to the DOM
      }
    });
  } else {
    console.error('Dropzone library is not loaded.');
  }
})();

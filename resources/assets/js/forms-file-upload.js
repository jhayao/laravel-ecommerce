/**
 * File Upload
 */

'use strict';

document.addEventListener('DOMContentLoaded', function () {
  // Disable auto-discover
  if (typeof Dropzone !== 'undefined') {
    Dropzone.autoDiscover = false;

    // Updated Dropzone default previewTemplate
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

    // Basic Dropzone
    const dropzoneBasic = document.querySelector('#dropzone-basic');
    if (dropzoneBasic) {
      new Dropzone(dropzoneBasic, {
        url: '/upload', // Replace with your actual endpoint
        previewTemplate: previewTemplate,
        parallelUploads: 1,
        maxFilesize: 5,
        addRemoveLinks: true,
        maxFiles: 1,
        dictDefaultMessage: 'Drop files here or click to upload'
      });
    } else {
      console.error("Dropzone element with selector '#dropzone-basic' not found.");
    }

    // Multiple Dropzone
    const dropzoneMulti = document.querySelector('#dropzone-multi');
    if (dropzoneMulti) {
      new Dropzone(dropzoneMulti, {
        url: '/upload',
        previewTemplate: previewTemplate,
        parallelUploads: 1,
        maxFilesize: 5,
        addRemoveLinks: true,
        dictDefaultMessage: 'Drop files here or click to upload'
      });
    }

    // Function to initialize Dropzone on newly added repeaters
    const initializeDropzone = selector => {
      const dropzoneElement = document.querySelector(selector);
      if (dropzoneElement && !dropzoneElement.classList.contains('dz-started') && !dropzoneElement.dropzone) {
        new Dropzone(dropzoneElement, {
          url: '/upload',
          maxFilesize: 2,
          addRemoveLinks: true,
          dictDefaultMessage: 'Drop files here or click to upload'
        });
      }
    };

    // Handle dynamically added dropzones (from repeater)
    const repeater = document.querySelector('.form-repeater');
    if (repeater) {
      repeater.addEventListener('click', event => {
        if (event.target && event.target.hasAttribute('data-repeater-create')) {
          setTimeout(() => {
            const newDropzones = document.querySelectorAll(
              '.dropzone.needsclick:not(.dz-started):not([data-initialized])'
            );
            newDropzones.forEach(el => {
              el.setAttribute('data-initialized', 'true');
              initializeDropzone(`#${el.id}`);
            });
          }, 100); // Wait for DOM insertion
        }
      });
    }
  } else {
    console.error('Dropzone library is not loaded.');
  }
});

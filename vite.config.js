import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import html from '@rollup/plugin-html';
import { glob } from 'glob';

/**
 * Get Files from a directory
 * @param {string} query
 * @returns array
 */
function GetFilesArray(query) {
  return glob.sync(query);
}

/**
 * JS Files
 */
const pageJsFiles = GetFilesArray('resources/assets/js/*.js');
const vendorJsFiles = GetFilesArray('resources/assets/vendor/js/*.js');
const LibsJsFiles = GetFilesArray('resources/assets/vendor/libs/**/*.js');

/**
 * SCSS & CSS Files
 */
const CoreScssFiles = GetFilesArray('resources/assets/vendor/scss/**/!(_)*.scss');
const LibsScssFiles = GetFilesArray('resources/assets/vendor/libs/**/!(_)*.scss');
const LibsCssFiles = GetFilesArray('resources/assets/vendor/libs/**/*.css');
const FontsScssFiles = GetFilesArray('resources/assets/vendor/fonts/**/!(_)*.scss');

/**
 * Custom Plugin to assign libraries to window
 */
function libsWindowAssignment() {
  return {
    name: 'libsWindowAssignment',
    transform(src, id) {
      if (id.includes('jkanban.js')) {
        return src.replace('this.jKanban', 'window.jKanban');
      } else if (id.includes('vfs_fonts')) {
        return src.replaceAll('this.pdfMake', 'window.pdfMake');
      }
    }
  };
}

export default defineConfig({
  // 👇 Change this if deploying to a subfolder like '/myapp/'
  base: '/',
  build: {
    outDir: 'public/build',
    assetsDir: 'assets',
    manifest: true
  },
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/assets/css/demo.css',
        'resources/js/app.js',
        ...pageJsFiles,
        ...vendorJsFiles,
        ...LibsJsFiles,
        'resources/js/laravel-user-management.js',
        ...CoreScssFiles,
        ...LibsScssFiles,
        ...LibsCssFiles,
        ...FontsScssFiles
      ],
      refresh: true
    }),
    html(),
    libsWindowAssignment()
  ]
});

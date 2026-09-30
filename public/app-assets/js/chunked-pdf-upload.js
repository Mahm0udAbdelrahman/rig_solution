/**
 * Upload a generated PDF blob in small chunks to report.generatePdf.
 *
 * Sending the whole PDF in one request fails for many-page reports once it
 * exceeds PHP's upload_max_filesize / post_max_size. Chunks of 1MB stay under
 * any sane server limit; the server re-assembles them on the last chunk.
 *
 * @param {Blob}     blob              The PDF produced by jsPDF (doc.output('blob')).
 * @param {Object}   options
 * @param {String}   options.url       The report.generatePdf route.
 * @param {Object}   options.fields    Extra form fields (folder, imageurl, report_id...).
 * @param {Function} [options.onProgress] Called with (chunkNumber, totalChunks).
 * @param {Number}   [options.chunkSize]  Bytes per chunk (default 1MB).
 * @return {jQuery.Promise} Resolves with the server response of the last chunk.
 */
(function (window, $) {
  window.uploadPdfInChunks = function (blob, options) {
    var chunkSize = options.chunkSize || 1024 * 1024;
    var fields = options.fields || {};
    var totalChunks = Math.max(1, Math.ceil(blob.size / chunkSize));
    var uploadId = 'pdf' + Date.now() + Math.random().toString(36).slice(2, 10);

    function uploadChunk(index) {
      if (typeof options.onProgress === 'function') {
        options.onProgress(index + 1, totalChunks);
      }

      var formData = new FormData();
      Object.keys(fields).forEach(function (key) {
        if (fields[key] !== null && fields[key] !== undefined && fields[key] !== '') {
          formData.append(key, fields[key]);
        }
      });
      formData.append('upload_id', uploadId);
      formData.append('chunk_index', index);
      formData.append('total_chunks', totalChunks);
      formData.append('chunk', blob.slice(index * chunkSize, (index + 1) * chunkSize, 'application/pdf'), 'part' + index);

      var request = $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'POST',
        url: options.url,
        cache: false,
        data: formData,
        processData: false,
        contentType: false
      });

      if (index < totalChunks - 1) {
        return request.then(function () { return uploadChunk(index + 1); });
      }
      return request;
    }

    return uploadChunk(0);
  };
})(window, jQuery);

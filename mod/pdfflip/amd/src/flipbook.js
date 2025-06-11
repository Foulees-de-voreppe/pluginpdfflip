import $ from 'jquery';
import * as pdfjsLib from 'pdfjs-dist';

export const init = (url) => {
    pdfjsLib.GlobalWorkerOptions.workerSrc = M.util.image_url('pdf.worker', 'mod_pdfflip');

    const container = $('#flipbook');
    const loadingTask = pdfjsLib.getDocument(url);

    loadingTask.promise.then(pdf => {
        const numPages = pdf.numPages;
        const renderPage = (num) => {
            pdf.getPage(num).then(page => {
                const viewport = page.getViewport({ scale: 1.5 });
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                container.append(canvas);
                page.render({ canvasContext: context, viewport: viewport });
            });
        };
        for (let i = 1; i <= numPages; i++) {
            renderPage(i);
        }
    });
};
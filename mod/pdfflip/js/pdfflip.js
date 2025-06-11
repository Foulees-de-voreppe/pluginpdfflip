(function() {
    const container = document.getElementById('flipbook');
    if (!container) {
        return;
    }
    const url = container.dataset.pdf;
    if (!url) {
        return;
    }

    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.9.179/pdf.min.js';
    script.onload = () => initFlipbook(url);
    document.head.appendChild(script);

    function initFlipbook(pdfurl) {
        const loadingTask = window['pdfjsLib'].getDocument(pdfurl);
        loadingTask.promise.then(pdf => {
            let current = 1;
            const num = pdf.numPages;
            const canvas = document.createElement('canvas');
            container.appendChild(canvas);
            const ctx = canvas.getContext('2d');

            function renderPage(n) {
                pdf.getPage(n).then(page => {
                    const viewport = page.getViewport({scale: 1.5});
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    page.render({canvasContext: ctx, viewport: viewport});
                });
            }

            function next() {
                if (current < num) {
                    current++;
                    renderPage(current);
                }
            }

            function prev() {
                if (current > 1) {
                    current--;
                    renderPage(current);
                }
            }

            const prevBtn = document.createElement('button');
            prevBtn.textContent = '<';
            prevBtn.addEventListener('click', prev);
            const nextBtn = document.createElement('button');
            nextBtn.textContent = '>';
            nextBtn.addEventListener('click', next);
            container.appendChild(prevBtn);
            container.appendChild(nextBtn);

            renderPage(current);
        });
    }
})();
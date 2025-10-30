/**
 * Chart Debugging Utility
 * Used to troubleshoot chart display issues
 */
(function () {
    console.log('Charts Debug Utility loaded');

    // Check if Chart.js is available
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is NOT loaded - charts will not display');
    } else {
        console.log('Chart.js is loaded - version:', Chart.version);
    }

    // Wait for document to be ready
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM loaded, checking for chart canvases');

        // Find all canvas elements
        const canvases = document.querySelectorAll('canvas');
        console.log(`Found ${canvases.length} canvas elements:`, canvases);

        canvases.forEach(canvas => {
            console.log(`Canvas #${canvas.id}:`, {
                width: canvas.width,
                height: canvas.height,
                offsetWidth: canvas.offsetWidth,
                offsetHeight: canvas.offsetHeight,
                style: window.getComputedStyle(canvas),
                visibility: isElementVisible(canvas)
            });
        });
    });

    // Check if an element is visible
    function isElementVisible(el) {
        if (!el) return false;

        const style = window.getComputedStyle(el);
        if (style.display === 'none') return false;
        if (style.visibility !== 'visible') return false;
        if (parseFloat(style.opacity) === 0) return false;

        // Check if element has dimensions
        const rect = el.getBoundingClientRect();
        if (rect.width === 0 || rect.height === 0) return false;

        return true;
    }
})();

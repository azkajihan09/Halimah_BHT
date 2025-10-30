/**
 * Chart Helper Utility
 * Provides consistent initialization and debugging for charts
 */
var ChartHelper = {
    /**
     * Initialize a chart with standard settings
     * 
     * @param {string} canvasId - The ID of the canvas element
     * @param {string} type - Chart type (line, bar, pie, doughnut, etc.)
     * @param {object} data - Chart data object
     * @param {object} options - Chart options
     * @return {object} Chart instance
     */
    initChart: function (canvasId, type, data, options) {
        try {
            // Get canvas context
            var canvas = document.getElementById(canvasId);
            if (!canvas) {
                console.error('ChartHelper: Canvas with ID ' + canvasId + ' not found');
                return null;
            }

            var ctx = canvas.getContext('2d');

            // Set default options if not provided
            options = options || {};

            // Create and return the chart
            return new Chart(ctx, {
                type: type,
                data: data,
                options: options
            });
        } catch (e) {
            console.error('ChartHelper: Error initializing chart', e);
            this.debugCanvas(canvasId);
            return null;
        }
    },

    /**
     * Debug canvas element to help identify rendering issues
     * 
     * @param {string} canvasId - Canvas element ID
     */
    debugCanvas: function (canvasId) {
        var canvas = document.getElementById(canvasId);
        if (!canvas) {
            console.error('ChartHelper: Canvas with ID ' + canvasId + ' not found during debugging');
            return;
        }

        // Log canvas details
        console.log('ChartHelper Debug for #' + canvasId + ':', {
            width: canvas.width,
            height: canvas.height,
            offsetWidth: canvas.offsetWidth,
            offsetHeight: canvas.offsetHeight,
            style: window.getComputedStyle(canvas),
            visible: this.isElementVisible(canvas),
            parent: canvas.parentNode
        });

        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('ChartHelper: Chart.js is not loaded!');
        } else {
            console.log('ChartHelper: Chart.js version', Chart.version, 'is loaded');
        }

        // Highlight the canvas for visual debugging
        var originalBorder = canvas.style.border;
        canvas.style.border = '2px solid red';

        setTimeout(function () {
            canvas.style.border = originalBorder;
        }, 5000);
    },

    /**
     * Check if an element is visible in the document
     * 
     * @param {HTMLElement} elem - Element to check
     * @return {boolean} True if visible
     */
    isElementVisible: function (elem) {
        if (!elem) return false;

        var style = window.getComputedStyle(elem);
        if (style.display === 'none') return false;
        if (style.visibility !== 'visible') return false;
        if (parseFloat(style.opacity) === 0) return false;

        var rect = elem.getBoundingClientRect();
        if (rect.width === 0 || rect.height === 0) return false;

        return true;
    },

    /**
     * Initialize all charts on the page
     * This function can be called to initialize charts that are dynamically added
     */
    initAllCharts: function () {
        console.log('ChartHelper: Initializing all charts on page');
        
        // Find all canvas elements with chart-related classes or data attributes
        var canvases = document.querySelectorAll('canvas[id*="chart"], canvas[id*="Chart"], canvas.chart-canvas');
        
        if (canvases.length === 0) {
            console.log('ChartHelper: No chart canvases found on page');
            return;
        }
        
        console.log('ChartHelper: Found ' + canvases.length + ' potential chart canvas(es)');
        
        // This is a placeholder - specific chart initialization should be handled
        // by the individual page scripts, not by this helper
        canvases.forEach(function(canvas) {
            if (canvas.id) {
                console.log('ChartHelper: Found canvas with ID: ' + canvas.id);
            }
        });
    }
};

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded, preparing to initialize charts');
    setTimeout(function () {
        ChartHelper.initAllCharts();
    }, 300);
});

// Display the Current Year
function displayYear() {
    const yearElement = document.querySelector("#displayYear");
    if (yearElement) {
        yearElement.innerHTML = new Date().getFullYear();
    }
}
displayYear();

// Debounce Function for Optimizing Event Handling
const debounce = (fn, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

// Food Section Initialization
jQuery(document).ready(function ($) {
    const $grid = $('.grid');

    // Loading spinner
    function showLoadingSpinner(show) {
        if (show) $('.filters-content').addClass('is-loading');
        else $('.filters-content').removeClass('is-loading');
    }

    // Equalize Heights Function
    function equalizeHeights() {
        const $visibleItems = $('.all').not('.isotope-hidden');

        // Reset item heights
        $('.box, .detail-box, .img-box, .description, .price-box').css('height', '');

        // Calculate and apply the max heights dynamically
        const heights = {};
        ['box', 'detail-box', 'img-box', 'description', 'price-box'].forEach((selector) => {
            heights[selector] = 0;
            $visibleItems.each(function () {
                const height = $(this).find(`.${selector}`).outerHeight();
                heights[selector] = Math.max(heights[selector], height);
            });
        });

        // Apply heights and add spacing
        $visibleItems.each(function () {
            $(this).find('.box').height(heights['box']);
            $(this).find('.detail-box').height(heights['detail-box']);
            $(this).find('.img-box').height(heights['img-box']);
            $(this).find('.description').height(heights['description']);
            $(this).find('.price-box').height(heights['price-box']);
        });

        // Add margin to grid items for spacing
        $visibleItems.css({ marginBottom: '20px' });

        // Update layout after height adjustment
        $grid.isotope('layout');
    }

    // Initialize Isotope Grid
    if ($grid.length) {
        $grid.isotope({
            itemSelector: '.all',
            layoutMode: 'fitRows',
            fitRows: { gutter: 20 }, // Reduced gutter spacing for a cleaner look
            filter: '.pizza', // Default filter on page load
            transitionDuration: '0.4s',
        });

        // Filter Button Click Event
        $('.filters_menu').on('click', 'li', function () {
            const filterValue = $(this).attr('data-filter');
            $('.filters_menu li').removeClass('active');
            $(this).addClass('active');

            // Show loading spinner
            showLoadingSpinner(true);

            // Apply filter and adjust heights
            $grid.isotope({ filter: filterValue });
            setTimeout(() => {
                showLoadingSpinner(false);
                equalizeHeights();
                fadeInGridItems();
            }, 350);
        });

        // Fade-in animation for grid items
        function fadeInGridItems() {
            $('.all').css({ opacity: 0 }).animate({ opacity: 1 }, 400);
        }

        // Ensure images are loaded before layout
        $grid.imagesLoaded().then(() => {
            setTimeout(() => {
                equalizeHeights();
                $grid.isotope('layout');
            }, 100);
        });

        // Handle window resize with debounce
        $(window).on('resize', debounce(() => {
            equalizeHeights();
        }, 250));

        // Ensure Layout After Page Load
        $(window).on('load', () => {
            equalizeHeights();
            $grid.isotope('layout');
        });
    }

    // Nice Select Initialization
    if ($('select').length) {
        $('select').niceSelect();
    }
});

// Google Maps Initialization
function initMap() {
    const mapOptions = {
        center: { lat: 66.5017333, lng: 25.7307001 }, // Rovaniemi coordinates
        zoom: 15,
        styles: [
            {
                "featureType": "poi.business",
                "stylers": [{ "visibility": "on" }]
            }
        ]
    };
    const mapElement = document.getElementById("googleMap");
    if (mapElement) {
        new google.maps.Map(mapElement, mapOptions);
    }
}

// Wait for Google Maps to Load
window.addEventListener('load', () => {
    setTimeout(() => {
        if (typeof google !== 'undefined') {
            initMap();
        }
    }, 200);
});

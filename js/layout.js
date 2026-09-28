/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Version: 4.3.0
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Layout Js File
*/

(function () {

    'use strict';

    if (sessionStorage.getItem('defaultAttribute')) {

        var attributesValue = document.documentElement.attributes;
        var CurrentLayoutAttributes = {};
        Object.entries(attributesValue).forEach(function(key) {
            if (key[1] && key[1].nodeName && key[1].nodeName != "undefined") {
                var nodeKey = key[1].nodeName;
                CurrentLayoutAttributes[nodeKey] = key[1].nodeValue;
            }
          });
        if(sessionStorage.getItem('defaultAttribute') !== JSON.stringify(CurrentLayoutAttributes)) {
            sessionStorage.clear();
            window.location.reload();
        } else {
            var isLayoutAttributes = {};
            isLayoutAttributes['data-layout'] = sessionStorage.getItem('data-layout');
            isLayoutAttributes['data-sidebar-size'] = sessionStorage.getItem('data-sidebar-size');
            isLayoutAttributes['data-bs-theme'] = sessionStorage.getItem('data-bs-theme');
            isLayoutAttributes['data-layout-width'] = sessionStorage.getItem('data-layout-width');
            isLayoutAttributes['data-sidebar'] = sessionStorage.getItem('data-sidebar');
            isLayoutAttributes['data-sidebar-image'] = sessionStorage.getItem('data-sidebar-image');
            isLayoutAttributes['data-layout-direction'] = sessionStorage.getItem('data-layout-direction');
            isLayoutAttributes['data-layout-position'] = sessionStorage.getItem('data-layout-position');
            isLayoutAttributes['data-layout-style'] = sessionStorage.getItem('data-layout-style');
            isLayoutAttributes['data-topbar'] = sessionStorage.getItem('data-topbar');
            isLayoutAttributes['data-preloader'] = sessionStorage.getItem('data-preloader');
            isLayoutAttributes['data-body-image'] = sessionStorage.getItem('data-body-image');
            
            Object.keys(isLayoutAttributes).forEach(function (x) {
                if (isLayoutAttributes[x] && isLayoutAttributes[x]) {
                    document.documentElement.setAttribute(x, isLayoutAttributes[x]);
                }
            });
        }
    }

})();

(function () {
    'use strict';

    function applyTheme(themeName) {
        const root = document.body;
        const toggleButton = document.getElementById('theme-toggle');
        const gradientElements = document.querySelectorAll('.bg-gradient-primary');

        root.setAttribute('data-theme', themeName);
        localStorage.setItem('docuflow-theme', themeName);

        gradientElements.forEach(function (element) {
            const gradient = themeName === 'dark'
                ? 'linear-gradient(135deg, #111827, #1f2937)'
                : 'linear-gradient(135deg, #6f42ff, #9b4dff)';
            element.style.background = gradient;
        });

        if (toggleButton) {
            if (themeName === 'dark') {
                toggleButton.innerHTML = '<i class="ri-sun-line me-1"></i> Light Mode';
            } else {
                toggleButton.innerHTML = '<i class="ri-moon-line me-1"></i> Dark Mode';
            }
        }
    }

    const savedTheme = localStorage.getItem('docuflow-theme') || 'purple-blue';
    applyTheme(savedTheme);

    const toggleButton = document.getElementById('theme-toggle');
    if (toggleButton) {
        toggleButton.addEventListener('click', function () {
            const currentTheme = document.body.getAttribute('data-theme') === 'dark' ? 'purple-blue' : 'dark';
            applyTheme(currentTheme);
        });
    }
})();
document.addEventListener('DOMContentLoaded', function () {
    const sections = {
        SNA: { file: 'webpage/SNA_Navigationbar.html', css: 'css/navigationBar.css' },
        BNI: { file: 'webpage/BNI_Navigationbar.html', css: 'css/navigationBar.css' },
        TNR: { file: 'webpage/TNR_Navigationbar.html', css: 'css/navigationBar.css' }
    };

    function addClickListener(id, section) {
        const button = document.getElementById(id);
        if (button) {
            button.addEventListener('click', () => loadContent(section));
        } else {
            console.warn(`Element with ID '${id}' not found.`);
        }
    }

    // Add event listeners
    addClickListener('schedule_and_appointment', 'SNA');
    addClickListener('billing_and_invoice', 'BNI');
    addClickListener('tracking_and_report', 'TNR');

    // Load default content when the page loads
    loadContent('SNA');

    function loadContent(section) {
        if (!(section in sections)) {
            console.error(`Invalid section: ${section}`);
            return;
        }

        const { file: contentFile, css: cssFile } = sections[section];

        // Fetch HTML content dynamically
        fetch(contentFile, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(content => {
                const contentElement = document.getElementById('content');
                if (contentElement) {
                    contentElement.innerHTML = content;
                    loadCSS(cssFile);
                } else {
                    console.error("Element with ID 'content' not found.");
                }
            })
            .catch(error => {
                console.error('Error loading content:', error);
            });
    }

    // Function to load external CSS dynamically
    function loadCSS(file) {
        let existingLink = document.querySelector('link[data-type="dynamic-css"]');
        if (existingLink) existingLink.remove();

        let linkTag = document.createElement('link');
        linkTag.rel = 'stylesheet';
        linkTag.type = 'text/css';
        linkTag.href = file;
        linkTag.setAttribute('data-type', 'dynamic-css');

        document.head.appendChild(linkTag);
    }
});

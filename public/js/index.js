/**
 * Loads cat facts from the API based on provided URL parameters.
 * @param {URLSearchParams} params - Query parameters (count, lang, id).
 */
function loadFactsFromParams(params) {
    const loader = document.getElementById('loader');
    const factsContainer = document.getElementById('facts');
    const errorMessage = document.getElementById('error-message');
    const card = document.getElementById('factCard');

    // Show the loader before making the request
    loader.classList.remove('d-none');
    loader.classList.add('d-block');

    // Clear previous content
    factsContainer.innerHTML = '';
    errorMessage.innerHTML = '';
    card.classList.add('d-none');
    errorMessage.classList.add('d-none');

    fetch('api.php?' + params.toString())
        .then(response => response.json())
        .then(data => {
            // Hide the loader when the response arrives
            loader.classList.remove('d-block');
            loader.classList.add('d-none');

            if (data.success && data.facts.length > 0) {
                data.facts.forEach(fact => {
                    const div = document.createElement('div');
                    div.className = 'fact';
                    div.textContent = fact;
                    factsContainer.appendChild(div);
                });

                errorMessage.classList.add('d-none');
                card.classList.remove('d-none');
                card.classList.add('d-block');
            } else {
                console.error(data.error);
                errorMessage.innerHTML = `<div class="fs-3">Error: ${data.error}</div>`;
                errorMessage.classList.remove('d-none');
                errorMessage.classList.add('d-block');
            }
        })
        .catch(error => {
            // Hide the loader on error
            loader.classList.remove('d-block');
            loader.classList.add('d-none');

            document.getElementById('error-message').innerHTML = '<div class="fs-3">Request failed.</div>';
            console.error(error);
        });
}
  
// Event listener for form submission
document.getElementById('factForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Extract data from the form
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);

    // Update the browser URL without reloading, allowing sharing/bookmarking of current query
    history.pushState({}, '', '?' + params.toString());

    // Load facts with parameters from the submitted form
    loadFactsFromParams(params);
});

/**
 * Sets form input values based on URL query parameters.
 *
 * @param {URLSearchParams} params - The URLSearchParams object representing current query string
 */
function setFormValuesFromQuery(params) {
    if (params.has('count')) {
        document.querySelector('[name="count"]').value = params.get('count');
    }
    if (params.has('lang')) {
        document.querySelector('[name="lang"]').value = params.get('lang');
    }
    if (params.has('id')) {
        document.querySelector('[name="id"]').value = params.get('id');
    }
}
  
// On page load, check if URL contains parameters and fetch facts if so
document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);

    setFormValuesFromQuery(params);

    // If main parameters exist, trigger data fetch
    if (params.has('count') || params.has('lang') || params.has('id')) {
        loadFactsFromParams(params);
    }
});
  
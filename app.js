document.addEventListener('DOMContentLoaded', () => {
    
    // --- Router: Decide which function to run based on the current page ---
    const path = window.location.pathname;

    if (path.endsWith('index.html') || path === '/' || path.endsWith('/')) {
        renderEventCards();
    } else if (path.endsWith('event-detail.html')) {
        renderEventDetails();
    } else if (path.endsWith('register.html')) {
        renderRegistrationForm();
    }

});

/**
 * Renders the event cards on the homepage.
 */
function renderEventCards() {
    const gridContainer = document.querySelector('.event-grid-container');
    if (!gridContainer) return; // Exit if the container is not on the page

    gridContainer.innerHTML = ''; // Clear any existing static content

    // Loop through the events data and create a card for each one
    events.forEach(event => {
        const eventCard = document.createElement('div');
        eventCard.className = 'event-card';
        
        eventCard.innerHTML = `
            <img class="card-image" src="${event.image}" alt="${event.title}">
            <h3 class="card-title">${event.title}</h3>
            <a href="event-detail.html?id=${event.id}" class="card-button">View Event</a>
        `;
        
        gridContainer.appendChild(eventCard);
    });
}

/**
 * Renders the details for a single event on the event detail page.
 */
function renderEventDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const eventId = urlParams.get('id');
    const event = events.find(e => e.id === eventId);

    if (!event) {
        const mainContent = document.querySelector('.main-content');
        if(mainContent) mainContent.innerHTML = '<h2 class="page-title">Event not found.</h2>';
        return;
    }

    // Populate the page with the event data using specific element IDs
    document.title = `${event.title} - EventHub`;
    document.getElementById('event-banner').src = event.banner;
    document.getElementById('event-banner').alt = event.title;
    document.getElementById('event-title').textContent = event.title;
    document.getElementById('event-date').textContent = event.date;
    document.getElementById('event-time').textContent = event.time;
    document.getElementById('event-location').textContent = event.location;
    document.getElementById('event-description').textContent = event.description;
    document.getElementById('register-button').href = `register.html?id=${event.id}`;
}

/**
 * Updates the registration form to show which event is being registered for.
 */
function renderRegistrationForm() {
    const urlParams = new URLSearchParams(window.location.search);
    const eventId = urlParams.get('id');
    const event = events.find(e => e.id === eventId);

    const pageTitle = document.getElementById('registration-title');
    if (pageTitle && event) {
        pageTitle.textContent = `Register for ${event.title}`;
    }
}

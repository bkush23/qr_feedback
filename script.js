document.addEventListener('DOMContentLoaded', () => {
    // --- Element References ---
    const guestLinks = document.getElementById('guest-links');
    const userLinks = document.getElementById('user-links');
    const loginBtn = document.getElementById('login-btn');
    const logoutBtn = document.getElementById('logout-btn');

    // --- State ---
    // In a real app, this would be determined by checking a token or session
    let isLoggedIn = false; 

    // --- Functions ---
    // This function updates the UI based on the login state
    function updateUI() {
        if (isLoggedIn) {
            // If logged in, hide guest links and show user links
            guestLinks.classList.add('hidden');
            userLinks.classList.remove('hidden');
            userLinks.classList.add('flex');
        } else {
            // If not logged in, show guest links and hide user links
            guestLinks.classList.remove('hidden');
            guestLinks.classList.add('flex');
            userLinks.classList.add('hidden');
        }
    }

    // --- Event Listeners ---
    // When the login button is clicked, set state to logged in and update UI
    loginBtn.addEventListener('click', () => {
        isLoggedIn = true;
        updateUI();
    });

    // When the logout button is clicked, set state to logged out and update UI
    logoutBtn.addEventListener('click', () => {
        isLoggedIn = false;
        updateUI();
    });

    // --- Initial UI setup ---
    // Set the initial state of the buttons when the page loads
    updateUI();
});
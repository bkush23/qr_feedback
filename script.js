document.getElementById('companyForm').addEventListener('submit', function (event) {
    event.preventDefault();

    let website = document.getElementById('company_website').value.trim();
    const resultDiv = document.getElementById('result');

    // Auto-fix if protocol is missing
    if (!/^https?:\/\//i.test(website)) {
        website = 'https://' + website;
    }

    // Update the field visually with the corrected URL
    document.getElementById('company_website').value = website;

    resultDiv.textContent = "Checking website...";

    if (!isValidUrl(website)) {
        resultDiv.textContent = "Invalid website format.";
        resultDiv.style.color = 'red';
        return;
    }

    // Try to reach the site using fetch
    fetch(website, { method: 'HEAD', mode: 'no-cors' })
        .then(() => {
            resultDiv.textContent = "Website looks valid and reachable!";
            resultDiv.style.color = 'green';

            // Collect data (can send to PHP here)
            const data = {
                company_name: document.getElementById('company_name').value,
                company_email: document.getElementById('company_email').value,
                company_password: document.getElementById('company_password').value,
                company_contact_no: document.getElementById('company_contact_no').value,
                company_category: document.getElementById('company_category').value,
                company_address: document.getElementById('company_address').value,
                company_website: website,
            };

            console.log("Collected Data:", data);
        })
        .catch((error) => {
            resultDiv.textContent = "Could not reach the website.";
            resultDiv.style.color = 'red';
            console.error("Fetch error:", error);
        });
});

function isValidUrl(url) {
    try {
        new URL(url);
        return true;
    } catch (err) {
        return false;
    }
}

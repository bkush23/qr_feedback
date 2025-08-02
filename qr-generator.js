document.addEventListener('DOMContentLoaded', () => {
    const generateBtn = document.getElementById('generate-qr-btn');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const modal = document.getElementById('qr-modal');
    const qrcodeContainer = document.getElementById('qrcode-container');

    if (generateBtn) {
        generateBtn.addEventListener('click', () => {
            qrcodeContainer.innerHTML = '';

            const baseUrl = window.location.origin + window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));
            const eventId = 'community-meetup-2025';
            const feedbackUrl = `${baseUrl}/feedback.html?event=${eventId}`;

            console.log(`Generating QR code for: ${feedbackUrl}`);

            new QRCode(qrcodeContainer, {
                text: feedbackUrl,
                width: 200,
                height: 200,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            modal.classList.remove('hidden');
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    }

    if (modal) {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    }
});
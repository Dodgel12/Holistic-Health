/**
 * delete-modal.js
 * Gestione modale eliminazione cliente con conferma password — Terranova
 */

const DeleteModal = {
    overlay: null,
    clientIdInput: null,
    clientNameEl: null,
    passwordInput: null,
    errorEl: null,

    init() {
        this.overlay = document.getElementById('deleteModalOverlay');
        this.clientIdInput = document.getElementById('deleteClientId');
        this.clientNameEl = document.getElementById('deleteClientName');
        this.passwordInput = document.getElementById('deletePassword');
        this.errorEl = document.getElementById('deletePasswordError');

        // Chiudi cliccando fuori
        this.overlay?.addEventListener('click', (e) => {
            if (e.target === this.overlay) this.close();
        });

        // ESC per chiudere
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.close();
        });
    },

    open(clientId, clientName) {
        if (!this.overlay) return;
        this.clientIdInput.value = clientId;
        this.clientNameEl.textContent = clientName;
        this.passwordInput.value = '';
        this.errorEl.classList.remove('visible');
        this.passwordInput.classList.remove('error');
        this.overlay.classList.add('active');
        setTimeout(() => this.passwordInput.focus(), 100);
    },

    close() {
        this.overlay?.classList.remove('active');
    },

    confirm() {
        const pwd = this.passwordInput.value.trim();
        if (!pwd) {
            this.passwordInput.classList.add('error');
            this.errorEl.textContent = 'Inserisci la password per confermare.';
            this.errorEl.classList.add('visible');
            return;
        }
        // Submits the hidden form
        document.getElementById('deleteForm').submit();
    }
};

document.addEventListener('DOMContentLoaded', () => DeleteModal.init());

/**
 * delete-modal.js
 * Modale riusabile per confermare le eliminazioni.
 */

const DeleteModal = {
    overlay: null,
    clientIdInput: null,
    clientNameEl: null,
    passwordInput: null,
    errorEl: null,
    genericOverlay: null,
    _escapeHandlerBound: false,

    init() {
        this.overlay = document.getElementById('deleteModalOverlay');
        this.clientIdInput = document.getElementById('deleteClientId');
        this.clientNameEl = document.getElementById('deleteClientName');
        this.passwordInput = document.getElementById('deletePassword');
        this.errorEl = document.getElementById('deletePasswordError');

        this.overlay?.addEventListener('click', (e) => {
            if (e.target === this.overlay) this.close();
        });

        if (!this._escapeHandlerBound) {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.close();
            });
            this._escapeHandlerBound = true;
        }
    },

    // Modale usato nella lista pazienti.
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

        if (this.genericOverlay) {
            this.genericOverlay.classList.remove('active');
            setTimeout(() => {
                this.genericOverlay?.remove();
                this.genericOverlay = null;
            }, 120);
        }
    },

    confirm() {
        const pwd = this.passwordInput.value.trim();
        if (!pwd) {
            this.passwordInput.classList.add('error');
            this.errorEl.textContent = 'Inserisci la password per confermare.';
            this.errorEl.classList.add('visible');
            return;
        }
        document.getElementById('deleteForm').submit();
    },

    show(options) {
        const cfg = {
            title: options?.title || 'Conferma eliminazione',
            message: options?.message || 'Questa azione è irreversibile. Vuoi continuare?',
            confirmText: options?.confirmText || 'Elimina',
            cancelText: options?.cancelText || 'Annulla',
            requirePassword: Boolean(options?.requirePassword),
            passwordLabel: options?.passwordLabel || 'Password di conferma',
            passwordPlaceholder: options?.passwordPlaceholder || 'Inserisci la password',
            onConfirm: typeof options?.onConfirm === 'function' ? options.onConfirm : () => {}
        };

        this.close();

        const wrapper = document.createElement('div');
        wrapper.className = 'modal-overlay active';
        wrapper.id = 'genericDeleteModalOverlay';

        wrapper.innerHTML = `
            <div class="modal">
                <div class="modal-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3>${cfg.title}</h3>
                <p>${cfg.message}</p>
                ${cfg.requirePassword ? `
                    <div class="form-group mt-12">
                        <label for="genericDeletePassword">${cfg.passwordLabel}</label>
                        <input type="password" id="genericDeletePassword" placeholder="${cfg.passwordPlaceholder}">
                        <span class="form-error" id="genericDeletePasswordError"></span>
                    </div>
                ` : ''}
                <div class="modal-actions">
                    <button type="button" class="btn btn-ghost" id="genericDeleteCancel">${cfg.cancelText}</button>
                    <button type="button" class="btn btn-danger" id="genericDeleteConfirm">${cfg.confirmText}</button>
                </div>
            </div>
        `;

        document.body.appendChild(wrapper);
        this.genericOverlay = wrapper;

        wrapper.addEventListener('click', (e) => {
            if (e.target === wrapper) this.close();
        });

        const confirmBtn = wrapper.querySelector('#genericDeleteConfirm');
        const cancelBtn = wrapper.querySelector('#genericDeleteCancel');
        const pwdInput = wrapper.querySelector('#genericDeletePassword');
        const pwdError = wrapper.querySelector('#genericDeletePasswordError');

        cancelBtn.addEventListener('click', () => this.close());
        confirmBtn.addEventListener('click', () => {
            let passwordValue = '';
            if (cfg.requirePassword) {
                passwordValue = (pwdInput?.value || '').trim();
                if (!passwordValue) {
                    if (pwdError) {
                        pwdError.textContent = 'Inserisci la password per confermare.';
                        pwdError.classList.add('visible');
                    }
                    pwdInput?.classList.add('error');
                    return;
                }
            }

            cfg.onConfirm(passwordValue);
            this.close();
        });

        if (pwdInput) {
            setTimeout(() => pwdInput.focus(), 80);
        } else {
            setTimeout(() => confirmBtn.focus(), 80);
        }
    },

    confirmDeleteForm(formOrSelector, options = {}) {
        const form = typeof formOrSelector === 'string'
            ? document.querySelector(formOrSelector)
            : formOrSelector;

        if (!form) return;

        const passwordFieldName = options.passwordFieldName || 'confirm_password';
        this.show({
            title: options.title || 'Conferma eliminazione',
            message: options.message || 'Questa azione è irreversibile. Vuoi continuare?',
            confirmText: options.confirmText || 'Elimina',
            requirePassword: Boolean(options.requirePassword),
            passwordLabel: options.passwordLabel || 'Password di conferma',
            onConfirm: (passwordValue) => {
                if (options.requirePassword) {
                    let pwdField = form.querySelector('[name="' + passwordFieldName + '"]');
                    if (!pwdField) {
                        pwdField = document.createElement('input');
                        pwdField.type = 'hidden';
                        pwdField.name = passwordFieldName;
                        form.appendChild(pwdField);
                    }
                    pwdField.value = passwordValue;
                }
                form.submit();
            }
        });
    },

    confirmDeleteLink(url, options = {}) {
        this.show({
            title: options.title || 'Conferma eliminazione',
            message: options.message || 'Questa azione è irreversibile. Vuoi continuare?',
            confirmText: options.confirmText || 'Elimina',
            requirePassword: Boolean(options.requirePassword),
            passwordLabel: options.passwordLabel || 'Password di conferma',
            onConfirm: (passwordValue) => {
                const method = (options.method || 'GET').toUpperCase();
                const form = document.createElement('form');
                form.method = method;
                form.action = url;
                form.className = 'hidden';

                const fields = options.fields || {};
                Object.keys(fields).forEach((k) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = k;
                    input.value = String(fields[k]);
                    form.appendChild(input);
                });

                if (options.requirePassword) {
                    const pwd = document.createElement('input');
                    pwd.type = 'hidden';
                    pwd.name = options.passwordFieldName || 'confirm_password';
                    pwd.value = passwordValue;
                    form.appendChild(pwd);
                }

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', () => DeleteModal.init());

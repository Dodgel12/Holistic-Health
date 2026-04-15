/**
 * validation.js
 * Funzioni riutilizzabili per validare i form.
 */

const Validation = {
    /** Controlla che il campo non sia vuoto. */
    required(field, errorEl, msg = 'Campo obbligatorio.') {
        const val = field.value.trim();
        if (!val) {
            this.showError(field, errorEl, msg);
            return false;
        }
        this.clearError(field, errorEl);
        return true;
    },

    /** Controlla il formato email. */
    email(field, errorEl, msg = 'Email non valida.') {
        const val = field.value.trim();
        if (val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            this.showError(field, errorEl, msg);
            return false;
        }
        this.clearError(field, errorEl);
        return true;
    },

    /** Controlla che il numero sia nel range richiesto. */
    numericRange(field, errorEl, min, max, msg = null) {
        const val = parseFloat(field.value);
        if (field.value !== '' && (isNaN(val) || val < min || val > max)) {
            this.showError(field, errorEl, msg || `Valore tra ${min} e ${max}.`);
            return false;
        }
        this.clearError(field, errorEl);
        return true;
    },

    /** Controlla che sia selezionata un'opzione. */
    selected(field, errorEl, msg = 'Seleziona un\'opzione.') {
        if (!field.value) {
            this.showError(field, errorEl, msg);
            return false;
        }
        this.clearError(field, errorEl);
        return true;
    },

    /** Controlla che la data sia valida. */
    date(field, errorEl, msg = 'Data non valida.') {
        const val = field.value;
        if (val && isNaN(Date.parse(val))) {
            this.showError(field, errorEl, msg);
            return false;
        }
        this.clearError(field, errorEl);
        return true;
    },

    showError(field, errorEl, msg) {
        field.classList.add('error');
        if (errorEl) {
            errorEl.textContent = msg;
            errorEl.classList.add('visible');
        }
    },

    clearError(field, errorEl) {
        field.classList.remove('error');
        if (errorEl) errorEl.classList.remove('visible');
    },

    /** Pulisce l'errore quando l'utente modifica il campo. */
    bindClear(fields) {
        fields.forEach(({ field, errorEl }) => {
            if (field) {
                field.addEventListener('input', () => this.clearError(field, errorEl));
                field.addEventListener('change', () => this.clearError(field, errorEl));
            }
        });
    }
};

/**
 * validation.js
 * Funzioni di validazione form riutilizzabili — Terranova
 */

const Validation = {
    /**
     * Valida che un campo non sia vuoto.
     */
    required(field, errorEl, msg = 'Campo obbligatorio.') {
        const val = field.value.trim();
        if (!val) {
            this.showError(field, errorEl, msg);
            return false;
        }
        this.clearError(field, errorEl);
        return true;
    },

    /**
     * Valida formato email.
     */
    email(field, errorEl, msg = 'Email non valida.') {
        const val = field.value.trim();
        if (val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            this.showError(field, errorEl, msg);
            return false;
        }
        this.clearError(field, errorEl);
        return true;
    },

    /**
     * Valida che un valore numerico sia in un certo range.
     */
    numericRange(field, errorEl, min, max, msg = null) {
        const val = parseFloat(field.value);
        if (field.value !== '' && (isNaN(val) || val < min || val > max)) {
            this.showError(field, errorEl, msg || `Valore tra ${min} e ${max}.`);
            return false;
        }
        this.clearError(field, errorEl);
        return true;
    },

    /**
     * Valida che sia selezionato un valore (select/radio).
     */
    selected(field, errorEl, msg = 'Seleziona un\'opzione.') {
        if (!field.value) {
            this.showError(field, errorEl, msg);
            return false;
        }
        this.clearError(field, errorEl);
        return true;
    },

    /**
     * Valida una data.
     */
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

    /**
     * Aggiunge listener per pulire errore all'input.
     */
    bindClear(fields) {
        fields.forEach(({ field, errorEl }) => {
            if (field) {
                field.addEventListener('input', () => this.clearError(field, errorEl));
                field.addEventListener('change', () => this.clearError(field, errorEl));
            }
        });
    }
};

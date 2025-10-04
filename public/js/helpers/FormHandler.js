import { InputValidator } from "./InputValidator.js"

class FormHandler extends InputValidator {
    constructor($form = null) {
        super()
        this.$form = $form
    }

    clearFormErrors() {
        const $errors = this.$form.querySelectorAll('.js-form-error')

        if ($errors.length) {
            $errors.forEach($error => {
                $error.remove()
            })
        }
    }

    addCsrfToFormData() {
        this.formData.append('csrf', csrf)
    }

    displayFormErrors() {
        this.clearFormErrors()

        for (const [name, message] of Object.entries(this.errors)) {
            const $input = this.$form.querySelector(`[name="${name}"]`)
            if (!$input) continue

            const $field = $input.closest('.js-form-field')
            if (!$field) continue

            const $newError = document.createElement('span')
            $newError.classList.add('form__error')
            $newError.classList.add('js-form-error')
            $newError.textContent = message
            $field.append($newError)
        }
    }

    formFilledByBot() {
        const $honeypotInput = this.$form.querySelector('[name="website"]')
        if (!$honeypotInput) return true

        return !$honeypotInput.value == ''
    }
}

export { FormHandler }
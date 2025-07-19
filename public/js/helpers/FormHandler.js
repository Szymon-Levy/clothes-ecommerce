import { InputValidator } from "./InputValidator.js"

const FormHandler = function($form) {
  this.$form = $form
}

// Inheritance from InputValidator
FormHandler.prototype = Object.create(InputValidator.prototype)
FormHandler.prototype.constructor = FormHandler

Object.assign(FormHandler.prototype, {
  clearFormErrors: function() {
    const $errors = this.$form.querySelectorAll('.js-form-error')

    if ($errors.length) {
      $errors.forEach($error => {
        $error.remove()
      })
    }
  },

  addCsrfToFormData: function() {
    this.formData.append('csrf', csrf)
  },

  displayFormErrors: function() {
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
  },

  formFilledByBot: function() {
    const $honeypotInput = this.$form.querySelector('[name="website"]')
    if (!$honeypotInput) return true

    return !$honeypotInput.value == ''
  }
})

export { FormHandler }
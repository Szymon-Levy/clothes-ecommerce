import { InputValidator } from "./InputValidator.js"

const FormHelper = function($form) {
  this.$form = $form
}

// Inheritance from InputValidator
FormHelper.prototype = Object.create(InputValidator.prototype)

FormHelper.prototype.clearFormErrors = function() {
  const $errors = this.$form.querySelectorAll('.js-form-error')

  if ($errors.length) {
    $errors.forEach($error => {
      $error.remove()
    })
  }
}

FormHelper.prototype.addCsrfToFormData = function(formData) {
  formData.append('csrf', csrf)
}

FormHelper.prototype.displayFormErrors = function(errors) {
  this.clearFormErrors()

  for (const [name, message] of Object.entries(errors)) {
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

FormHelper.prototype.formFilledByBot = () => {
  $honeypotInput = this.$form.querySelector('[name="website"]')
  if (!$honeypotInput) return true

  return !$honeypotInput.value == ''
}

export { FormHelper }
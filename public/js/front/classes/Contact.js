import { FormHandler } from "../../helpers/FormHandler.js"
import { uiController } from '../classes/UiController.js'

const Contact = function() {}

Contact.prototype.handleContactFormResponse = function () {
  this.clearFormErrors()

  if (this.response.hasOwnProperty('success')) {
      uiController.showAlert(this.response.success, 'success')
      this.$form.reset()
  }
  else if (this.response.hasOwnProperty('error')) {
      uiController.showAlert(this.response.error, 'error')
  }
  else {
    this.displayFormErrors(this.response)
  }
}

Contact.prototype.sendContactRequest = async function () {
  try {
    const request = await fetch(docRoot + 'ajax/contact-send-message', {
      method: 'POST',
      body: this.formData
    })
    this.response = await request.json()
    return true
  } catch(error) {
    showAlert('Server error. The administrator has been informed of the error.', 'error')
    return false
  }
}

Contact.prototype.validateContactForm = function () {
  const name = this.formData.get('name').trim()
  const email = this.formData.get('email').trim()
  const subject = this.formData.get('subject')
  const message = this.formData.get('message')
  const policy = this.formData.get('policy')
  const errors = {}

  const nameError = this.lengthValidation(name, 'Name', 2, 50, true)
  const subjectValues = [
    'Shipping & Delivery',
    'Returns & Exchanges',
    'Payment Issues',
    'Technical Support',
    'Account Management',
    'Other'
  ]
  const subjectError = this.multiValuesValidation(subject, 'Subject', subjectValues, true)
  const emailError = this.emailValidation(email, true)
  const messageError = this.lengthValidation(message, 'Message', 15, 200, true)

  if (nameError) {
    errors.name = nameError
  }

  if (subjectError) {
    errors.subject = subjectError
  }

  if (emailError) {
    errors.email = emailError
  }

  if (messageError) {
    errors.message = messageError
  }
  
  if (!policy) {
    errors.policy = 'Accepting privacy policy is required!'
  }

  return errors
}

Contact.prototype.handleContactForm = async function() {
  this.contactForm.formData = new FormData(this.contactForm.$form)
  this.contactForm.addCsrfToFormData()

  const errors = this.validateContactForm.call(this.contactForm)

  
  if (Object.keys(errors).length === 0) {
    await this.sendContactRequest.call(this.contactForm)
    if (!this.contactForm.response) return
    this.handleContactFormResponse.call(this.contactForm)
  }
  else {
    this.contactForm.displayFormErrors(errors)
  }
}

Contact.prototype.initContactForm = function() {
  const $contactForm = document.querySelector('.js-contact-form')
  if (!$contactForm) return

  this.contactForm = new FormHandler($contactForm)

  $contactForm.addEventListener('submit', e => {
    e.preventDefault()

    if (this.contactForm.formFilledByBot()) return

    this.handleContactForm()
  })
}

export { Contact }
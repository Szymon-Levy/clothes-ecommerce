import { FormHandler } from "../../helpers/FormHandler.js"
import { uiController } from '../classes/UiController.js'

const Newsletter = function() {}

Newsletter.prototype.initNewsletterForm = function() {
  this.newsletterForm = null

  document.addEventListener('submit', e => {
    const $newsletterForm = e.target.closest('.js-newsletter-form')
    if ($newsletterForm) {
      e.preventDefault()
      
      if (!this.newsletterForm) {
        this.newsletterForm = new FormHandler($newsletterForm)
      }

      if (this.newsletterForm.formFilledByBot()) return
      this.handleNewsletterForm()
    }
  })
}

Newsletter.prototype.handleNewsletterForm = async function() {
  this.newsletterForm.formData = new FormData(this.newsletterForm.$form)
  this.newsletterForm.addCsrfToFormData()
  this.validateNewsletterForm.call(this.newsletterForm)

  if (Object.keys(this.newsletterForm.errors).length) {
    this.newsletterForm.displayFormErrors()
    return
  }

  await this.sendNewsletterRequest.call(this.newsletterForm)
  if (!this.newsletterForm.response) return
  this.handleNewsletterFormResponse.call(this.newsletterForm)
}

Newsletter.prototype.validateNewsletterForm = function() {
  const name = this.formData.get('name').trim()
  const email = this.formData.get('email').trim()
  const policy = this.formData.get('policy')
  this.errors = {}

  const nameError = this.lengthValidation(name, 'Name', 2, 50, true)
  const emailError = this.emailValidation(email, true)

  if (nameError) {
    this.errors.name = nameError
  }

  if (emailError) {
    this.errors.email = emailError
  }
  
  if (!policy) {
    this.errors.policy = 'Accepting privacy policy is required!'
  }
}

Newsletter.prototype.sendNewsletterRequest = async function() {
  try {
    const request = await fetch(docRoot + 'ajax/newsletter-subscribe', {
      method: 'POST',
      body: this.formData
    })
    this.response = await request.json()
    return true
  } catch(error) {
    showAlert('Server error. Try again and if the problem persists please notify the administrator: admin@clothes-ecommerce.com.pl.', 'error')
    return false
  }
}

Newsletter.prototype.handleNewsletterFormResponse = function () {
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

export { Newsletter }
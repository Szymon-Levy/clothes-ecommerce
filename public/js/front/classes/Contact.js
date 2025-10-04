import { FormHandler } from "../../helpers/FormHandler.js"
import { uiController } from '../classes/UiController.js'

class Contact {
    constructor() {
        const init = () => {
            this.initContactForm()
        }

        init()
    }

    // SEND MESSAGE FROM CONTACT FORM

    initContactForm() {
        const $contactForm = document.querySelector('.js-contact-form')
        if (!$contactForm) return

        this.contactForm = new FormHandler($contactForm)

        $contactForm.addEventListener('submit', e => {
            e.preventDefault()

            if (this.contactForm.formFilledByBot()) return
            this.handleContactForm()
        })
    }

    async handleContactForm() {
        this.contactForm.formData = new FormData(this.contactForm.$form)
        this.contactForm.addCsrfToFormData()
        this.validateContactForm.call(this.contactForm)

        if (Object.keys(this.contactForm.errors).length) {
            this.contactForm.displayFormErrors()
            return
        }

        await this.sendContactRequest.call(this.contactForm)
        if (!this.contactForm.response) return
        this.handleContactFormResponse.call(this.contactForm)
    }

    validateContactForm() {
        const name = this.formData.get('name').trim()
        const email = this.formData.get('email').trim()
        const subject = this.formData.get('subject').trim()
        const message = this.formData.get('message').trim()
        const policy = this.formData.get('policy')
        this.errors = {}

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
            this.errors.name = nameError
        }

        if (subjectError) {
            this.errors.subject = subjectError
        }

        if (emailError) {
            this.errors.email = emailError
        }

        if (messageError) {
            this.errors.message = messageError
        }

        if (!policy) {
            this.errors.policy = 'Accepting privacy policy is required!'
        }
    }

    async sendContactRequest() {
        try {
            const request = await fetch(docRoot + 'ajax/contact-send-message', {
                method: 'POST',
                body: this.formData
            })
            this.response = await request.json()
            return true
        } catch (error) {
            uiController.showAlert('Server error. Try again and if the problem persists please notify the administrator: admin@clothes-ecommerce.com.pl.', 'error')
            return false
        }
    }

    handleContactFormResponse() {
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
}

export const contact = new Contact()
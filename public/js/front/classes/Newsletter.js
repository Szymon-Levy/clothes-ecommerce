import { FormHandler } from "../../helpers/FormHandler.js"
import { uiController } from '../classes/UiController.js'

class Newsletter {
    constructor() {
        const init = () => {
            this.initSubscribtionForm()
        }

        init()
    }

    // SUBSCRIBE NEWSLETTER

    initSubscribtionForm() {
        document.addEventListener('submit', e => {
            const $subscribtionForm = e.target.closest('.js-subscribtion-form')

            this.subscribtionForm = null

            if ($subscribtionForm) {
                e.preventDefault()

                if (!this.subscribtionForm) {
                    this.subscribtionForm = new FormHandler($subscribtionForm)
                }

                if (this.subscribtionForm.formFilledByBot()) return
                this.handleSubscribtionForm()
            }
        })
    }

    async handleSubscribtionForm() {
        this.subscribtionForm.formData = new FormData(this.subscribtionForm.$form)
        this.subscribtionForm.addCsrfToFormData()
        this.validateSubscribtionForm.call(this.subscribtionForm)

        if (Object.keys(this.subscribtionForm.errors).length) {
            this.subscribtionForm.displayFormErrors()
            return
        }

        await this.sendSubscribtionRequest.call(this.subscribtionForm)
        if (!this.subscribtionForm.response) return
        this.handleSubscribtionFormResponse.call(this.subscribtionForm)
    }

    validateSubscribtionForm() {
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

    async sendSubscribtionRequest() {
        try {
            const request = await fetch('/newsletter/subscriber/add', {
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

    handleSubscribtionFormResponse() {
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

export const newsletter = new Newsletter()
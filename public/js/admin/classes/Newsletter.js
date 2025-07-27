import { FormHandler } from "../../helpers/FormHandler.js"
import { uiController } from '../classes/UiController.js'

const Newsletter = function() {
  const init = () => {
    this.initAddSubscriberForm()
    this.initEditSubscriberForm()
    this.initDeleteSubscribers()
  }

  init()
}

Newsletter.prototype = {
  constructor: Newsletter,

  // ADD SUBSCRIBER

  initAddSubscriberForm: function() {
    const $addSubscriberForm = document.querySelector('.js-add-subscriber-form')
    if (!$addSubscriberForm) return

    this.addSubscriberForm = new FormHandler($addSubscriberForm)

    $addSubscriberForm.addEventListener('submit', e => {
      e.preventDefault()

      if (this.addSubscriberForm.formFilledByBot()) return
      this.handleAddSubscriberForm()
    })
  },

  handleAddSubscriberForm: async function() {
    this.addSubscriberForm.formData = new FormData(this.addSubscriberForm.$form)
    this.addSubscriberForm.addCsrfToFormData()
    this.validateAddSubscriberForm.call(this.addSubscriberForm)

    if (Object.keys(this.addSubscriberForm.errors).length) {
      this.addSubscriberForm.displayFormErrors()
      return
    }

    await this.sendAddSubscriberRequest.call(this.addSubscriberForm)
    if (!this.addSubscriberForm.response) return
    this.handleAddSubscriberFormResponse.call(this.addSubscriberForm)
  },

  validateAddSubscriberForm: function() {
    const name = this.formData.get('name').trim()
    const email = this.formData.get('email').trim()
    this.errors = {}

    const nameError = this.lengthValidation(name, 'Name', 2, 50, true)
    const emailError = this.emailValidation(email, true)

    if (nameError) {
      this.errors.name = nameError
    }

    if (emailError) {
      this.errors.email = emailError
    }
  },

  sendAddSubscriberRequest: async function() {
    try {
      const request = await fetch(docRoot + 'admin/ajax/newsletter-add-subscriber', {
        method: 'POST',
        body: this.formData
      })
      this.response = await request.json()
      return true
    } catch(error) {
      uiController.showAlert('Server error. Try again and if the problem persists please notify the administrator: admin@clothes-ecommerce.com.pl.', 'error')
      return false
    }
  },

  handleAddSubscriberFormResponse: function () {
    this.clearFormErrors()

    if (this.response.hasOwnProperty('success')) {
        location.href = docRoot + this.response.path
    }
    else if (this.response.hasOwnProperty('error')) {
        uiController.showAlert(this.response.error, 'error')
    }
    else {
      this.displayFormErrors(this.response)
    }
  },

  // EDIT SUBSCRIBER

  initEditSubscriberForm: function() {
    const $editSubscriberForm = document.querySelector('.js-edit-subscriber-form')
    if (!$editSubscriberForm) return

    this.editSubscriberForm = new FormHandler($editSubscriberForm)

    $editSubscriberForm.addEventListener('submit', e => {
      e.preventDefault()

      if (this.editSubscriberForm.formFilledByBot()) return
      this.handleEditSubscriberForm()
    })
  },

  handleEditSubscriberForm: async function() {
    this.editSubscriberForm.formData = new FormData(this.editSubscriberForm.$form)
    this.editSubscriberForm.addCsrfToFormData()
    const validate = this.validateEditSubscriberForm.call(this.editSubscriberForm)
    if (validate === false) return false

    if (Object.keys(this.editSubscriberForm.errors).length) {
      this.editSubscriberForm.displayFormErrors()
      return
    }

    await this.sendEditSubscriberRequest.call(this.editSubscriberForm)
    if (!this.editSubscriberForm.response) return
    this.handleEditSubscriberFormResponse.call(this.editSubscriberForm)
  },

  validateEditSubscriberForm: function() {
    const id = this.formData.get('id')?.trim()
    const name = this.formData.get('name').trim()
    const email = this.formData.get('email').trim()
    this.errors = {}

    const nameError = this.lengthValidation(name, 'Name', 2, 50, true)
    const emailError = this.emailValidation(email, true)

    if (!id) {
      uiController.showAlert('Incomplete form data!', 'error')
      return false
    }

    if (nameError) {
      this.errors.name = nameError
    }

    if (emailError) {
      this.errors.email = emailError
    }
  },

  sendEditSubscriberRequest: async function() {
    try {
      const request = await fetch(docRoot + 'admin/ajax/newsletter-edit-subscriber', {
        method: 'POST',
        body: this.formData
      })

      this.response = await request.json()
      return true
    } catch(error) {
      uiController.showAlert('Server error. Try again and if the problem persists please notify the administrator: admin@clothes-ecommerce.com.pl.', 'error')
      return false
    }
  },

  handleEditSubscriberFormResponse: function () {
    this.clearFormErrors()

    if (this.response.hasOwnProperty('success')) {
        location.href = docRoot + this.response.path
    }
    else if (this.response.hasOwnProperty('error')) {
        uiController.showAlert(this.response.error, 'error')
    }
    else {
      this.displayFormErrors(this.response)
    }
  },

  // DELETE TABLE ITEMS
  prepareDeletionFormData: function(itemsId) {
    const form = new FormHandler()
    form.formData = new FormData()
    form.addCsrfToFormData()
    form.formData.append('ids', JSON.stringify(itemsId))
    return form
  },

  handleDeletionResponse: function() {
    const response = this.deletionResponse

    if (response.hasOwnProperty('success')) {
      uiController.showAlert(response.success, 'success')
      uiController.handleDeletionItemsFromTable(this.deletionItemsIds, response.count)
    }
    else if (response.hasOwnProperty('error')) {
      uiController.showAlert(response.error, 'error')
    }
    else if (response.hasOwnProperty('info')) {
      uiController.showAlert(response.info, 'info')
    }
  },

  // DELETE SUBSCRIBERS

  initDeleteSubscribers: function () {
    const deleteCallback = this.sendDeleteNewsletterItemsRequest.bind(this)
    uiController.setDeleteItemsFromTableHandler(deleteCallback, 'subscriber')
  },

  sendDeleteNewsletterItemsRequest: async function (itemsId) {
    this.deletionItemsIds = itemsId
    this.deleteSubscribersForm = this.prepareDeletionFormData(this.deletionItemsIds)
    let success = false

    try {
      const request = await fetch(docRoot + 'admin/ajax/newsletter-delete-subscribers', {
        method: 'POST',
        body: this.deleteSubscribersForm.formData
      })

      this.deletionResponse = await request.json()
      success = true
    } catch(error) {
      console.log(error)
      uiController.showAlert('Server error. Try again and if the problem persists please notify the administrator: admin@clothes-ecommerce.com.pl.', 'error')
    } finally {
      if (success) {
        this.handleDeletionResponse()
      }
    }
  }
}

export const newsletter = new Newsletter()
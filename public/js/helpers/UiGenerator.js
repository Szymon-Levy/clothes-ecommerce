import { uiController } from '../front/classes/UiController.js'

const UiGenerator = function(type, parameters = {}, trigger = null) {
  this.type = type
  this.parameters = parameters
  this.trigger = trigger
}

UiGenerator.prototype = {
  constructor: UiGenerator,

  render: async function() {
    if(this.inUseState) return
    this.changeInUseState()

    const receivedHtml = await this.getElementHtml()
    if(!receivedHtml) return
    this.executeElementInsertMethod()
  },

  changeInUseState: function() {
    this.inUseState = !this.inUseState
  },

  getElementHtml: async function () {
    const elementUrl = this.getElementUrl()
    if (!elementUrl) return

    if (this.trigger) {
      this.showLoader()
      this.setLoadDelay()
    }

    try {
      const request = await fetch(docRoot + 'ui_elements/' + elementUrl, {
        method: 'POST',
        body: this.getFormdataParameters()
      })

      this.elementHtml = await request.text()
      await this.waitForLoadingHtmlAssets()
      return true
    } catch(error) {
      uiController.showAlert('Server error. Try again and if the problem persists please notify the administrator: admin@clothes-ecommerce.com.pl.', 'error')
      return false
    } finally {
      if (this.trigger) {
        await this.hanldeLoadDelay()
        this.hideLoader()
      }
    }
  },

  getElementUrl: function() {
    switch (this.type) {
      case 'video_popup':
        return 'video_popup'

      case 'newsletter_popup':
        return 'newsletter_popup'
    
      default:
        return false
    }
  },

  executeElementInsertMethod: function() {
    switch (this.type) {
      case 'video_popup':
      case 'newsletter_popup':
        this.addPopupToDOM()
        break
    
      default:
        return false
    }
  },

  getFormdataParameters: function() {
    const formData = new FormData()
    Object.keys(this.parameters).forEach(key => formData.append(key, this.parameters[key]))
    return formData
  },

  showLoader: function() {
    this.trigger.classList.add('ajax-loader')
    this.trigger.innerHTML += '<div class="ajax-loader__bg"><div class="ajax-loader__icon"></div></div>'
  },

  hideLoader: function() {
    this.trigger.classList.remove('ajax-loader')
    this.trigger.querySelector('.ajax-loader__bg')?.remove()
  },

  setLoadDelay: function() {
    this.loadingStart = performance.now()
  },

  hanldeLoadDelay: async function() {
    const duration = performance.now() - this.loadingStart
    const remainingTime = 400 - duration
    if (remainingTime > 0) {
        await new Promise(resolve => setTimeout(resolve, remainingTime))
    }
  },

  addPopupToDOM: function() {
    document.querySelector('.body-wrapper').insertAdjacentHTML('afterend', this.elementHtml)
    document.body.style.overflow = 'hidden'
    this.popupRemover = this.removePopup.bind(this)
    document.addEventListener('click', this.popupRemover)
  },

  removePopup: function(e) {
    const $target = e.target

    if ($target.classList.contains('js-popup-close') || $target.classList.contains('js-popup')) {
      document.removeEventListener('click', this.popupRemover)
      document.querySelector('.js-popup').remove()
      document.body.style.overflow = '';
      this.changeInUseState()
    }
  },

  waitForLoadingHtmlAssets: async function() {
    const temporaryContainer = document.createElement('div')
    temporaryContainer.style.display = 'none'
    temporaryContainer.innerHTML = this.elementHtml
    document.body.appendChild(temporaryContainer)

    const images = temporaryContainer.querySelectorAll('img')
    const videos = temporaryContainer.querySelectorAll('video')

    const assetsLoadingPromises = []

    if (images.length) {
      images.forEach(image => {
        assetsLoadingPromises.push(new Promise(resolve => {
          if (image.complete) {
            resolve()
          }
          else {
            image.addEventListener('load', resolve, {once: true})
            image.addEventListener('error', resolve, {once: true})
          }
        }))
      })
    }

    if (videos.length) {
      videos.forEach(video => {
        assetsLoadingPromises.push(new Promise(resolve => {
          if (video.readyState >= 2) {
            resolve()
          }
          else {
            video.addEventListener('loadeddata', resolve, {once: true})
            video.addEventListener('error', resolve, {once: true})
          }
        }))
      })
    }

    await Promise.all(assetsLoadingPromises)

    temporaryContainer.remove()
  }
}

export { UiGenerator }
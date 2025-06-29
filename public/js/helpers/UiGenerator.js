import { uiController } from '../front/classes/UiController.js'

const UiGenerator = function(type, parameters = {}, trigger = null) {
  this.type = type
  this.parameters = parameters
  this.trigger = trigger
}

UiGenerator.prototype.render = async function() {
  const successfullyGotHtml = await this.getElementHtml()
  if(!successfullyGotHtml) return
  this.executeElementInsertMethod()
}

UiGenerator.prototype.getElementHtml = async function () {
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
    console.log(error)
    uiController.showAlert('Server error. Try again and if the problem persists please notify the administrator: admin@clothes-ecommerce.com.pl.', 'error')
    return false
  } finally {
    if (this.trigger) {
      await this.hanldeLoadDelay()
      this.hideLoader()
    }
  }
}

UiGenerator.prototype.getElementUrl = function() {
  switch (this.type) {
    case 'video_popup':
      return 'video_popup'

    case 'newsletter_popup':
      return 'newsletter_popup'
  
    default:
      return false
  }
}

UiGenerator.prototype.executeElementInsertMethod = function() {
  switch (this.type) {
    case 'video_popup':
    case 'newsletter_popup':
      this.addPopupToDOM()
      break
  
    default:
      return false
  }
}

UiGenerator.prototype.getFormdataParameters = function() {
  const formData = new FormData()
  Object.keys(this.parameters).forEach(key => formData.append(key, this.parameters[key]))
  return formData
}

UiGenerator.prototype.showLoader = function() {
  this.trigger.classList.add('ajax-loader')
  this.trigger.innerHTML += '<div class="ajax-loader__bg"><div class="ajax-loader__icon"></div></div>'
}

UiGenerator.prototype.hideLoader = function() {
  this.trigger.classList.remove('ajax-loader')
  this.trigger.querySelector('.ajax-loader__bg')?.remove()
}

UiGenerator.prototype.setLoadDelay = function() {
  this.loadingStart = performance.now()
}

UiGenerator.prototype.hanldeLoadDelay = async function() {
  const duration = performance.now() - this.loadingStart
  const remainingTime = 800 - duration
  if (remainingTime > 0) {
      await new Promise(resolve => setTimeout(resolve, remainingTime))
  }
}

UiGenerator.prototype.addPopupToDOM = function() {
  document.querySelector('.body-wrapper').insertAdjacentHTML('afterend', this.elementHtml)
  document.body.style.overflow = 'hidden'
  document.addEventListener('click', this.removePopup)
}

UiGenerator.prototype.removePopup = function(e) {
  const $target = e.target

  if ($target.classList.contains('js-popup-close') || $target.classList.contains('js-popup')) {
    document.querySelector('.js-popup').remove()
    document.body.style.overflow = '';
    document.removeEventListener('click', this.removePopup)
  }
}

UiGenerator.prototype.waitForLoadingHtmlAssets = async function() {
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

export { UiGenerator }
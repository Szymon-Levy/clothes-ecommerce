const UiGenerator = function(type, parameters = {}) {
  this.type = type
  this.parameters = parameters
}

UiGenerator.prototype.render = async function() {
  this.elementHtml = await this.getElementHtml()
  this.executeElementInsertMethod()
}

UiGenerator.prototype.getElementHtml = async function () {
  const elementUrl = this.getElementUrl()
  if (!elementUrl) return

  try {
    const request = await fetch(docRoot + 'ui_elements/' + elementUrl, {
      method: 'POST',
      body: this.getFormdataParameters()
    })

    const html = await request.text()
    return html
  } catch(error) {
    showAlert('Server error. The administrator has been informed of the error.', 'error')
    console.log(error)
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
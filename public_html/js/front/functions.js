/**
 * Removes popup
 */
const removePopup = (e) => {
  const $target = e.target

  if ($target.classList.contains('js-popup-close') || $target.classList.contains('js-popup')) {
    document.querySelector('.js-popup').remove()
    document.body.style.overflow = '';
    document.removeEventListener('click', removePopup)
  }
}

/**
 * Creates HTML popup
 */
const addPopupToDOM = (popupContent = '', additionalCssClasses = '') => {
  const popupHtml = `
    <div class="popup ${additionalCssClasses} js-popup">
      <div class="popup__container">
        ${popupContent}
        <button class="popup__close js-popup-close"></button>
      </div>
    </div>
  `

  document.querySelector('.body-wrapper').insertAdjacentHTML('afterend', popupHtml)
  document.body.style.overflow = 'hidden'

  document.addEventListener('click', removePopup)
}

/**
 * Replaces doc root in html content loaded dynamically "|doc_root|" => path
 */
const replaceDocRoot = (string) => {
  return string.replaceAll('|doc_root|', docRoot)
}

/**
 * Shows alert popup
 */
let alertTimeoutId

const showAlert = (message, type) => {
  let $currentAlert = document.querySelector('.js-alert')
  let icon = 'ri-information-line'
  let title = 'Alert'

  if (type == 'success') {
    icon = 'ri-checkbox-circle-line'
    title = 'Success :)'
  }
  else if (type == 'error') {
    icon = 'ri-checkbox-circle-line'
    title = 'Error!'
  }
  else if (type == 'info') {
    icon = 'ri-information-line'
    title = 'Information...'
  }
  else if (type == 'warning') {
    icon = 'ri-spam-3-line'
    title = 'Warning!'
  }

  if ($currentAlert) {
    $currentAlert.removeAttribute('class')
    $currentAlert.classList.add('alert', 'alert--' + type, 'js-alert')

    $currentAlert.querySelector('.js-alert-icon i').removeAttribute('class')
    $currentAlert.querySelector('.js-alert-icon i').classList.add(icon)

    $currentAlert.querySelector('.js-alert-title').innerText = title

    $currentAlert.querySelector('.js-alert-message').innerText = message

    $currentAlert.querySelector('.js-alert-line').classList.remove('shrinking')

    setTimeout(() => {
      $currentAlert.querySelector('.js-alert-line').classList.add('shrinking')
    }, 100);

    clearTimeout(alertTimeoutId)
  }
  else {
    const html = `
      <div class="alert alert--${type} js-alert">
        <span class="alert__line shrinking js-alert-line"></span>
  
        <div class="alert__content">
          <h5 class="alert__title">
            <span class="alert__icon js-alert-icon"><i class="${icon}"></i></span>
            <span class="js-alert-title">${title}</span>
          </h5>
  
          <p class="alert__message js-alert-message">${message}</p>
        </div>
  
        <button class="alert__close" onclick="closeAlert(event)"><i class="ri-close-line"></i></button>
      </div>
    `
  
    document.querySelector('.body-wrapper').insertAdjacentHTML('afterend', html)
    $currentAlert = document.querySelector('.js-alert')
  }

  alertTimeoutId = setTimeout(() => {
    $currentAlert.remove()
  }, 6000);
}

/**
 * Closes alert popup
 */
const closeAlert = (e) => {
  e.target.closest('.js-alert').remove()
}

/**
 * Limits function execution to given time interval
 */

const throttleFunction = (callback, interval = 100) => {
  let isRunning = false
  
  return (...args) => {
    if (!isRunning) {
      isRunning = true

      callback.apply(this, args)

      setTimeout(() => {
        isRunning = false
      }, interval);
    }
  }
}

/*----------------------------------*\
  #FORM HELPERS
\*----------------------------------*/

/**
 * Clears form errors
 */
const clearFormErrors = ($form) => {
  const $errors = $form.querySelectorAll('.js-form-error')

  if ($errors.length) {
    $errors.forEach($error => {
      $error.remove()
    })
  }
}

/**
 * Adds csrf token to formData
 */
const addCsrfToFormData = (formData) => {
  formData.append('csrf', csrf)
}


/**
 * Displays form errors
 */
const displayFormErrors = ($form, errors) => {
  clearFormErrors($form)

  for (const [name, message] of Object.entries(errors)) {
    const $input = $form.querySelector(`[name="${name}"]`)
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


/**
 * Validates email field
 */
const emailValidation = (email, isRequired = false) => {
  const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  if (isRequired && email === '') {
    return 'E-mail address is required!'
  }
  else if (!email.match(regex)) {
    return 'E-mail address format is invalid!'
  }
  else if (email.length > 255) {
    return 'E-mail address cannot be longer than 255 characters!'
  }

  return false
}


/**
 * Validates lenght
 */
const lengthValidation = (value, name, from, to, isRequired = false) => {
  if (isRequired && value === '') {
    return `${name} is required!`
  }
  else if (value !== '' && (value.length < from || value.length > to)) {
    return  `${name} cannot be shorter than ${from} and longer than ${to} characters!`
  }

  return false
}

/**
 * Validates multi values
 */
const multiValuesValidation = (value, name, checkValuesSet, isRequired = false) => {
  if (isRequired && value === '') {
    return `${name} is required!`
  }
  else if (!checkValuesSet.includes(value) && !(value == '')) {
    return  `${name} value is incorrect!`
  }

  return false
}

/**
 * Checks if bot filled form
 */

const formFilledByBot = ($form) => {
  $honeypotInput = $form.querySelector('[name="website"]')
  if (!$honeypotInput) return true

  return !$honeypotInput.value == ''
}
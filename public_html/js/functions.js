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
  document.body.style.overflow = 'hidden';

  document.addEventListener('click', removePopup)
}


/**
 * Replaces doc root in html content loaded dynamically "|doc_root|" => path
 */
const replaceDocRoot = (string) => {
  return string.replaceAll('|doc_root|', docRoot)
}


/*----------------------------------*\
  #FORM VALIDATION
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
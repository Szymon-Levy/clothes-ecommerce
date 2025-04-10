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
  
    document.querySelector('.app-content__panel').insertAdjacentHTML('afterend', html)
    $currentAlert = document.querySelector('.js-alert')
  }

  alertTimeoutId = setTimeout(() => {
    $currentAlert.remove()
  }, 3000);
}


/**
 * Closes alert popup
 */
const closeAlert = (e) => {
  e.target.closest('.js-alert').remove()
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

/*----------------------------------*\
  #TABLE FUNCTIONS
\*----------------------------------*/

/**
 * Updates table numbers and rows after removing its items
 */
rowsToRemove = []

const updateTableAfterRomoveItems = (ids, count) => {
  const callback = () => {
    $totalRows = document.querySelectorAll('.js-table-row')
    if (!$totalRows.length) {
      const params = new URLSearchParams(location.search);
      const page = +params.get('page');
      if (page && page > 1) {
        let newUrl = new URL(location.href)
        newUrl.searchParams.set('page', page - 1)
        location.href = newUrl.toString()
      }
      else {
        const $to = document.querySelector('.js-table-number-to')
        const $of = document.querySelector('.js-table-number-of')
        let to = +$to.innerText
        let of = +$of.innerText
        if (of > to) {
          location.reload()
        }
        else {
          document.querySelector('.js-panel-bottom').remove()
          document.querySelector('.js-table').innerHTML = 'No items found to display'
        }
      }
    }

    const $numbersWrapper = document.querySelector('.js-table-numbers')
    if (!$numbersWrapper) { return false }

    const $from = $numbersWrapper.querySelector('.js-table-number-from')
    const $to = $numbersWrapper.querySelector('.js-table-number-to')
    const $of = $numbersWrapper.querySelector('.js-table-number-of')

    let from = +$from.innerText
    let to = +$to.innerText - count
    let of = +$of.innerText - count

    $to.innerHTML = to >= 0 ? to : 0
    $of.innerHTML = of >= 0 ? of : 0

    if ((from - count) == of || (from - 1) == of) {
      $from.innerHTML = 0
      $to.innerHTML = 0
    }
  }

  ids.forEach(id => {
    $row = document.querySelector(`[data-id="${id}"]`)
    if ($row) {
      rowsToRemove.push($row)
      $row.classList.add('removed')
    }
  })

  setTimeout(() => {
    if (rowsToRemove.length) {
      rowsToRemove.forEach($row => $row.remove())
      rowsToRemove = []
    }

    callback()
  }, 3000)
}

/**
 * Updates mobile sorting form hidden inputs values
 */
const updateSortTableParams = ($form) => {
  console.log($form)
  const $select = $form.querySelector('.js-sort-columns-select')
  const selectedValue = $select.value.split("-")
  $form.querySelector('[name="orderby"]').value = selectedValue[0]
  $form.querySelector('[name="sort"]').value = selectedValue[1]
  $form.submit()
}
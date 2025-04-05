/*----------------------------------*\
  #ASIDE
\*----------------------------------*/

const $aside = document.querySelector('.js-aside')
const $showAsideBtn = document.querySelector('.js-show-aside')
const $hideAsideBtn = document.querySelector('.js-hide-aside')
const $mobileOverlay = document.querySelector('.js-mobile-overlay')

const showAside = () => {
  $aside.classList.add('show')
  $mobileOverlay.classList.add('active')
}

const hideAside = () => {
  $aside.classList.remove('show')
  $mobileOverlay.classList.remove('active')
}

if ($aside) {
  $aside.addEventListener('click', e => {
    const $navItem = e.target.closest('.js-aside-nav-menu-item-title')
    if ($navItem) {
      const $currentlyActiveItem = $aside.querySelector('.js-aside-nav-menu-item-title.show-links')
      if ($currentlyActiveItem && $currentlyActiveItem != $navItem) {
        $currentlyActiveItem.classList.remove('show-links')
      }
      $navItem.classList.toggle('show-links')
    }
  })

  $showAsideBtn.addEventListener('click', showAside)
  $hideAsideBtn.addEventListener('click', hideAside)
  $mobileOverlay.addEventListener('click', hideAside)
}

/*----------------------------------*\
  #TABLE
\*----------------------------------*/

const $selectAllItems = document.querySelectorAll('.js-select-all-items')

const toggleItemsSelection = (toggler) => {
  const $selectItems = document.querySelectorAll('.js-select-item')
  if (!$selectItems) { return false }

  const state = toggler.checked
  $selectAllItems.forEach(select => {
    if (select != toggler) select.checked = state
  })

  if (state) {
    $selectItems.forEach(item => {
      if (!item.checked) {
        item.checked = true
      }
    })
  }
  else {
    $selectItems.forEach(item => {
      if (item.checked) {
        item.checked = false
      }
    })
  }
}

if ($selectAllItems.length) {
  $selectAllItems.forEach(select => {
    select.addEventListener('change', (e) => {
      toggleItemsSelection(e.target)
    })
  })
}

/*----------------------------------*\
  #CLOSE MESSAGE BAR
\*----------------------------------*/

const $messageBarClose = document.querySelector('.js-message-close')

const closeMessageBar = (e) => {
  e.target.closest('.js-message').remove()
}

if ($messageBarClose) {
  $messageBarClose.addEventListener('click', e => {
    closeMessageBar(e)
  })
}

/*----------------------------------*\
  #NEWSLETTER
\*----------------------------------*/

const handleDeleteNewsletterItemsResponse = (response, ids) => {
  if (response.hasOwnProperty('success')) {
      showAlert(response.success, 'success')
      updateTableAfterRomoveItems(ids, response.count)
  }
  else if (response.hasOwnProperty('error')) {
      showAlert(response.error, 'error')
  }
  else if (response.hasOwnProperty('info')) {
      showAlert(response.info, 'info')
  }
}

const sendDeleteNewsletterItemsRequest = async (formData, ids) => {
  try {
    const request = await fetch(docRoot + 'admin/ajax/newsletter-delete-subscribers', {
      method: 'POST',
      body: formData
    })
    const data = await request.json()
    handleDeleteNewsletterItemsResponse(data, ids);
  } catch(error) {
    showAlert('Server error. The administrator has been informed of the error.', 'error')
    console.log(error)
  }
}

/* == DELETE SELECTED ITEMS == */
$deleteSelectedBtn = document.querySelector('.js-delete-selected')

const handleDeleteNewsletterSelected = () => {
  const $selectItems = document.querySelectorAll('.js-select-item')

  if (!$selectItems.length) {
    showAlert('Items to select don\'t exists!', 'error')
    return false;
  }

  const isAnyChecked = [...$selectItems].some(checkbox => checkbox.checked)

  if (!isAnyChecked) {
    showAlert('None of the items are selected!', 'info')
    return false;
  }

  if (!confirm('Are you sure you want to remove selected subscribers?')) { return }

  const ids = []
  $selectItems.forEach(checkbox => {
    if (checkbox.checked) {
      ids.push(checkbox.value)
    }
  })

  const formData = new FormData()
  addCsrfToFormData(formData)
  formData.append('ids', JSON.stringify(ids))
  sendDeleteNewsletterItemsRequest(formData, ids)
}

if ($deleteSelectedBtn) {
  $deleteSelectedBtn.addEventListener('click', handleDeleteNewsletterSelected)
}

/* == DELETE SINGLE ITEM == */
$table = document.querySelector('.js-table')

const handleDeleteNewsletterSingle = ($btn) => {
  id = $btn.closest('.js-table-row')?.dataset.id
  if (id) {
    const formData = new FormData()
    addCsrfToFormData(formData)
    formData.append('ids', JSON.stringify([id]))
    sendDeleteNewsletterItemsRequest(formData, [id])
  }
  else {
    showAlert('Subscriber deletion failed.', 'error')
  }
}

if ($table) {
  $table.addEventListener('click', e => {
    $btn = e.target.closest('.js-newsletter-subscriber-delete')

    if ($btn) {
      if (confirm('Are you sure you want to remove this subscriber?')) {
        handleDeleteNewsletterSingle($btn)
      }
    }
  })
}

/* == ADD SUBSCRIBER == */
const $addSubscriberForm = document.querySelector('.js-add-subscriber-form')

const handleaddSubscriberFormResponse = ($form, response) => {
  clearFormErrors($form)

  if (response.hasOwnProperty('success')) {
      location.href = docRoot + response.path
  }
  else if (response.hasOwnProperty('error')) {
      showAlert(response.error, 'error')
  }
  else {
    displayFormErrors($form, response)
  }
}

const sendAddSubscriberRequest = async ($form, formData) => {
  try {
    const request = await fetch(docRoot + 'admin/ajax/newsletter-add-subscriber', {
      method: 'POST',
      body: formData
    })
    const data = await request.json()
    handleaddSubscriberFormResponse($form, data);
  } catch(error) {
    showAlert('Server error. The administrator has been informed of the error.', 'error')
    console.log(error)
  }
}

const validateaddSubscriberForm = (formData) => {
  const name = formData.get('name').trim()
  const email = formData.get('email').trim()
  const errors = {}

  const nameError = lengthValidation(name, 'Name', 2, 50, true)
  const emailError = emailValidation(email, true)

  if (nameError) {
    errors.name = nameError
  }

  if (emailError) {
    errors.email = emailError
  }

  return errors
}

const handleaddSubscriberForm = ($addSubscriberForm) => {
  const formData = new FormData($addSubscriberForm)
  addCsrfToFormData(formData)
  const errors = validateaddSubscriberForm(formData)

  if (Object.keys(errors).length === 0) {
    sendAddSubscriberRequest($addSubscriberForm, formData)
  }
  else {
    displayFormErrors($addSubscriberForm, errors)
  }
}

if ($addSubscriberForm) {
  $addSubscriberForm.addEventListener('submit', e => {
    e.preventDefault()
    const $form = e.target
    if (formFilledByBot($form)) return false

    handleaddSubscriberForm($form)
  })
}

/* == EDIT SUBSCRIBER == */
const $editSubscriberForm = document.querySelector('.js-edit-subscriber-form')

const handleeditSubscriberFormResponse = ($form, response) => {
  clearFormErrors($form)

  if (response.hasOwnProperty('success')) {
      location.href = docRoot + response.path
  }
  else if (response.hasOwnProperty('error')) {
      showAlert(response.error, 'error')
  }
  else {
    displayFormErrors($form, response)
  }
}

const sendEditSubscriberRequest = async ($form, formData) => {
  try {
    const request = await fetch(docRoot + 'admin/ajax/newsletter-edit-subscriber', {
      method: 'POST',
      body: formData
    })
    const data = await request.json()
    handleeditSubscriberFormResponse($form, data);
  } catch(error) {
    showAlert('Server error. The administrator has been informed of the error.', 'error')
    console.log(error)
  }
}

const validateEditSubscriberForm = (formData) => {
  const id = formData.get('id').trim()
  const email = formData.get('email').trim()
  const name = formData.get('name').trim()
  const errors = {}

  const nameError = lengthValidation(name, 'Name', 2, 50, true)
  const emailError = emailValidation(email, true)

  if (id == '') {
    return false
  }

  if (nameError) {
    errors.name = nameError
  }

  if (emailError) {
    errors.email = emailError
  }

  return errors
}

const handleeditSubscriberForm = ($editSubscriberForm) => {
  const formData = new FormData($editSubscriberForm)
  addCsrfToFormData(formData)
  const errors = validateEditSubscriberForm(formData)
  if (errors === false) return false

  if (Object.keys(errors).length === 0) {
    sendEditSubscriberRequest($editSubscriberForm, formData)
  }
  else {
    displayFormErrors($editSubscriberForm, errors)
  }
}

if ($editSubscriberForm) {
  $editSubscriberForm.addEventListener('submit', e => {
    e.preventDefault()
    const $form = e.target
    if (formFilledByBot($form)) return false

    handleeditSubscriberForm($form)
  })
}
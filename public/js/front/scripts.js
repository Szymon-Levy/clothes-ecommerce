/*----------------------------------*\
  #HOME
\*----------------------------------*/

// Video popup create
const $videoPopupButton = document.querySelector('.js-video-popup-button')

if ($videoPopupButton) {
  const videoPopup = new App.UiGenerator('video_popup', {
    video_name: 'popup-video.mp4'
  })

  $videoPopupButton.addEventListener('click', () => {
    videoPopup.render()
  })
}

// Newsletter popup create
const $newsletterCtaButton = document.querySelector('.js-newsletter-cta-button')

if ($newsletterCtaButton) {
  const newsletterPopup = new App.UiGenerator('newsletter_popup')

  $newsletterCtaButton.addEventListener('click', () => {
    newsletterPopup.render()
  })
}

/*----------------------------------*\
  #NEWSLETTER FORM
\*----------------------------------*/

const handleNewsletterFormResponse = ($form, response) => {
  clearFormErrors($form)

  if (response.hasOwnProperty('success')) {
      showAlert(response.success, 'success')
      $form.reset()
  }
  else if (response.hasOwnProperty('error')) {
      showAlert(response.error, 'error')
      $form.reset()
  }
  else {
    displayFormErrors($form, response)
  }
}

const sendNewsletterRequest = async ($form, formData) => {
  try {
    const request = await fetch(docRoot + 'ajax/newsletter-subscribe', {
      method: 'POST',
      body: formData
    })
    const data = await request.json()
    handleNewsletterFormResponse($form, data);
  } catch(error) {
    showAlert('Server error. The administrator has been informed of the error.', 'error')
    console.log(error)
  }
}

const validateNewsletterForm = (formData) => {
  const name = formData.get('name').trim()
  const email = formData.get('email').trim()
  const policy = formData.get('policy')
  const errors = {}

  const nameError = lengthValidation(name, 'Name', 2, 50, true)
  const emailError = emailValidation(email, true)

  if (nameError) {
    errors.name = nameError
  }

  if (emailError) {
    errors.email = emailError
  }
  
  if (!policy) {
    errors.policy = 'Accepting privacy policy is required!'
  }

  return errors
}

const handleNewsletterForm = ($newsletterForm) => {
  const formData = new FormData($newsletterForm)
  addCsrfToFormData(formData)
  const errors = validateNewsletterForm(formData)

  if (Object.keys(errors).length === 0) {
    sendNewsletterRequest($newsletterForm, formData)
  }
  else {
    displayFormErrors($newsletterForm, errors)
  }
}

const newsletterFormSubmit = (e) => {
  e.preventDefault()
  const $form = e.target
  if (formFilledByBot($form)) return false

  handleNewsletterForm($form)
}

/*----------------------------------*\
  #CONTACT FORM
\*----------------------------------*/
const $contactForm = document.querySelector('.js-contact-form')

const handleContactFormResponse = ($form, response) => {
  clearFormErrors($form)

  if (response.hasOwnProperty('success')) {
      showAlert(response.success, 'success')
      $form.reset()
  }
  else if (response.hasOwnProperty('error')) {
      showAlert(response.error, 'error')
      $form.reset()
  }
  else {
    displayFormErrors($form, response)
  }
}

const sendContactRequest = async ($form, formData) => {
  try {
    const request = await fetch(docRoot + 'ajax/contact-send-message', {
      method: 'POST',
      body: formData
    })
    const data = await request.json()
    handleContactFormResponse($form, data);
  } catch(error) {
    showAlert('Server error. The administrator has been informed of the error.', 'error')
    console.log(error)
  }
}

const validateContactForm = (formData) => {
  const name = formData.get('name').trim()
  const email = formData.get('email').trim()
  const subject = formData.get('subject')
  const message = formData.get('message')
  const policy = formData.get('policy')
  const errors = {}

  const nameError = lengthValidation(name, 'Name', 2, 50, true)
  const subjectValues = [
    'Shipping & Delivery',
    'Returns & Exchanges',
    'Payment Issues',
    'Technical Support',
    'Account Management',
    'Other'
  ]
  const subjectError = multiValuesValidation(subject, 'Subject', subjectValues, true)
  const emailError = emailValidation(email, true)
  const messageError = lengthValidation(message, 'Message', 15, 200, true)

  if (nameError) {
    errors.name = nameError
  }

  if (subjectError) {
    errors.subject = subjectError
  }

  if (emailError) {
    errors.email = emailError
  }

  if (messageError) {
    errors.message = messageError
  }
  
  if (!policy) {
    errors.policy = 'Accepting privacy policy is required!'
  }

  return errors
}

const handleContactForm = ($contactForm) => {
  const formData = new FormData($contactForm)
  addCsrfToFormData(formData)
  const errors = validateContactForm(formData)

  if (Object.keys(errors).length === 0) {
    sendContactRequest($contactForm, formData)
  }
  else {
    displayFormErrors($contactForm, errors)
  }
}

if ($contactForm) {
  $contactForm.addEventListener('submit', e => {
    e.preventDefault()
    const $form = e.target
    if (formFilledByBot($form)) return false

    handleContactForm($form)
  })
}
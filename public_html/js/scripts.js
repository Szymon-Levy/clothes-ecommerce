/*----------------------------------*\
  #SET SIDE SPACE
\*----------------------------------*/
function setSideSpace (){
  const sideSpace = Math.floor(document.body.clientWidth / 8)

  const $root = document.documentElement
  $root.style.setProperty('--side-space-dynamic', sideSpace + 'px')
}

setSideSpace()

window.addEventListener('resize', function(){
  setSideSpace()
})


/*----------------------------------*\
  #HIDE PRELOADER
\*----------------------------------*/
const $preloader = document.querySelector('.js-preloader')
const isPreloader = true
let animationDelay = 0

if ($preloader){
  setTimeout(() => {
    $preloader.classList.add('active')
  }, 50)
}


/*----------------------------------*\
  #OFFCANVAS TOGGLE
\*----------------------------------*/
const $offcanvasMenu = document.querySelector('.js-offcanvas'),
      $offcanvasOpen = document.querySelector('.js-offcanvas-open'),
      $offcanvasClose = document.querySelector('.js-offcanvas-close')

const openOffcanvas = () => {
  $offcanvasMenu.classList.toggle('active')
  $offcanvasMenu.classList.toggle('inactive')
  document.body.style.overflow = 'hidden'
}

const closeOffcanvas = () => {
  $offcanvasMenu.classList.toggle('active')
  $offcanvasMenu.classList.toggle('inactive')
  document.body.style.overflow = ''
}

if ($offcanvasMenu && $offcanvasOpen && $offcanvasClose) {
  $offcanvasMenu.classList.add('inactive')
  $offcanvasOpen.addEventListener('click', openOffcanvas)
  $offcanvasClose.addEventListener('click', closeOffcanvas)
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
  #VIDEO POPUP
\*----------------------------------*/
const $videoPopupButton = document.querySelector('.js-video-popup-button')

const openVideoPopup = async (src) => {
  const response = await fetch(docRoot + 'templates/video_popup.html')
  let popupHTML = await response.text()
  popupHTML = popupHTML.replace('videoSrc', src)
  addPopupToDOM(popupHTML, 'video-popup-wrapper')
}

if ($videoPopupButton) {
  const src = $videoPopupButton.dataset.src

  $videoPopupButton.addEventListener('click', () => {
    openVideoPopup(src)
  })
}


/*----------------------------------*\
  #NEWSLETTER POPUP
\*----------------------------------*/
const $newsletterCtaButton = document.querySelector('.js-newsletter-cta-button')

const openNewsletterPopup = async () => {
  const response = await fetch(docRoot + 'templates/newsletter_popup.html?v=008')
  let popupHTML = await response.text()
  popupHTML = replaceDocRoot(popupHTML)
  addPopupToDOM(popupHTML, 'newsletter-popup animate')
}

if ($newsletterCtaButton) {
  $newsletterCtaButton.addEventListener('click', openNewsletterPopup)
}

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
    const request = await fetch(docRoot + 'ajax/newsletter', {
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
    const request = await fetch(docRoot + 'ajax/contact', {
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
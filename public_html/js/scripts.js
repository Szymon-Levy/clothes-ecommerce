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
  const response = await fetch(docRoot + 'templates/newsletter_popup.html?v=005')
  let popupHTML = await response.text()
  popupHTML = replaceDocRoot(popupHTML)
  addPopupToDOM(popupHTML, 'newsletter-popup animate')
}

if ($newsletterCtaButton) {
  $newsletterCtaButton.addEventListener('click', openNewsletterPopup)
}

const handleNewsletterFormResponse = ($form, response) => {
  clearFormErrors($form)

  console.log(response)
  if (response.hasOwnProperty('success')) {
      // showAlert(response.success, 'success')
      $form.reset()
  }
  else if (response.hasOwnProperty('error')) {
      // showAlert(response.error, 'error')
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
    console.log(error)
  }
}

const validateNewsletterForm = (formData) => {
  const email = formData.get('email').trim()
  const policy = formData.get('policy')
  const errors = {}

  if (emailValidation(email, true)) {
    errors.email = emailValidation(email, true)
  }
  
  if (!policy) {
    errors.policy = 'Accepting privacy policy is required!'
  }

  return errors
}

const handleNewsletterForm = ($newsletterForm) => {
  const formData = new FormData($newsletterForm)
  const errors = validateNewsletterForm(formData)

  sendNewsletterRequest($newsletterForm, formData)
  // if (Object.keys(errors).length === 0) {
  //   sendNewsletterRequest($newsletterForm, formData)
  // }
  // else {
  //   displayFormErrors($newsletterForm, errors)
  // }

}

const newsletterFormSubmit = (e) => {
  e.preventDefault()
  handleNewsletterForm(e.target)
}
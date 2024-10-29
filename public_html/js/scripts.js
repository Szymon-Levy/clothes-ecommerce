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
  const response = await fetch(docRoot + 'templates/newsletter_popup.html?v=001')
  let popupHTML = await response.text()
  popupHTML = replaceDocRoot(popupHTML)
  addPopupToDOM(popupHTML, 'newsletter-popup animate')
}

if ($newsletterCtaButton) {
  $newsletterCtaButton.addEventListener('click', openNewsletterPopup)
}
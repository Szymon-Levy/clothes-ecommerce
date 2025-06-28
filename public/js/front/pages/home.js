import { UiGenerator } from "../../helpers/UiGenerator.js"

// Video popup create
const $videoPopupButton = document.querySelector('.js-video-popup-button')

if ($videoPopupButton) {
  const videoPopup = new UiGenerator('video_popup', {
    video_name: 'popup-video.mp4'
  })

  $videoPopupButton.addEventListener('click', () => {
    videoPopup.render()
  })
}

// Newsletter popup create
const $newsletterCtaButton = document.querySelector('.js-newsletter-cta-button')

if ($newsletterCtaButton) {
  const newsletterPopup = new UiGenerator('newsletter_popup')

  $newsletterCtaButton.addEventListener('click', () => {
    newsletterPopup.render()
  })
}
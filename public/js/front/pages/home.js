import { UiGenerator } from "../../helpers/UiGenerator.js"
import { Newsletter } from "../classes/Newsletter.js"

// VIDEO GENERATE POPUP
const $videoPopupButton = document.querySelector('.js-video-popup-button')

if ($videoPopupButton) {
  const videoPopup = new UiGenerator('video_popup', {
    video_name: 'popup-video.mp4'
  })

  $videoPopupButton.addEventListener('click', () => {
    videoPopup.render()
  })
}

// NEWSLETTER GENERATE POPUP
const $newsletterCtaButton = document.querySelector('.js-newsletter-cta-button')

if ($newsletterCtaButton) {
  const newsletterPopup = new UiGenerator('newsletter_popup')

  $newsletterCtaButton.addEventListener('click', () => {
    newsletterPopup.render()
  })
}

// HANDLE NEWSLETTER FORM
const newsletter = new Newsletter()
newsletter.initNewsletterForm()
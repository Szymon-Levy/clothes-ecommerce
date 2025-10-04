import { UiGenerator } from "../classes/UiGenerator.js"

// Newsletter instance
import { newsletter } from "../classes/Newsletter.js"

// VIDEO GENERATE POPUP
const $videoPopupButton = document.querySelector('.js-video-popup-button')

if ($videoPopupButton) {
    const videoPopup = new UiGenerator('video_popup', {
        video_name: 'popup-video.mp4'
    }, $videoPopupButton)

    $videoPopupButton.addEventListener('click', () => {
        videoPopup.render()
    })
}

// NEWSLETTER GENERATE POPUP
const $newsletterCtaButton = document.querySelector('.js-newsletter-cta-button')

if ($newsletterCtaButton) {
    const newsletterPopup = new UiGenerator('newsletter_popup', {}, $newsletterCtaButton)

    $newsletterCtaButton.addEventListener('click', () => {
        newsletterPopup.render()
    })
}
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
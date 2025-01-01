/*----------------------------------*\
  #ASIDE
\*----------------------------------*/

const $aside = document.querySelector('.js-aside')
const $showAsideBtn = document.querySelector('.js-show-aside')
const $hideAsideBtn = document.querySelector('.js-hide-aside')

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
}

if ($showAsideBtn && $hideAsideBtn && $aside) {
  $showAsideBtn.addEventListener('click', () => {
    $aside.classList.add('show')
  })

  $hideAsideBtn.addEventListener('click', () => {
    $aside.classList.remove('show')
  })
}
/*----------------------------------*\
  #ASIDE
\*----------------------------------*/

const $aside = document.querySelector('.js-aside')

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
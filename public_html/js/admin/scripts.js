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

/*----------------------------------*\
  #TABLE
\*----------------------------------*/

const $selectAllItems = document.querySelector('.js-select-all-items')

const toggleItemsSelection = (toggler) => {
  const $selectItems = document.querySelectorAll('.js-select-item')
  if (!$selectItems) { return false }

  const state = toggler.checked

  if (state) {
    $selectItems.forEach(item => {
      if (!item.checked) {
        item.checked = true
      }
    })
  }
  else {
    $selectItems.forEach(item => {
      if (item.checked) {
        item.checked = false
      }
    })
  }
}

if ($selectAllItems) {
  $selectAllItems.addEventListener('change', (e) => {
    toggleItemsSelection(e.target)
  })
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
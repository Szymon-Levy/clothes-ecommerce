const UiController = function() {
  this.$htmlElement = document.documentElement

  this.$preloader = document.querySelector('.js-preloader')

  this.$offcanvasMenu = document.querySelector('.js-offcanvas')
  this.$offcanvasOpen = document.querySelector('.js-offcanvas-open')
  this.$offcanvasClose = document.querySelector('.js-offcanvas-close')

  this.$messageClose = document.querySelector('.js-message-close')

  this.$header = document.querySelector('.js-header')
  this.isHeaderSticky = false
}

UiController.prototype.init = function() {
  this.initSettingSideSpace()
  this.handlePreloader()
  this.initOffcanvas()
  this.initCloseMessageBox()
  this.initStickyHeader()
}

// SET SIDE SPACE

UiController.prototype.setSideSpace = function() {
  const sideSpace = Math.floor(document.body.clientWidth / 8)

  this.$htmlElement.style.setProperty('--side-space-dynamic', sideSpace + 'px')
}

UiController.prototype.initSettingSideSpace = function() {
  this.setSideSpace()

  const setSideSpaceHandler = throttleFunction(this.setSideSpace.bind(this), 100)
  window.addEventListener('resize', setSideSpaceHandler)
}

// SET PRELOADER ANIMATION

UiController.prototype.handlePreloader = function() {
  if(!this.$preloader) return

  this.$preloader.style.display = 'block'

  setTimeout(() => {
    this.$preloader.classList.add('active')
  }, 50)
}

// TOGGLE OFFCANVAS

UiController.prototype.openOffcanvas = function() {
  this.$offcanvasMenu.classList.toggle('active')
  this.$offcanvasMenu.classList.toggle('inactive')
  document.body.style.overflow = 'hidden'
}

UiController.prototype.closeOffcanvas = function() {
  this.$offcanvasMenu.classList.toggle('active')
  this.$offcanvasMenu.classList.toggle('inactive')
  document.body.style.overflow = ''
}

UiController.prototype.initOffcanvas = function() {
  this.$offcanvasMenu.classList.add('inactive')
  this.$offcanvasOpen.addEventListener('click', this.openOffcanvas.bind(this))
  this.$offcanvasClose.addEventListener('click', this.closeOffcanvas.bind(this))
}

// CLOSE MESSAGE BOX

UiController.prototype.closeMessageBox = function(e) {
  e.target.closest('.js-message').remove()
}

UiController.prototype.initCloseMessageBox = function() {
  if (!this.$messageClose) return

  this.$messageClose.addEventListener('click', this.closeMessageBox)
}

// STICKY HEADER

UiController.prototype.stickyHeader = function() {
  const Y = window.scrollY
  const stickynessDistance = window.innerHeight * 1.5

  if (Y > stickynessDistance) {
    this.$header.classList.add('sticky')
    this.$header.classList.remove('unsticky')

    this.isHeaderSticky = true
  }
  else if (this.isHeaderSticky) {
    this.$header.classList.remove('sticky')
    this.$header.classList.add('unsticky')

    const onAnimationEnd = () => {
      this.isHeaderSticky = false
      this.$header.classList.remove('unsticky')
      this.$header.removeEventListener('animationend', onAnimationEnd)
    }

    this.$header.addEventListener('animationend', onAnimationEnd)
  }
}

UiController.prototype.initStickyHeader = function() {
  this.stickyHeader()

  const handleStickyHeaderScroll = throttleFunction(this.stickyHeader.bind(this), 100)
  window.addEventListener('scroll', handleStickyHeaderScroll)
}

export { UiController }
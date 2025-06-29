const UiController = function() {
  const init = ()=> {
    this.declareInitialVariables()
    this.initSettingSideSpace()
    this.handlePreloader()
    this.initOffcanvas()
    this.initCloseMessageBox()
    this.initStickyHeader()
    this.initClosingAlertListener()
  }

  init()
}

UiController.prototype.declareInitialVariables = function() {
  this.$htmlElement = document.documentElement
  this.$preloader = document.querySelector('.js-preloader')
  this.$offcanvasMenu = document.querySelector('.js-offcanvas')
  this.$offcanvasOpen = document.querySelector('.js-offcanvas-open')
  this.$offcanvasClose = document.querySelector('.js-offcanvas-close')
  this.$messageClose = document.querySelector('.js-message-close')
  this.$header = document.querySelector('.js-header')
  this.isHeaderSticky = false
  this.alertTimeoutId
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

  const handleStickyHeaderScroll = throttleFunction(this.stickyHeader.bind(this), 30)
  window.addEventListener('scroll', handleStickyHeaderScroll)
}

// ALERTS

UiController.prototype.showAlert = function(message, type) {
  this.alertMessage = message
  this.alertType = type
  this.$currentAlert = document.querySelector('.js-alert')

  const alertTypes = {
    success: {icon: 'ri-checkbox-circle-line', title: 'Success :)'},
    error: {icon: 'ri-checkbox-circle-line', title: 'Error!'},
    info: {icon: 'ri-information-line', title: 'Information...'},
    warning: {icon: 'ri-spam-3-line', title: 'Warning!'}
  }

  const {icon, title} = alertTypes[type] || alertTypes.info
  this.alertIcon = icon
  this.alertTitle = title

  clearTimeout(this.alertTimeoutId)

  if (this.$currentAlert) this.closeAlert()

  this.createAlertInDOM()

  this.alertTimeoutId = setTimeout(() => this.closeAlert(), 6000)
}

UiController.prototype.createAlertInDOM = function() {
  const alert = document.createElement('div')
  alert.className = `alert alert--show alert--${this.alertType} js-alert`

  const line = document.createElement('span')
  line.className = 'alert__line shrinking js-alert-line'
  alert.append(line)

  const content = document.createElement('div')
  content.className = 'alert__content'
  const titleWrapper = document.createElement('h5')
  titleWrapper.className = 'alert__title'
  const iconWrapper = document.createElement('span')
  iconWrapper.className = 'alert__icon js-alert-icon'
  const icon = document.createElement('i')
  icon.className = this.alertIcon
  iconWrapper.append(icon)
  titleWrapper.append(iconWrapper)
  const title = document.createElement('span')
  title.className = 'js-alert-title'
  title.textContent = this.alertTitle
  titleWrapper.append(title)
  content.append(titleWrapper)
  const message = document.createElement('p')
  message.className = 'alert__message js-alert-message'
  message.textContent = this.alertMessage
  content.append(message)
  alert.append(content)

  const closeBtn = document.createElement('button')
  closeBtn.className = 'alert__close js-alert-close'
  const closeBtnIcon = document.createElement('i')
  closeBtnIcon.className = 'ri-close-line'
  closeBtn.append(closeBtnIcon)
  alert.append(closeBtn)

  this.$currentAlert = alert
  document.querySelector('.body-wrapper').insertAdjacentElement('afterend', alert)
}

UiController.prototype.closeAlert = function(e) {
  this.$currentAlert.remove()
}

UiController.prototype.initClosingAlertListener = function() {
  document.addEventListener('click', e => {
    if (e.target.closest('.js-alert-close')) {
      this.closeAlert()
    }
  })
}

export const uiController = new UiController()
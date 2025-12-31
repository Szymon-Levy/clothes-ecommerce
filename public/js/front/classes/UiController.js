class UiController {
    constructor() {
        const init = () => {
            this.initSettingSideSpace()
            this.initPreloader()
            this.initOffcanvas()
            this.initMessageBox()
            this.initHeader()
            this.initAlert()
        }

        init()
    }

    // SET SIDE SPACE

    initSettingSideSpace() {
        this.setSideSpace()

        const setSideSpaceHandler = throttleFunction(this.setSideSpace.bind(this), 100)
        window.addEventListener('resize', setSideSpaceHandler)
    }

    setSideSpace() {
        const sideSpace = Math.floor(document.body.clientWidth / 8)

        document.documentElement.style.setProperty('--side-space-dynamic', sideSpace + 'px')
    }

    // PRELOADER ANIMATION

    initPreloader() {
        this.$preloader = document.querySelector('.js-preloader')
        if (!this.$preloader) return

        this.animatePreloader()
    }

    animatePreloader() {
        setTimeout(() => {
            this.$preloader.classList.add('active')
        }, 50)
    }

    // OFFCANVAS

    initOffcanvas() {
        this.$offcanvasMenu = document.querySelector('.js-offcanvas')
        this.$offcanvasOpen = document.querySelector('.js-offcanvas-open')
        this.$offcanvasClose = document.querySelector('.js-offcanvas-close')

        if (!this.$offcanvasMenu || !this.$offcanvasOpen || !this.$offcanvasClose) return

        this.$offcanvasMenu.classList.add('inactive')
        this.$offcanvasOpen.addEventListener('click', this.openOffcanvas.bind(this))
        this.$offcanvasClose.addEventListener('click', this.closeOffcanvas.bind(this))
    }

    openOffcanvas() {
        this.$offcanvasMenu.classList.toggle('active')
        this.$offcanvasMenu.classList.toggle('inactive')
        document.body.style.overflow = 'hidden'
    }

    closeOffcanvas() {
        this.$offcanvasMenu.classList.toggle('active')
        this.$offcanvasMenu.classList.toggle('inactive')
        document.body.style.overflow = ''
    }

    // CLOSE MESSAGE BOX

    initMessageBox() {
        this.$messageBox = document.querySelector('.js-message')
        if (!this.$messageBox) return

        this.$messageBox.addEventListener('click', e => {
            const $messageClose = e.target.closest('.js-message-close')
            if (!$messageClose) return

            this.closeMessageBox()
        })
    }

    closeMessageBox() {
        this.$messageBox.remove()
    }

    // STICKY HEADER

    initHeader() {
        this.$header = document.querySelector('.js-header')
        this.isHeaderSticky = false

        this.stickyHeader()

        const handleStickyHeaderScroll = throttleFunction(this.stickyHeader.bind(this), 30)
        window.addEventListener('scroll', handleStickyHeaderScroll)
    }

    stickyHeader() {
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

    // ALERTS

    initAlert() {
        this.alertTimeoutId

        document.addEventListener('click', e => {
            if (e.target.closest('.js-alert-close')) {
                this.closeAlert()
            }
        })
    }

    showAlert(message, type) {
        this.alertMessage = message
        this.alertType = type
        this.$currentAlert = document.querySelector('.js-alert')

        const alertTypes = {
            success: { icon: 'ri-checkbox-circle-line', title: 'Success :)' },
            error: { icon: 'ri-checkbox-circle-line', title: 'Error!' },
            info: { icon: 'ri-information-line', title: 'Information...' },
            warning: { icon: 'ri-spam-3-line', title: 'Warning!' }
        }

        const { icon, title } = alertTypes[type] || alertTypes.info
        this.alertIcon = icon
        this.alertTitle = title

        clearTimeout(this.alertTimeoutId)

        if (this.$currentAlert) this.closeAlert()

        this.createAlertInDOM()

        this.alertTimeoutId = setTimeout(() => this.closeAlert(), 6000)
    }

    createAlertInDOM() {
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

    closeAlert(e) {
        this.$currentAlert.remove()
    }
}

export const uiController = new UiController()
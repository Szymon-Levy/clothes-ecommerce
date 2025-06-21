const UiController = function() {
  this.$htmlElement = document.documentElement
}

UiController.prototype.init = function() {
  this.initSettingSideSpace()
}

// SET SIDE SPACE

UiController.prototype.setSideSpace = function() {
  const $htmlElement = document.documentElement
  const sideSpace = Math.floor(document.body.clientWidth / 8)

  $htmlElement.style.setProperty('--side-space-dynamic', sideSpace + 'px')
}

UiController.prototype.initSettingSideSpace = function() {
  this.setSideSpace()

  const setSideSpaceHandler = throttleFunction(this.setSideSpace, 100)
  window.addEventListener('resize', setSideSpaceHandler)
}

export { UiController }
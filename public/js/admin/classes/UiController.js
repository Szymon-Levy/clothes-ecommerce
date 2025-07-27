const UiController = function() {
  const init = () => {
    this.initAside()
    this.initMessageBox()
    this.initAlert()
    this.initTable()
  }

  init()
}

UiController.prototype = {
  constructor: UiController,

  // ASIDE

  initAside: function() {
    this.$aside = document.querySelector('.js-aside')
    this.$mobileOverlay = document.querySelector('.js-mobile-overlay')
    if(!this.$aside) return
    
    const $showAsideBtn = document.querySelector('.js-show-aside')
    const $hideAsideBtn = document.querySelector('.js-hide-aside')
    
    $showAsideBtn.addEventListener('click', this.showAside.bind(this))
    $hideAsideBtn.addEventListener('click', this.hideAside.bind(this))
    this.$mobileOverlay.addEventListener('click', this.hideAside.bind(this))

    this.$aside.addEventListener('click', e => {
      this.$clickedAsideNavItem = e.target.closest('.js-aside-nav-menu-item-title')
      if (!this.$clickedAsideNavItem) return
      this.collapseAsideDropdownMenu()
    })
  },

  showAside: function() {
    this.$aside.classList.add('show')
    this.$mobileOverlay.classList.add('active')
  },

  hideAside: function() {
    this.$aside.classList.remove('show')
    this.$mobileOverlay.classList.remove('active')
  },

  collapseAsideDropdownMenu: function() {
    const $currentlyActiveItem = this.$aside.querySelector('.js-aside-nav-menu-item-title.show-links')
      if ($currentlyActiveItem && $currentlyActiveItem != this.$clickedAsideNavItem) {
        $currentlyActiveItem.classList.remove('show-links')
      }
    this.$clickedAsideNavItem.classList.toggle('show-links')
  },

  // MESSAGE BOX

  initMessageBox: function() {
    this.$messageBox = document.querySelector('.js-message')
    if (!this.$messageBox) return

    this.$messageBox.addEventListener('click', e => {
      const $messageClose = e.target.closest('.js-message-close')
      if (!$messageClose) return
      
      this.closeMessageBox()
    })
  },

  closeMessageBox: function() {
    this.$messageBox.remove()
  },

  // ALERT

  initAlert: function() {
    this.alertTimeoutId

    document.addEventListener('click', e => {
      if (e.target.closest('.js-alert-close')) {
        this.closeAlert()
      }
    })
  },

  showAlert: function(message, type) {
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

    this.alertTimeoutId = setTimeout(() => this.closeAlert(), 3000)
  },

  createAlertInDOM: function() {
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
    document.querySelector('.app-content__panel').insertAdjacentElement('afterend', alert)
  },

  closeAlert: function(e) {
    this.$currentAlert.remove()
  },

  // TABLE

  initTable: function() {
    this.$selectAllItemsTogglers = document.querySelectorAll('.js-select-all-items')
    this.$table = document.querySelector('.js-table')

    if(this.$table) {
      this.$table.addEventListener('change', e => {
        const $target = e.target

        if ($target.closest('.js-sort-columns-select')) {
          this.$tableChangeTarget = $target.closest('.js-sort-columns-select')
          this.updateSortTableParamsOnMobile()
        }
        else if ($target.closest('.js-select-all-items')) {
          this.$tableChangeTarget = $target.closest('.js-select-all-items')
          this.toggleTableItemsSelection()
        }
      })
    }
  },

  toggleTableItemsSelection: function () {
    const $selectItems = document.querySelectorAll('.js-select-item')
    if (!$selectItems) { return false }

    const currentState = this.$tableChangeTarget.checked
    document.querySelectorAll('.js-select-all-items').forEach($select => {
      if ($select != this.$tableChangeTarget) $select.checked = currentState
    })

    if (currentState) {
      $selectItems.forEach($item => {
        if (!$item.checked) {
          $item.checked = true
        }
      })
    }
    else {
      $selectItems.forEach($item => {
        if ($item.checked) {
          $item.checked = false
        }
      })
    }
  },

  updateSortTableParamsOnMobile: function() {
    const $form = this.$tableChangeTarget.closest('.js-sort-columns-mobile-form')
    const $select = $form.querySelector('.js-sort-columns-select')
    const selectedValue = $select.value.split("-")
    $form.querySelector('[name="orderby"]').value = selectedValue[0]
    $form.querySelector('[name="sort"]').value = selectedValue[1]
    $form.submit()
  },

  setDeleteItemsFromTableHandler: function(callback, deletionContent) {
    if(this.$table) {
      this.$table.addEventListener('click', e => {
        const $target = e.target

        if ($target.closest('.js-table-delete-item')) {
          this.$tableClickTarget = $target.closest('.js-table-delete-item')
          const btnDataDeletionContent = this.$tableClickTarget.dataset.deletionContent
          if(btnDataDeletionContent !== deletionContent) return

          const itemId = this.$tableClickTarget.closest('.js-table-row')?.dataset.id

          if (!itemId) {
            this.showAlert('Subscriber deletion failed.', 'error')
            return false
          }

          if (confirm('Are you sure you want to remove this subscriber?')) {
            this.disableDeleteSelectedButton()
            const idArray = [itemId]
            callback(idArray)
          }
        }
      })
    }

    this.$deleteSelectedBtn = document.querySelector('.js-delete-selected')

    if (this.$deleteSelectedBtn) {
      this.$deleteSelectedBtn.addEventListener('click', (e) => {
        const btnDataDeletionContent = e.target.dataset.deletionContent
        if (btnDataDeletionContent !== deletionContent) return
        
        const itemIds = this.getSelectedTableItemsIds()
        if (!itemIds) return

        if (confirm('Are you sure you want to remove selected subscribers?')) {
          this.disableDeleteSelectedButton()
          callback(itemIds)
          return itemIds
        }
      })
    }
  },

  disableDeleteSelectedButton: function() {
    this.$deleteSelectedBtn.disabled = true
  },

  enableDeleteSelectedButton: function() {
    if (this.$deleteSelectedBtn.disabled) {
      this.$deleteSelectedBtn.removeAttribute('disabled')
    }
  },

  getSelectedTableItemsIds: function() {
    const $selectItems = this.$table.querySelectorAll('.js-select-item')

    if (!$selectItems.length) {
      this.showAlert('Items to select don\'t exists!', 'error')
      return false;
    }

    const isAnyChecked = [...$selectItems].some(checkbox => checkbox.checked)

    if (!isAnyChecked) {
      this.showAlert('None of the items are selected!', 'info')
      return false;
    }

    const ids = []

    $selectItems.forEach(checkbox => {
      if (checkbox.checked) {
        ids.push(checkbox.value)
      }
    })

    return ids
  },

  handleDeletionItemsFromTable: function(ids, count) {
    this.deleteItemsCount = count

    if (!Array.isArray(this.tableRowsToRemove)) {
      this.tableRowsToRemove = []
    }

    ids.forEach(id => {
      const $row = document.querySelector(`.js-table-row[data-id="${id}"]`)
      if ($row) {
        this.tableRowsToRemove.push($row)
        $row.classList.add('removed')
      }
    })

    setTimeout(() => {
      if (this.tableRowsToRemove.length) {
        this.tableRowsToRemove.forEach($row => $row.remove())
        this.tableRowsToRemove = []
      }

      this.updateTableNumbersAfterItemsDeletion()
    }, 1000)
  },

  updateTableNumbersAfterItemsDeletion: function() {
    const $totalRows = document.querySelectorAll('.js-table-row')
    this.getTablePageNumbers()

    if ($totalRows.length === 0) {
      this.handleEmptiedTableItems()
    }
    else {
      this.updateTablePageNumbers()
      this.enableDeleteSelectedButton()
    }
  },

  getTablePageNumbers: function() {
    this.tablePageNumbers = {
      to: Number(document.querySelector('.js-table-number-to').innerText),
      of: Number(document.querySelector('.js-table-number-of').innerText)
    }
  },

  handleEmptiedTableItems: function() {
    const params = new URLSearchParams(location.search)
    this.currentTablePage = +params.get('page')

    this.removeTablePageNumbers()

    if (this.currentTablePage && this.currentTablePage > 1) {
      this.goToPreviousTablePage()
      return
    }

    if (this.tablePageNumbers.of > this.tablePageNumbers.to) {
      location.reload()
      return
    }

    this.handleEmptiedTable()
  },

  goToPreviousTablePage: function() {
    const newUrl = new URL(location.href)
    newUrl.searchParams.set('page', this.currentTablePage - 1)
    location.href = newUrl.toString()
  },

  handleEmptiedTable: function() {
    document.querySelector('.js-panel-bottom').remove()
    document.querySelector('.js-table').innerHTML = 'No items found to display'
  },

  removeTablePageNumbers: function() {
    document.querySelector('.js-table-numbers').style.visibility = 'hidden'
  },

  updateTablePageNumbers: function() {
    const $to = document.querySelector('.js-table-number-to')
    const $of = document.querySelector('.js-table-number-of')

    const to = this.tablePageNumbers.to - this.deleteItemsCount
    const of = this.tablePageNumbers.of - this.deleteItemsCount

    $to.innerText = to
    $of.innerText = of
  }
}

export const uiController = new UiController()
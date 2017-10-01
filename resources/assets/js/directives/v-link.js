Vue.directive('link', {
  bind: function (el, { modifiers, value }) {
    var isClicking = true
    var clickTimeout

    handleClick = (e) => {
      isClicking = false
    }

    el.addEventListener('mousedown', e => {
      clickTimeout = setTimeout(handleClick, 400, e);
    })

    el.addEventListener('mouseup', e => {
      clearInterval(clickTimeout)

      if (isClicking) {
        e.stopPropagation()

        if (modifiers.blank) {
          window.open(value)
          return
        }
        window.location.href = value
      }

      isClicking = true
    })
  }
})

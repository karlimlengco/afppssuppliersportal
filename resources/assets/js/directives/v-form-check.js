Vue.directive('form-check', {
  bind: function (el, { value, rawName, expression }) {
    var isSubmitting = false

    var submitForm = () => {
      isSubmitting = true
    }

    el.addEventListener('submit', submitForm)

    window.onbeforeunload = () => {
      if (!isSubmitting) {
        return 'Are you sure you want to navigate away from this page?';
      }
    }
  }
})

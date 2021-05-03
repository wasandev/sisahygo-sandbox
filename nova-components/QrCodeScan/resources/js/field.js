Nova.booting((Vue, router, store) => {
  Vue.component('index-qr-code-scan', require('./components/IndexField'))
  Vue.component('detail-qr-code-scan', require('./components/DetailField'))
  Vue.component('form-qr-code-scan', require('./components/FormField'))
})

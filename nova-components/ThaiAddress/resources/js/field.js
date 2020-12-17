Nova.booting((Vue, router, store) => {
  Vue.component('index-thai-address', require('./components/IndexField'))
  Vue.component('detail-thai-address', require('./components/DetailField'))
  Vue.component('form-thai-address', require('./components/FormField'))
})

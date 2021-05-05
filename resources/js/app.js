import Vue from 'vue';


require('./bootstrap');

require('./nav');
require('./addtohome');


window.$ = window.jQuery = require('jquery');



window.Vue = require('vue');


import VueThailandAddress from 'vue-thailand-address';



// เพิ่ม stylesheet ของ Vue Thailand Address เข้าไป
import 'vue-thailand-address/dist/vue-thailand-address.css'; // ใช้ Plugin
Vue.use(VueThailandAddress);



var deferredPrompt;
window.addEventListener('beforeinstallprompt', function(event) {
  event.preventDefault();
  deferredPrompt = event;
  return false;
});

function addToHomeScreen() {
  if (deferredPrompt) {
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then(function (choiceResult) {
      console.log(choiceResult.outcome);
      if (choiceResult.outcome === 'dismissed') {
        console.log('User cancelled installation');
      } else {
        console.log('User added to home screen');
      }
    });
    deferredPrompt = null;
  }
}

//require('./components');
if ('serviceWorker' in navigator) {
  // Use the window load event to keep the page load performant
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js');
  });
}

var app = new Vue({
    el: "#app",
    //router: new VueRouter(routes),
    data: {
        termModalShowing: false,
        subdistrict: "",
      district: "",
      province: "",
      zipcode: ""

    },


});


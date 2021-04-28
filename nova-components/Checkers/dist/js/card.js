/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
module.exports = __webpack_require__(6);


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

Nova.booting(function (Vue, router, store) {
  Vue.component('checkers', __webpack_require__(2));
});

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(3)
/* script */
var __vue_script__ = __webpack_require__(4)
/* template */
var __vue_template__ = __webpack_require__(5)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/Card.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-b9bc2c0a", Component.options)
  } else {
    hotAPI.reload("data-v-b9bc2c0a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 3 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'Checkers',

  props: {
    card: Object
  },

  methods: {
    redirect: function redirect(path) {
      return '/app/resources/' + path;
    }
  },

  computed: {
    checking: function checking() {
      return this.redirect('order_checkers');
    },
    customers: function customers() {
      return this.redirect('customers');
    },
    productprices: function productprices() {
      return this.redirect('productservice_prices');
    },
    users: function users() {

      return this.redirect('users');
    }
  }
});

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "flex justify-center items-centers" }, [
    _c(
      "div",
      { staticClass: "w-full max-w-xl" },
      [
        _c("heading", { staticClass: "flex mb-3" }, [_vm._v("ยินดีต้อนรับ")]),
        _vm._v(" "),
        _c("p", { staticClass: "text-90 leading-tight mb-8" }, [
          _vm._v("\n      Sisahygo : ระบบงานสำหรับพนักงานตรวจรับสินค้า\n    ")
        ]),
        _vm._v(" "),
        _c("card", [
          _c(
            "table",
            {
              staticClass: "w-full",
              attrs: { cellpadding: "0", cellspacing: "0" }
            },
            [
              _c("tr", [
                _c(
                  "td",
                  {
                    staticClass: "align-top w-1/2 border-r border-b border-50"
                  },
                  [
                    _c(
                      "a",
                      {
                        staticClass: "no-underline dim flex p-6",
                        attrs: { href: _vm.checking }
                      },
                      [
                        _c(
                          "div",
                          {
                            staticClass:
                              "flex justify-center w-11 flex-no-shrink mr-6"
                          },
                          [
                            _c(
                              "svg",
                              {
                                attrs: {
                                  xmlns: "http://www.w3.org/2000/svg",
                                  width: "40",
                                  height: "40",
                                  viewBox: "0 0 40 40"
                                }
                              },
                              [
                                _c("path", {
                                  attrs: {
                                    fill: "var(--primary)",
                                    d:
                                      "M31.51 25.86l7.32 7.31c1.0110617 1.0110616 1.4059262 2.4847161 1.035852 3.865852-.3700742 1.3811359-1.4488641 2.4599258-2.83 2.83-1.3811359.3700742-2.8547904-.0247903-3.865852-1.035852l-7.31-7.32c-7.3497931 4.4833975-16.89094893 2.7645226-22.21403734-4.0019419-5.3230884-6.7664645-4.74742381-16.4441086 1.34028151-22.53181393C11.0739495-1.11146115 20.7515936-1.68712574 27.5180581 3.63596266 34.2845226 8.95905107 36.0033975 18.5002069 31.52 25.85l-.01.01zm-3.99 4.5l7.07 7.05c.7935206.6795536 1.9763883.6338645 2.7151264-.1048736.7387381-.7387381.7844272-1.9216058.1048736-2.7151264l-7.06-7.07c-.8293081 1.0508547-1.7791453 2.0006919-2.83 2.83v.01zM17 32c8.2842712 0 15-6.7157288 15-15 0-8.28427125-6.7157288-15-15-15C8.71572875 2 2 8.71572875 2 17c0 8.2842712 6.71572875 15 15 15zm0-2C9.82029825 30 4 24.1797017 4 17S9.82029825 4 17 4c7.1797017 0 13 5.8202983 13 13s-5.8202983 13-13 13zm0-2c6.0751322 0 11-4.9248678 11-11S23.0751322 6 17 6 6 10.9248678 6 17s4.9248678 11 11 11z"
                                  }
                                })
                              ]
                            )
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "div",
                          [
                            _c(
                              "heading",
                              { staticClass: "mb-3", attrs: { level: 3 } },
                              [_vm._v("ตรวจรับสินค้า")]
                            ),
                            _vm._v(" "),
                            _c("p", { staticClass: "text-90 leading-normal" }, [
                              _vm._v(
                                "\n                  ทำรายการตรวจรับสินค้า\n                "
                              )
                            ])
                          ],
                          1
                        )
                      ]
                    )
                  ]
                ),
                _vm._v(" "),
                _c(
                  "td",
                  { staticClass: "align-top w-1/2 border-b border-50" },
                  [
                    _c(
                      "a",
                      {
                        staticClass: "no-underline dim flex p-6",
                        attrs: { href: _vm.customers }
                      },
                      [
                        _c(
                          "div",
                          {
                            staticClass:
                              "flex justify-center w-11 flex-no-shrink mr-6"
                          },
                          [
                            _c(
                              "svg",
                              {
                                attrs: {
                                  xmlns: "http://www.w3.org/2000/svg",
                                  width: "44",
                                  height: "44",
                                  viewBox: "0 0 44 44"
                                }
                              },
                              [
                                _c("path", {
                                  attrs: {
                                    fill: "var(--primary)",
                                    d:
                                      "M22 44C9.8497355 44 0 34.1502645 0 22S9.8497355 0 22 0s22 9.8497355 22 22-9.8497355 22-22 22zm0-2c11.045695 0 20-8.954305 20-20S33.045695 2 22 2 2 10.954305 2 22s8.954305 20 20 20zm3-24h5c.3638839-.0007291.6994429.1962627.8761609.5143551.176718.3180924.1666987.707072-.0261609 1.0156449l-10 16C20.32 36.38 19 36 19 35v-9h-5c-.3638839.0007291-.6994429-.1962627-.8761609-.5143551-.176718-.3180924-.1666987-.707072.0261609-1.0156449l10-16C23.68 7.62 25 8 25 9v9zm3.2 2H24c-.5522847 0-1-.4477153-1-1v-6.51L15.8 24H20c.5522847 0 1 .4477153 1 1v6.51L28.2 20z"
                                  }
                                })
                              ]
                            )
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "div",
                          [
                            _c(
                              "heading",
                              { staticClass: "mb-3", attrs: { level: 3 } },
                              [_vm._v("ข้อมูลลูกค้า")]
                            ),
                            _vm._v(" "),
                            _c("p", { staticClass: "text-90 leading-normal" }, [
                              _vm._v(
                                "\n                  ดูข้อมูลลูกค้า\n                "
                              )
                            ])
                          ],
                          1
                        )
                      ]
                    )
                  ]
                )
              ]),
              _vm._v(" "),
              _c("tr", [
                _c(
                  "td",
                  {
                    staticClass: "align-top w-1/2 border-r border-b border-50"
                  },
                  [
                    _c(
                      "a",
                      {
                        staticClass: "no-underline dim flex p-6",
                        attrs: { href: _vm.productprices }
                      },
                      [
                        _c(
                          "div",
                          {
                            staticClass:
                              "flex justify-center w-11 flex-no-shrink mr-6"
                          },
                          [
                            _c(
                              "svg",
                              {
                                attrs: {
                                  xmlns: "http://www.w3.org/2000/svg",
                                  width: "38",
                                  height: "38",
                                  viewBox: "0 0 38 38"
                                }
                              },
                              [
                                _c("path", {
                                  attrs: {
                                    fill: "var(--primary)",
                                    d:
                                      "M36 4V2H2v6.59l13.7 13.7c.1884143.1846305.296243.4362307.3.7v11.6l6-6v-5.6c.003757-.2637693.1115857-.5153695.3-.7L36 8.6V6H19c-.5522847 0-1-.44771525-1-1s.4477153-1 1-1h17zM.3 9.7C.11158574 9.51536954.00375705 9.26376927 0 9V1c0-.55228475.44771525-1 1-1h36c.5522847 0 1 .44771525 1 1v8c-.003757.26376927-.1115857.51536954-.3.7L24 23.42V29c-.003757.2637693-.1115857.5153695-.3.7l-8 8c-.2857003.2801197-.7108712.3629755-1.0808485.210632C14.2491743 37.7582884 14.0056201 37.4000752 14 37V23.4L.3 9.71V9.7z"
                                  }
                                })
                              ]
                            )
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "div",
                          [
                            _c(
                              "heading",
                              { staticClass: "mb-3", attrs: { level: 3 } },
                              [_vm._v("ค่าขนส่งสินค้า")]
                            ),
                            _vm._v(" "),
                            _c("p", { staticClass: "text-90 leading-normal" }, [
                              _vm._v(
                                "\n                  ดูราคาค่าขนส่งสินค้า\n                "
                              )
                            ])
                          ],
                          1
                        )
                      ]
                    )
                  ]
                ),
                _vm._v(" "),
                _c(
                  "td",
                  { staticClass: "align-top w-1/2 border-b border-50" },
                  [
                    _c(
                      "a",
                      {
                        staticClass: "no-underline dim flex p-6",
                        attrs: { href: _vm.users }
                      },
                      [
                        _c(
                          "div",
                          {
                            staticClass:
                              "flex justify-center w-11 flex-no-shrink mr-6"
                          },
                          [
                            _c(
                              "svg",
                              {
                                attrs: {
                                  xmlns: "http://www.w3.org/2000/svg",
                                  width: "36",
                                  height: "36",
                                  viewBox: "0 0 36 36"
                                }
                              },
                              [
                                _c("path", {
                                  attrs: {
                                    fill: "var(--primary)",
                                    d:
                                      "M4 8C1.790861 8 0 6.209139 0 4s1.790861-4 4-4 4 1.790861 4 4-1.790861 4-4 4zm0-2c1.1045695 0 2-.8954305 2-2s-.8954305-2-2-2-2 .8954305-2 2 .8954305 2 2 2zm0 16c-2.209139 0-4-1.790861-4-4s1.790861-4 4-4 4 1.790861 4 4-1.790861 4-4 4zm0-2c1.1045695 0 2-.8954305 2-2s-.8954305-2-2-2-2 .8954305-2 2 .8954305 2 2 2zm0 16c-2.209139 0-4-1.790861-4-4s1.790861-4 4-4 4 1.790861 4 4-1.790861 4-4 4zm0-2c1.1045695 0 2-.8954305 2-2s-.8954305-2-2-2-2 .8954305-2 2 .8954305 2 2 2zm9-31h22c.5522847 0 1 .44771525 1 1s-.4477153 1-1 1H13c-.5522847 0-1-.44771525-1-1s.4477153-1 1-1zm0 14h22c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1H13c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1zm0 14h22c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1H13c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1z"
                                  }
                                })
                              ]
                            )
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "div",
                          [
                            _c(
                              "heading",
                              { staticClass: "mb-3", attrs: { level: 3 } },
                              [_vm._v("ข้อมูลผู้ใช้")]
                            ),
                            _vm._v(" "),
                            _c("p", { staticClass: "text-90 leading-normal" }, [
                              _vm._v(
                                "\n                  ข้อมูลผู้ใช้งาน\n                "
                              )
                            ])
                          ],
                          1
                        )
                      ]
                    )
                  ]
                )
              ])
            ]
          )
        ])
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-b9bc2c0a", module.exports)
  }
}

/***/ }),
/* 6 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);
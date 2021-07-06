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
  Vue.component('report', __webpack_require__(2));
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
  name: 'Report',

  props: {
    card: Object
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
        _c("heading", { staticClass: "flex mb-3" }, [
          _vm._v("Quick Reports Menu")
        ]),
        _vm._v(" "),
        _c("p", { staticClass: "text-90 leading-tight mb-8" }, [
          _vm._v("\n          รวม Link รายงานต่างๆ\n      ")
        ]),
        _vm._v(" "),
        _c("card", [
          _c(
            "div",
            {
              staticClass: "w-full flex flex-wrap",
              attrs: { cellpadding: "10", cellspacing: "10" }
            },
            [
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "order_headers",
                            lens: "order-billing-cash"
                          }
                        },
                        title: _vm.รายงานเงินสดรับตามพนักงาน
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานเงินสดรับ ตามพนักงาน")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "order_headers",
                            lens: "accounts-order-report-bill-by-day"
                          }
                        },
                        title: _vm.รายงานรายการขนส่งประจำวัน
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานรายการขนส่งประจำวัน")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "w-full flex flex-wrap",
              attrs: { cellpadding: "10", cellspacing: "10" }
            },
            [
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "order_headers",
                            lens: "accounts-order-report-by-day"
                          }
                        },
                        title: _vm.รายงานยอดค่าขนส่งตามวัน
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานยอดค่าขนส่งตามวัน")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "order_headers",
                            lens: "accounts-order-report-by-branchrec"
                          }
                        },
                        title: _vm.รายงานยอดค่าขนส่งตามสาขาปลายทาง
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานยอดค่าขนส่งตามสาขาปลายทาง")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "w-full flex flex-wrap",
              attrs: { cellpadding: "10", cellspacing: "10" }
            },
            [
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "order_headers",
                            lens: "accounts-order-report-cancel-by-day"
                          }
                        },
                        title: _vm.รายงานรายการยกเลิกใบรับส่ง
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานรายการยกเลิกใบรับส่ง")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "order_headers",
                            lens: "accounts-order-report-cash-by-day"
                          }
                        },
                        title: _vm.รายงานขายสดประจำวัน
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานขายสดประจำวัน")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "w-full flex flex-wrap",
              attrs: { cellpadding: "10", cellspacing: "10" }
            },
            [
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "order_headers",
                            lens: "accounts-order-report-cr-by-day"
                          }
                        },
                        title: _vm.รายงานขายเชื่อประจำวัน
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานขายเชื่อประจำวัน")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "waybills",
                            lens: "waybill-confirmed-per-day"
                          }
                        },
                        title: _vm.รายงานรถออกประจำวัน
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานรถออกประจำวัน")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "w-full flex flex-wrap",
              attrs: { cellpadding: "10", cellspacing: "10" }
            },
            [
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "carreceives",
                            lens: "accounts-carreceive-report-by-day"
                          }
                        },
                        title: _vm.รายงานสรุปรับเงินรถ
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานสรุปรับเงินรถ")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "carpayments",
                            lens: "accounts-carpayment-report-by-day"
                          }
                        },
                        title: _vm.รายงานสรุปจ่ายเงินรถ
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานสรุปจ่ายเงินรถ")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "w-full flex flex-wrap",
              attrs: { cellpadding: "10", cellspacing: "10" }
            },
            [
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "car_balances",
                            lens: "cars-carcard-report"
                          }
                        },
                        title: _vm.รายงานสรุปรับเงินรถ
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานบัญชีคุมรถบรรทุก")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "car_balances",
                            lens: "cars-summary-report"
                          }
                        },
                        title: _vm.รายงานสรุปยอดคงเหลือรถ
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานสรุปยอดคงเหลือรถ")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "w-full flex flex-wrap",
              attrs: { cellpadding: "10", cellspacing: "10" }
            },
            [
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "ar_balances",
                            lens: "ar-outstanding-report"
                          }
                        },
                        title: _vm.รายงานลูกหนี้ค้างชำระ
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานลูกหนี้ค้างชำระ")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "receipt_ars",
                            lens: "ar-receipt-report"
                          }
                        },
                        title: _vm.รายงานรับชำระหนี้ลูกหนี้การค้า
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานรับชำระหนี้ลูกหนี้การค้า")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "w-full flex flex-wrap",
              attrs: { cellpadding: "10", cellspacing: "10" }
            },
            [
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "ar_balances",
                            lens: "ar-card-report"
                          }
                        },
                        title: _vm.รายงานทะเบียนคุมลูกหนี้
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานทะเบียนคุมลูกหนี้")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "ar_balances",
                            lens: "ar-summary-report"
                          }
                        },
                        title: _vm.รายงานสรุปยอดลูกหนี้การค้า
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานสรุปยอดลูกหนี้การค้า")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "w-full flex flex-wrap",
              attrs: { cellpadding: "10", cellspacing: "10" }
            },
            [
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "branch_balances",
                            lens: "branch-branch-balance-bydate"
                          }
                        },
                        title: _vm.รายงานตั้งหนี้ลูกหนี้สาขา
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานตั้งหนี้ลูกหนี้สาขา")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "branch_balances",
                            lens: "branch-branch-balance-receipt"
                          }
                        },
                        title: _vm.รายงานรับชำระหนี้ลูกหนี้สาขา
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานรับชำระหนี้ลูกหนี้สาขา")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "w-full flex flex-wrap",
              attrs: { cellpadding: "10", cellspacing: "10" }
            },
            [
              _c(
                "div",
                { staticClass: "align-top w-1/2 border-r border-b border-50" },
                [
                  _c(
                    "router-link",
                    {
                      staticClass: "no-underline dim flex p-6",
                      attrs: {
                        to: {
                          name: "lens",
                          params: {
                            resourceName: "branch_balances",
                            lens: "branch-branch-balance-report"
                          }
                        },
                        title: _vm.รายงานลูกหนี้สาขาค้างชำระ
                      }
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
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  fill: "var(--primary)",
                                  d:
                                    "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                                }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-3" },
                        [
                          _c(
                            "heading",
                            { staticClass: "mb-3", attrs: { level: 3 } },
                            [_vm._v("รายงานลูกหนี้สาขาค้างชำระ")]
                          )
                        ],
                        1
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c("div", {
                staticClass: "align-top w-1/2 border-r border-b border-50"
              })
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
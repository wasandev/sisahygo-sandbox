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
  Vue.component('truck', __webpack_require__(2));
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

/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'truck',

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
        _c("heading", { staticClass: "flex mb-3" }, [_vm._v("Quick Menu")]),
        _vm._v(" "),
        _c("p", { staticClass: "text-90 leading-tight mb-8" }, [
          _vm._v("\n      สำหรับฝ่ายรถบรรทุก\n    ")
        ]),
        _vm._v(" "),
        _c("card", [
          _c(
            "table",
            {
              staticClass: "w-full",
              attrs: { cellpadding: "10", cellspacing: "10" }
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
                      "router-link",
                      {
                        staticClass: "no-underline dim flex p-6",
                        attrs: {
                          to: {
                            name: "index",
                            params: {
                              resourceName: "cars"
                            }
                          },
                          title: _vm.ข้อมูลรถ
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
                                      "M13 18H7v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-1a2 2 0 0 1-2-2V2c0-1.1.9-2 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2v1a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1zM4 5v6h5V5H4zm7 0v6h5V5h-5zM5 2v1h10V2H5zm.5 14a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm9 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"
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
                              [_vm._v("ข้อมูลรถ")]
                            ),
                            _vm._v(" "),
                            _c("p", { staticClass: "text-90 leading-normal" }, [
                              _vm._v(
                                "\n                          ค้นหา ดู จัดการข้อมูลรถ\n                        "
                              )
                            ])
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
                  "td",
                  {
                    staticClass: "align-top w-1/2 border-r border-b border-50"
                  },
                  [
                    _c(
                      "router-link",
                      {
                        staticClass: "no-underline dim flex p-6",
                        attrs: {
                          to: {
                            name: "index",
                            params: {
                              resourceName: "car_balances"
                            }
                          },
                          title: _vm.บัญชีรถ
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
                                      "M16 2h4v15a3 3 0 0 1-3 3H3a3 3 0 0 1-3-3V0h16v2zm0 2v13a1 1 0 0 0 1 1 1 1 0 0 0 1-1V4h-2zM2 2v15a1 1 0 0 0 1 1h11.17a2.98 2.98 0 0 1-.17-1V2H2zm2 8h8v2H4v-2zm0 4h8v2H4v-2zM4 4h8v4H4V4z"
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
                              [_vm._v("บัญชีรถ")]
                            ),
                            _vm._v(" "),
                            _c("p", { staticClass: "text-90 leading-normal" }, [
                              _vm._v(
                                "\n                          ค้นหา ดู บัญชีรับ-จ่ายของรถ\n                        "
                              )
                            ])
                          ],
                          1
                        )
                      ]
                    )
                  ],
                  1
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
                      "router-link",
                      {
                        staticClass: "no-underline dim flex p-6",
                        attrs: {
                          to: {
                            name: "index",
                            params: {
                              resourceName: "charter_jobs"
                            }
                          },
                          title: _vm.ใบงานขนส่งเหมาคัน
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
                                      "M9 10V8h2v2h2v2h-2v2H9v-2H7v-2h2zm-5 8h12V6h-4V2H4v16zm-2 1V0h12l4 4v16H2v-1z"
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
                              [_vm._v("ใบงานขนส่งเหมาคัน")]
                            ),
                            _vm._v(" "),
                            _c("p", { staticClass: "text-90 leading-normal" }, [
                              _vm._v(
                                "\n                          จัดการข้อมูลใบงานขนส่งเหมาคัน\n                        "
                              )
                            ])
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
                  "td",
                  {
                    staticClass: "align-top w-1/2 border-r border-b border-50"
                  },
                  [
                    _c(
                      "router-link",
                      {
                        staticClass: "no-underline dim flex p-6",
                        attrs: {
                          to: {
                            name: "index",
                            params: {
                              resourceName: "users"
                            }
                          },
                          title: _vm.ข้อมูลผู้ใช้
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
                                      "M5 5a5 5 0 0 1 10 0v2A5 5 0 0 1 5 7V5zM0 16.68A19.9 19.9 0 0 1 10 14c3.64 0 7.06.97 10 2.68V20H0v-3.32z"
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
                                "\n                          แก้ไขรายละเอียดบัญชีผู้ใช้ของตัวเอง\n                        "
                              )
                            ])
                          ],
                          1
                        )
                      ]
                    )
                  ],
                  1
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
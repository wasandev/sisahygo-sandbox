/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Card.vue?vue&type=script&lang=js":
/*!**********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Card.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'Report',
  props: {
    card: Object
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Card.vue?vue&type=template&id=b9bc2c0a":
/*!*********************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Card.vue?vue&type=template&id=b9bc2c0a ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render),
/* harmony export */   staticRenderFns: () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "flex justify-center items-centers"
  }, [_c("div", {
    staticClass: "w-full max-w-xl"
  }, [_c("heading", {
    staticClass: "flex mb-3"
  }, [_vm._v("Quick Reports Menu")]), _vm._v(" "), _c("p", {
    staticClass: "text-90 leading-tight mb-8"
  }, [_vm._v("\n          รวม Link รายงานต่างๆ\n      ")]), _vm._v(" "), _c("card", [_c("div", {
    staticClass: "w-full flex flex-wrap",
    attrs: {
      cellpadding: "10",
      cellspacing: "10"
    }
  }, [_c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
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
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานเงินสดรับ ตามพนักงาน")])], 1)])], 1), _vm._v(" "), _c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
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
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานรายการขนส่งประจำวัน")])], 1)])], 1)]), _vm._v(" "), _c("div", {
    staticClass: "w-full flex flex-wrap",
    attrs: {
      cellpadding: "10",
      cellspacing: "10"
    }
  }, [_c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
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
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานยอดค่าขนส่งตามวัน")])], 1)])], 1), _vm._v(" "), _c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
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
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานยอดค่าขนส่งตามสาขาปลายทาง")])], 1)])], 1)]), _vm._v(" "), _c("div", {
    staticClass: "w-full flex flex-wrap",
    attrs: {
      cellpadding: "10",
      cellspacing: "10"
    }
  }, [_c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
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
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานรายการยกเลิกใบรับส่ง")])], 1)])], 1), _vm._v(" "), _c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
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
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานขายสดประจำวัน")])], 1)])], 1)]), _vm._v(" "), _c("div", {
    staticClass: "w-full flex flex-wrap",
    attrs: {
      cellpadding: "10",
      cellspacing: "10"
    }
  }, [_c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
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
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานขายเชื่อประจำวัน")])], 1)])], 1), _vm._v(" "), _c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
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
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานรถออกประจำวัน")])], 1)])], 1)]), _vm._v(" "), _c("div", {
    staticClass: "w-full flex flex-wrap",
    attrs: {
      cellpadding: "10",
      cellspacing: "10"
    }
  }, [_c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
    staticClass: "no-underline dim flex p-6",
    attrs: {
      to: {
        name: "lens",
        params: {
          resourceName: "cars",
          lens: "accounts-carreceive-report-by-day"
        }
      },
      title: _vm.รายงานสรุปรับเงินรถ
    }
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานสรุปรับเงินรถ")])], 1)])], 1), _vm._v(" "), _c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
    staticClass: "no-underline dim flex p-6",
    attrs: {
      to: {
        name: "lens",
        params: {
          resourceName: "cars",
          lens: "accounts-carpayment-report-by-day"
        }
      },
      title: _vm.รายงานสรุปจ่ายเงินรถ
    }
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานสรุปจ่ายเงินรถ")])], 1)])], 1)]), _vm._v(" "), _c("div", {
    staticClass: "w-full flex flex-wrap",
    attrs: {
      cellpadding: "10",
      cellspacing: "10"
    }
  }, [_c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
    staticClass: "no-underline dim flex p-6",
    attrs: {
      to: {
        name: "lens",
        params: {
          resourceName: "cars",
          lens: "cars-carcard-report"
        }
      },
      title: _vm.รายงานสรุปรับเงินรถ
    }
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานบัญชีคุมรถบรรทุก")])], 1)])], 1), _vm._v(" "), _c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
    staticClass: "no-underline dim flex p-6",
    attrs: {
      to: {
        name: "lens",
        params: {
          resourceName: "cars",
          lens: "cars-summary-report"
        }
      },
      title: _vm.รายงานสรุปยอดคงเหลือรถ
    }
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานสรุปยอดคงเหลือรถ")])], 1)])], 1)]), _vm._v(" "), _c("div", {
    staticClass: "w-full flex flex-wrap",
    attrs: {
      cellpadding: "10",
      cellspacing: "10"
    }
  }, [_c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
    staticClass: "no-underline dim flex p-6",
    attrs: {
      to: {
        name: "lens",
        params: {
          resourceName: "vendors",
          lens: "carpay-tax"
        }
      },
      title: _vm.รายงานภาษีหักณที่จ่ายรถ
    }
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานภาษีหัก ณ ที่จ่ายรถ")])], 1)])], 1), _vm._v(" "), _c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
    staticClass: "no-underline dim flex p-6",
    attrs: {
      to: {
        name: "lens",
        params: {
          resourceName: "waybills",
          lens: "waybill-confirmed-per-month"
        }
      },
      title: _vm.รายงานรถออกประจำเดือน
    }
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานรถออกประจำเดือน")])], 1)])], 1)]), _vm._v(" "), _c("div", {
    staticClass: "w-full flex flex-wrap",
    attrs: {
      cellpadding: "10",
      cellspacing: "10"
    }
  }, [_c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
    staticClass: "no-underline dim flex p-6",
    attrs: {
      to: {
        name: "lens",
        params: {
          resourceName: "ar_customers",
          lens: "ar-outstanding-report"
        }
      },
      title: _vm.รายงานลูกหนี้ค้างชำระ
    }
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานลูกหนี้ค้างชำระ")])], 1)])], 1), _vm._v(" "), _c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
    staticClass: "no-underline dim flex p-6",
    attrs: {
      to: {
        name: "lens",
        params: {
          resourceName: "ar_customers",
          lens: "ar-receipt-report"
        }
      },
      title: _vm.รายงานรับชำระหนี้ลูกหนี้การค้า
    }
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานรับชำระหนี้ลูกหนี้การค้า")])], 1)])], 1)]), _vm._v(" "), _c("div", {
    staticClass: "w-full flex flex-wrap",
    attrs: {
      cellpadding: "10",
      cellspacing: "10"
    }
  }, [_c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
    staticClass: "no-underline dim flex p-6",
    attrs: {
      to: {
        name: "lens",
        params: {
          resourceName: "ar_customers",
          lens: "ar-card-report"
        }
      },
      title: _vm.รายงานทะเบียนคุมลูกหนี้
    }
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานทะเบียนคุมลูกหนี้")])], 1)])], 1), _vm._v(" "), _c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
    staticClass: "no-underline dim flex p-6",
    attrs: {
      to: {
        name: "lens",
        params: {
          resourceName: "ar_customers",
          lens: "ar-summary-report"
        }
      },
      title: _vm.รายงานสรุปยอดลูกหนี้การค้า
    }
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานสรุปยอดลูกหนี้การค้า")])], 1)])], 1)]), _vm._v(" "), _c("div", {
    staticClass: "w-full flex flex-wrap",
    attrs: {
      cellpadding: "10",
      cellspacing: "10"
    }
  }, [_c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
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
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานตั้งหนี้ลูกหนี้สาขา")])], 1)])], 1), _vm._v(" "), _c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
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
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานรับชำระหนี้ลูกหนี้สาขา")])], 1)])], 1)]), _vm._v(" "), _c("div", {
    staticClass: "w-full flex flex-wrap",
    attrs: {
      cellpadding: "10",
      cellspacing: "10"
    }
  }, [_c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  }, [_c("router-link", {
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
  }, [_c("div", {
    staticClass: "flex justify-center w-11 flex-no-shrink mr-6"
  }, [_c("svg", {
    attrs: {
      xmlns: "http://www.w3.org/2000/svg",
      width: "40",
      height: "40",
      viewBox: "0 0 20 20"
    }
  }, [_c("path", {
    attrs: {
      fill: "var(--primary)",
      d: "M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "mt-3"
  }, [_c("heading", {
    staticClass: "mb-3",
    attrs: {
      level: 3
    }
  }, [_vm._v("รายงานลูกหนี้สาขาค้างชำระ")])], 1)])], 1), _vm._v(" "), _c("div", {
    staticClass: "align-top w-1/2 border-r border-b border-50"
  })])])], 1)]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/card.js":
/*!******************************!*\
  !*** ./resources/js/card.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_Card_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/Card.vue */ "./resources/js/components/Card.vue");

Nova.booting(function (Vue, router, store) {
  Vue.component('report', _components_Card_vue__WEBPACK_IMPORTED_MODULE_0__["default"]);
});

/***/ }),

/***/ "./resources/sass/card.scss":
/*!**********************************!*\
  !*** ./resources/sass/card.scss ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/js/components/Card.vue":
/*!******************************************!*\
  !*** ./resources/js/components/Card.vue ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Card_vue_vue_type_template_id_b9bc2c0a__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Card.vue?vue&type=template&id=b9bc2c0a */ "./resources/js/components/Card.vue?vue&type=template&id=b9bc2c0a");
/* harmony import */ var _Card_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Card.vue?vue&type=script&lang=js */ "./resources/js/components/Card.vue?vue&type=script&lang=js");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Card_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _Card_vue_vue_type_template_id_b9bc2c0a__WEBPACK_IMPORTED_MODULE_0__.render,
  _Card_vue_vue_type_template_id_b9bc2c0a__WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Card.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/Card.vue?vue&type=script&lang=js":
/*!******************************************************************!*\
  !*** ./resources/js/components/Card.vue?vue&type=script&lang=js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Card_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Card.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Card.vue?vue&type=script&lang=js");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Card_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Card.vue?vue&type=template&id=b9bc2c0a":
/*!************************************************************************!*\
  !*** ./resources/js/components/Card.vue?vue&type=template&id=b9bc2c0a ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Card_vue_vue_type_template_id_b9bc2c0a__WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   staticRenderFns: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Card_vue_vue_type_template_id_b9bc2c0a__WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Card_vue_vue_type_template_id_b9bc2c0a__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Card.vue?vue&type=template&id=b9bc2c0a */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Card.vue?vue&type=template&id=b9bc2c0a");


/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ normalizeComponent)
/* harmony export */ });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent(
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */,
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options =
    typeof scriptExports === 'function' ? scriptExports.options : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) {
    // server build
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
    hook = shadowMode
      ? function () {
          injectStyles.call(
            this,
            (options.functional ? this.parent : this).$root.$options.shadowRoot
          )
        }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection(h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing ? [].concat(existing, hook) : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/card": 0,
/******/ 			"css/card": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/card"], () => (__webpack_require__("./resources/js/card.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/card"], () => (__webpack_require__("./resources/sass/card.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
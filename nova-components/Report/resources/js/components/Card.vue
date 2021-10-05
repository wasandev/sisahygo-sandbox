<template>
  <div class="flex justify-center items-centers">
    <div class="w-full max-w-xl">
        <heading class="flex mb-3">Quick Reports Menu</heading>
        <p class="text-90 leading-tight mb-8">
            รวม Link รายงานต่างๆ
        </p>

        <card>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'order_headers',
                        lens: 'order-billing-cash'
                        },
                        }"
                    :title="รายงานเงินสดรับตามพนักงาน">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานเงินสดรับ ตามพนักงาน</heading>

                    </div>
                </router-link>
            </div>

            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'order_headers',
                        lens: 'accounts-order-report-bill-by-day'
                        },
                        }"
                    :title="รายงานรายการขนส่งประจำวัน">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานรายการขนส่งประจำวัน</heading>

                    </div>
                </router-link>
            </div>
        </div>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'order_headers',
                        lens: 'accounts-order-report-by-day'
                        },
                        }"
                    :title="รายงานยอดค่าขนส่งตามวัน">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานยอดค่าขนส่งตามวัน</heading>

                    </div>
                </router-link>
            </div>

            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'order_headers',
                        lens: 'accounts-order-report-by-branchrec'
                        },
                        }"
                    :title="รายงานยอดค่าขนส่งตามสาขาปลายทาง">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานยอดค่าขนส่งตามสาขาปลายทาง</heading>

                    </div>
                </router-link>
            </div>
        </div>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'order_headers',
                        lens: 'accounts-order-report-cancel-by-day'
                        },
                        }"
                    :title="รายงานรายการยกเลิกใบรับส่ง">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานรายการยกเลิกใบรับส่ง</heading>

                    </div>
                </router-link>
            </div>
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'order_headers',
                        lens: 'accounts-order-report-cash-by-day'
                        },
                        }"
                    :title="รายงานขายสดประจำวัน">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานขายสดประจำวัน</heading>

                    </div>
                </router-link>
            </div>
        </div>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'order_headers',
                        lens: 'accounts-order-report-cr-by-day'
                        },
                        }"
                    :title="รายงานขายเชื่อประจำวัน">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานขายเชื่อประจำวัน</heading>

                    </div>
                </router-link>
            </div>

            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'waybills',
                        lens: 'waybill-confirmed-per-day'
                        },
                        }"
                    :title="รายงานรถออกประจำวัน">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานรถออกประจำวัน</heading>

                    </div>
                </router-link>
            </div>
        </div>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'carreceives',
                        lens: 'accounts-carreceive-report-by-day'
                        },
                        }"
                    :title="รายงานสรุปรับเงินรถ">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานสรุปรับเงินรถ</heading>

                    </div>
                </router-link>
            </div>
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'carpayments',
                        lens: 'accounts-carpayment-report-by-day'
                        },
                        }"
                    :title="รายงานสรุปจ่ายเงินรถ">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานสรุปจ่ายเงินรถ</heading>

                    </div>
                </router-link>
            </div>
        </div>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'car_balances',
                        lens: 'cars-carcard-report'
                        },
                        }"
                    :title="รายงานสรุปรับเงินรถ">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานบัญชีคุมรถบรรทุก</heading>

                    </div>
                </router-link>
            </div>
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'car_balances',
                        lens: 'cars-summary-report'
                        },
                        }"
                    :title="รายงานสรุปยอดคงเหลือรถ">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานสรุปยอดคงเหลือรถ</heading>

                    </div>
                </router-link>
            </div>
        </div>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'vendors',
                        lens: 'carpay-tax'
                        },
                        }"
                    :title="รายงานภาษีหักณที่จ่ายรถ">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานภาษีหัก ณ ที่จ่ายรถ</heading>

                    </div>
                </router-link>
            </div>
            <div class="align-top w-1/2 border-r border-b border-50">

            </div>
        </div>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'ar_balances',
                        lens: 'ar-outstanding-report'
                        },
                        }"
                    :title="รายงานลูกหนี้ค้างชำระ">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานลูกหนี้ค้างชำระ</heading>

                    </div>
                </router-link>
            </div>
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                    :to="{
                        name: 'lens',
                        params:{
                        resourceName: 'receipt_ars',
                        lens: 'ar-receipt-report'
                        },
                        }"
                    :title="รายงานรับชำระหนี้ลูกหนี้การค้า">
                    <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 20 20"
                        >
                        <path
                            fill="var(--primary)"
                            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                        />
                        </svg>

                    </div>
                    <div class="mt-3">
                        <heading :level="3" class="mb-3">รายงานรับชำระหนี้ลูกหนี้การค้า</heading>

                    </div>
                </router-link>
            </div>
        </div>
            <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
                <div class="align-top w-1/2 border-r border-b border-50">
                    <router-link
                        class="no-underline dim flex p-6"
                        :to="{
                            name: 'lens',
                            params:{
                            resourceName: 'ar_balances',
                            lens: 'ar-card-report'
                            },
                            }"
                        :title="รายงานทะเบียนคุมลูกหนี้">
                        <div class="flex justify-center w-11 flex-no-shrink mr-6">
                            <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="40"
                            height="40"
                            viewBox="0 0 20 20"
                            >
                            <path
                                fill="var(--primary)"
                                d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                            />
                            </svg>

                        </div>
                        <div class="mt-3">
                            <heading :level="3" class="mb-3">รายงานทะเบียนคุมลูกหนี้</heading>

                        </div>
                    </router-link>
                </div>
                <div class="align-top w-1/2 border-r border-b border-50">
                    <router-link
                        class="no-underline dim flex p-6"
                        :to="{
                            name: 'lens',
                            params:{
                            resourceName: 'ar_balances',
                            lens: 'ar-summary-report'
                            },
                            }"
                        :title="รายงานสรุปยอดลูกหนี้การค้า">
                        <div class="flex justify-center w-11 flex-no-shrink mr-6">
                            <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="40"
                            height="40"
                            viewBox="0 0 20 20"
                            >
                            <path
                                fill="var(--primary)"
                                d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                            />
                            </svg>

                        </div>
                        <div class="mt-3">
                            <heading :level="3" class="mb-3">รายงานสรุปยอดลูกหนี้การค้า</heading>

                        </div>
                    </router-link>
                </div>
            </div>
            <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
                <div class="align-top w-1/2 border-r border-b border-50">
                    <router-link
                        class="no-underline dim flex p-6"
                        :to="{
                            name: 'lens',
                            params:{
                            resourceName: 'branch_balances',
                            lens: 'branch-branch-balance-bydate'
                            },
                            }"
                        :title="รายงานตั้งหนี้ลูกหนี้สาขา">
                        <div class="flex justify-center w-11 flex-no-shrink mr-6">
                            <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="40"
                            height="40"
                            viewBox="0 0 20 20"
                            >
                            <path
                                fill="var(--primary)"
                                d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                            />
                            </svg>

                        </div>
                        <div class="mt-3">
                            <heading :level="3" class="mb-3">รายงานตั้งหนี้ลูกหนี้สาขา</heading>

                        </div>
                    </router-link>
                </div>
                <div class="align-top w-1/2 border-r border-b border-50">
                    <router-link
                        class="no-underline dim flex p-6"
                        :to="{
                            name: 'lens',
                            params:{
                            resourceName: 'branch_balances',
                            lens: 'branch-branch-balance-receipt'
                            },
                            }"
                        :title="รายงานรับชำระหนี้ลูกหนี้สาขา">
                        <div class="flex justify-center w-11 flex-no-shrink mr-6">
                            <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="40"
                            height="40"
                            viewBox="0 0 20 20"
                            >
                            <path
                                fill="var(--primary)"
                                d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                            />
                            </svg>

                        </div>
                        <div class="mt-3">
                            <heading :level="3" class="mb-3">รายงานรับชำระหนี้ลูกหนี้สาขา</heading>

                        </div>
                    </router-link>
                </div>
            </div>
            <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
                <div class="align-top w-1/2 border-r border-b border-50">
                    <router-link
                        class="no-underline dim flex p-6"
                        :to="{
                            name: 'lens',
                            params:{
                            resourceName: 'branch_balances',
                            lens: 'branch-branch-balance-report'
                            },
                            }"
                        :title="รายงานลูกหนี้สาขาค้างชำระ">
                        <div class="flex justify-center w-11 flex-no-shrink mr-6">
                            <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="40"
                            height="40"
                            viewBox="0 0 20 20"
                            >
                            <path
                                fill="var(--primary)"
                                d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
                            />
                            </svg>

                        </div>
                        <div class="mt-3">
                            <heading :level="3" class="mb-3">รายงานลูกหนี้สาขาค้างชำระ</heading>

                        </div>
                    </router-link>
                </div>
                <div class="align-top w-1/2 border-r border-b border-50">

                </div>
            </div>
      </card>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Report',

  props: {
    card: Object,
  },


}
</script>

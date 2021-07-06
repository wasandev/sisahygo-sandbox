<template>
  <div class="flex justify-center items-centers">
    <div class="w-full max-w-xl">
      <heading class="flex mb-3">Quick Menu</heading>
      <p class="text-90 leading-tight mb-8">
        สำหรับฝ่ายการเงิน
      </p>

      <card>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                      :to="{
                        name: 'index',
                        params:{
                          resourceName: 'order_banktransfers'
                          },
                        }"
                      :title="รายการโอนเงินค่าขนส่ง">
                      <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="40"
                          height="40"
                          viewBox="0 0 20 20"
                        >
                         <path
                            fill="var(--primary)"
                            d="M0 2C0 .9.9 0 2 0h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm14 12h4V2H2v12h4c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2zM5 9l2-2 2 2 4-4 2 2-6 6-4-4z"
                          />
                        </svg>
                      </div>
                      <div>
                        <heading :level="3" class="mb-3">รายการโอนเงินค่าขนส่ง</heading>
                          <p class="text-90 leading-normal">
                            ค้นหา ดู จัดการ รายการโอนเงินค่าขนส่ง
                          </p>
                      </div>
              </router-link>
            </div>

            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                      :to="{
                        name: 'index',
                        params:{
                          resourceName: 'receipt_alls'
                          },
                        }"
                      :title="ใบเสร็จรับเงิน">
                      <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="40"
                          height="40"
                          viewBox="0 0 20 20"
                        >
                          <path
                            fill="var(--primary)"
                            d="M9 10V8h2v2h2v2h-2v2H9v-2H7v-2h2zm-5 8h12V6h-4V2H4v16zm-2 1V0h12l4 4v16H2v-1z"
                          />
                        </svg>
                      </div>
                      <div>
                        <heading :level="3" class="mb-3">ใบเสร็จรับเงิน</heading>
                          <p class="text-90 leading-normal">
                            ดู แก้ไข เพิ่ม พิมพ์ ใบเสร็จรับเงิน
                          </p>
                      </div>
              </router-link>
            </div>
        </div>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                      :to="{
                        name: 'index',
                        params:{
                          resourceName: 'carpayments'
                          },
                        }"
                      :title="จ่ายเงินรถ">
                      <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="40"
                          height="40"
                          viewBox="0 0 20 20"
                        >
                          <path
                            fill="var(--primary)"
                            d="M0 10a10 10 0 1 1 20 0 10 10 0 0 1-20 0zm2 0a8 8 0 1 0 16 0 8 8 0 0 0-16 0zm8-2h5v4h-5v3l-5-5 5-5v3z"
                          />
                        </svg>
                      </div>
                      <div>
                        <heading :level="3" class="mb-3">จ่ายเงินรถ</heading>
                          <p class="text-90 leading-normal">
                            ค้นหา ดู แก้ไข พิมพ์ รายการจ่ายเงินรถ
                          </p>
                      </div>
              </router-link>
            </div>
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                      :to="{
                        name: 'index',
                        params:{
                          resourceName: 'carreceives'
                          },
                        }"
                      :title="รับเงินรถ">
                      <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="40"
                          height="40"
                          viewBox="0 0 20 20"
                        >
                          <path
                            fill="var(--primary)"
                            d="M20 10a10 10 0 1 1-20 0 10 10 0 0 1 20 0zm-2 0a8 8 0 1 0-16 0 8 8 0 0 0 16 0zm-8 2H5V8h5V5l5 5-5 5v-3z"
                          />
                        </svg>
                      </div>
                      <div>
                        <heading :level="3" class="mb-3">รับเงินรถ</heading>
                          <p class="text-90 leading-normal">
                            ค้นหา ดู แก้ไข พิมพ์ รายการรับเงินรถ
                          </p>
                      </div>
              </router-link>
            </div>
        </div>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                      :to="{
                        name: 'index',
                        params:{
                          resourceName: 'order_headers'
                          },
                        }"
                      :title="ใบรับส่งสินค้า">
                      <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="40"
                          height="40"
                          viewBox="0 0 40 40"
                        >
                          <path
                            fill="var(--primary)"
                            d="M29 7h5c.5522847 0 1 .44771525 1 1s-.4477153 1-1 1h-5v5c0 .5522847-.4477153 1-1 1s-1-.4477153-1-1V9h-5c-.5522847 0-1-.44771525-1-1s.4477153-1 1-1h5V2c0-.55228475.4477153-1 1-1s1 .44771525 1 1v5zM4 0h8c2.209139 0 4 1.790861 4 4v8c0 2.209139-1.790861 4-4 4H4c-2.209139 0-4-1.790861-4-4V4c0-2.209139 1.790861-4 4-4zm0 2c-1.1045695 0-2 .8954305-2 2v8c0 1.1.9 2 2 2h8c1.1045695 0 2-.8954305 2-2V4c0-1.1045695-.8954305-2-2-2H4zm20 18h8c2.209139 0 4 1.790861 4 4v8c0 2.209139-1.790861 4-4 4h-8c-2.209139 0-4-1.790861-4-4v-8c0-2.209139 1.790861-4 4-4zm0 2c-1.1045695 0-2 .8954305-2 2v8c0 1.1.9 2 2 2h8c1.1045695 0 2-.8954305 2-2v-8c0-1.1045695-.8954305-2-2-2h-8zM4 20h8c2.209139 0 4 1.790861 4 4v8c0 2.209139-1.790861 4-4 4H4c-2.209139 0-4-1.790861-4-4v-8c0-2.209139 1.790861-4 4-4zm0 2c-1.1045695 0-2 .8954305-2 2v8c0 1.1.9 2 2 2h8c1.1045695 0 2-.8954305 2-2v-8c0-1.1045695-.8954305-2-2-2H4z"
                          />
                        </svg>

                      </div>
                      <div>
                        <heading :level="3" class="mb-3">ใบรับส่งสินค้า</heading>
                          <p class="text-90 leading-normal">
                            ค้นหา ดูรายการใบรับส่งสินค้า
                          </p>
                      </div>
              </router-link>
            </div>

            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                      :to="{
                        name: 'index',
                        params:{
                          resourceName: 'users'
                          },
                        }"
                      :title="ข้อมูลผู้ใช้">
                      <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="40"
                          height="40"
                          viewBox="0 0 20 20"
                        >
                         <path
                            fill="var(--primary)"
                            d="M5 5a5 5 0 0 1 10 0v2A5 5 0 0 1 5 7V5zM0 16.68A19.9 19.9 0 0 1 10 14c3.64 0 7.06.97 10 2.68V20H0v-3.32z"
                          />
                        </svg>
                      </div>
                      <div>
                        <heading :level="3" class="mb-3">ข้อมูลผู้ใช้</heading>
                          <p class="text-90 leading-normal">
                            แก้ไขรายละเอียดบัญชีผู้ใช้ของตัวเอง
                          </p>
                      </div>
              </router-link>
            </div>
        </div>
      </card>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Financial',

  props: {
    card: Object,
  },


}
</script>

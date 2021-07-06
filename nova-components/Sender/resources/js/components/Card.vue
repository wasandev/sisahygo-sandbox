<template>
  <div class="flex justify-center items-centers">
    <div class="w-full max-w-xl">
      <heading class="flex mb-3">Quick Menu</heading>
      <p class="text-90 leading-tight mb-8">
        สำหรับพนักงานจัดส่งสินค้า
      </p>

      <card>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                      :to="{
                        name: 'index',
                        params:{
                          resourceName: 'deliveries'
                          },
                        }"
                      :title="รายการจัดส่งสินค้า">
                      <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="40"
                          height="40"
                          viewBox="0 0 20 20"
                        >
                          <path
                            fill="var(--primary)"
                            d="M0 0l20 8-8 4-2 8z"
                        />

                        </svg>

                      </div>
                      <div>
                        <heading :level="3" class="mb-3">รายการจัดส่งสินค้า</heading>
                          <p class="text-90 leading-normal">
                            รายการจัดส่งสินค้า
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
                          resourceName: 'customers'
                          },
                        }"
                      :title="ข้อมูลลูกค้า">
                      <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="40"
                          height="40"
                          viewBox="0 0 20 20"
                        >
                          <path
                            fill="var(--primary)"
                            d="M7 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0 1c2.15 0 4.2.4 6.1 1.09L12 16h-1.25L10 20H4l-.75-4H2L.9 10.09A17.93 17.93 0 0 1 7 9zm8.31.17c1.32.18 2.59.48 3.8.92L18 16h-1.25L16 20h-3.96l.37-2h1.25l1.65-8.83zM13 0a4 4 0 1 1-1.33 7.76 5.96 5.96 0 0 0 0-7.52C12.1.1 12.53 0 13 0z"
                          />
                        </svg>
                      </div>
                      <div>
                        <heading :level="3" class="mb-3">ข้อมูลลูกค้า</heading>
                          <p class="text-90 leading-normal">
                            ค้นหา ดู QR Code และรายละเอียดต่างๆของลูกค้า
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
  name: 'Sender',

  props: {
    card: Object,
  },


}
</script>

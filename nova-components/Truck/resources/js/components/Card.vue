<template>
  <div class="flex justify-center items-centers">
    <div class="w-full max-w-xl">
      <heading class="flex mb-3">Quick Menu</heading>
      <p class="text-90 leading-tight mb-8">
        สำหรับฝ่ายรถบรรทุก
      </p>

      <card>
        <div class="w-full flex flex-wrap" cellpadding="10" cellspacing="10">
            <div class="align-top w-1/2 border-r border-b border-50">
                <router-link
                    class="no-underline dim flex p-6"
                      :to="{
                        name: 'index',
                        params:{
                          resourceName: 'cars'
                          },
                        }"
                      :title="ข้อมูลรถ">
                      <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="40"
                          height="40"
                          viewBox="0 0 20 20"
                        >
                          <path
                            fill="var(--primary)"
                            d="M13 18H7v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-1a2 2 0 0 1-2-2V2c0-1.1.9-2 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2v1a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1zM4 5v6h5V5H4zm7 0v6h5V5h-5zM5 2v1h10V2H5zm.5 14a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm9 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"
                          />
                        </svg>

                      </div>
                      <div>
                        <heading :level="3" class="mb-3">ข้อมูลรถ</heading>
                          <p class="text-90 leading-normal">
                            ค้นหา ดู จัดการข้อมูลรถ
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
                          resourceName: 'car_balances'
                          },
                        }"
                      :title="บัญชีรถ">
                      <div class="flex justify-center w-11 flex-no-shrink mr-6">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="40"
                          height="40"
                          viewBox="0 0 20 20"
                        >
                          <path
                            fill="var(--primary)"
                            d="M16 2h4v15a3 3 0 0 1-3 3H3a3 3 0 0 1-3-3V0h16v2zm0 2v13a1 1 0 0 0 1 1 1 1 0 0 0 1-1V4h-2zM2 2v15a1 1 0 0 0 1 1h11.17a2.98 2.98 0 0 1-.17-1V2H2zm2 8h8v2H4v-2zm0 4h8v2H4v-2zM4 4h8v4H4V4z"
                          />
                        </svg>

                      </div>
                      <div>
                        <heading :level="3" class="mb-3">บัญชีรถ</heading>
                          <p class="text-90 leading-normal">
                            ค้นหา ดู บัญชีรับ-จ่ายของรถ
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
                          resourceName: 'charter_jobs'
                          },
                        }"
                      :title="ใบงานขนส่งเหมาคัน">
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
                        <heading :level="3" class="mb-3">ใบงานขนส่งเหมาคัน</heading>
                          <p class="text-90 leading-normal">
                            จัดการข้อมูลใบงานขนส่งเหมาคัน
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
  name: 'truck',

  props: {
    card: Object,
  },


}
</script>

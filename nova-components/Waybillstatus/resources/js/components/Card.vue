<template>
    <card class="flex flex-col  justify-center">
        <div class="px-3 py-3">
            <h1 class="text-left text-2xl text-80 font-light">
                สถานะใบกำกับ
            </h1>

            <table class="w-full" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        1.Loading = กำลังจัดขึ้นสินค้า
                    </td>
                    <td>
                        2.Confirmed = ยืนยัน/ออกใบกำกับแล้ว
                    </td>
                </tr>

                <tr>
                    <td>
                        3.In transit = รถออกจากสาขาต้นทางแล้ว
                    </td>
                    <td>
                        4.Arrival = รถถึงสาขาปลายทางแล้ว
                    </td>
                </tr>
                <tr>
                    <td>
                        5.Delivery = อยู่ระหว่างการจัดส่งสินค้าปลายทาง
                    </td>
                    <td>
                        6.Completed = จัดส่งสินค้าเรียบร้อยแล้ว
                    </td>
                </tr>
                <tr>
                    <td>
                        7.Cancel = ยกเลิกรายการแล้ว
                    </td>
                    <td>
                        8.Problem = มีปัญหาการขนส่ง
                    </td>
                </tr>

            </table>
        </div>
    </card>
</template>

<script>
export default {
    props: [
        "card"


    ],

    mounted() {
        //
    }
};
</script>

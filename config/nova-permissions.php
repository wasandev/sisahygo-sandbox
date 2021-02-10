<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User model class
    |--------------------------------------------------------------------------
    */

    'user_model' => 'App\Models\User',

    /*
    |--------------------------------------------------------------------------
    | Nova User resource tool class
    |--------------------------------------------------------------------------
    */

    'user_resource' => 'App\Nova\User',

    /*
    |--------------------------------------------------------------------------
    | The group associated with the resource
    |--------------------------------------------------------------------------
    */

    'role_resource_group' => '1.งานสำหรับผู้ดูแลระบบ',


    /*
    |--------------------------------------------------------------------------
    | Database table names
    |--------------------------------------------------------------------------
    | When using the "HasRoles" trait from this package, we need to know which
    | table should be used to retrieve your roles. We have chosen a basic
    | default value but you may easily change it to any table you like.
    */

    'table_names' => [
        'roles' => 'roles',

        'role_permission' => 'role_permission',

        'role_user' => 'role_user',

        'users' => 'users',
    ],
    /*
    |--------------------------------------------------------------------------
    | Application Permissions
    |--------------------------------------------------------------------------
    */

    'permissions' => [
        //1.สำหรับผู้ดูแลระบบ
        //-users
        'view users' => [
            'display_name' => 'ดู',
            'description'  => 'ดูข้อมูลผู้ใช้งาน',
            'group'        => 'ผู้ใช้งาน',
        ],

        'create users' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้างผู้ใช้งาน',
            'group'        => 'ผู้ใช้งาน',
        ],

        'edit users' => [
            'display_name' => 'แก้ไข',
            'description'  => 'Can edit users',
            'group'        => 'ผู้ใช้งาน',
        ],

        'delete users' => [
            'display_name' => 'ลบ',
            'description'  => 'Can delete users',
            'group'        => 'ผู้ใช้งาน',
        ],
        //Role
        'view roles' => [
            'display_name' => 'ดู',
            'description'  => 'Can view roles',
            'group'        => 'สิทธิ์การใช้งาน',
        ],

        'create roles' => [
            'display_name' => 'สร้าง',
            'description'  => 'Can create roles',
            'group'        => 'สิทธิ์การใช้งาน',
        ],

        'edit roles' => [
            'display_name' => 'แก้ไข',
            'description'  => 'Can edit roles',
            'group'        => 'สิทธิ์การใช้งาน',
        ],

        'delete roles' => [
            'display_name' => 'ลบ',
            'description'  => 'Can delete roles',
            'group'        => 'สิทธิ์การใช้งาน',
        ],
        //CompanyProfile
        'view companyprofile' => [
            'display_name' => 'ดู',
            'description'  => 'ดูข้อมูลบริษัท',
            'group'        => 'ข้อมูลบริษัท',
        ],

        'edit companyprofile' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไขข้อมูลบริษัท',
            'group'        => 'ข้อมูลบริษัท',
        ],

        //Branch
        'view branches' => [
            'display_name' => 'ดู',
            'description'  => 'ดูข้อมูลสาขา',
            'group'        => 'ข้อมูลสาขา',
        ],

        'create branches' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้างข้อมูลสาขา',
            'group'        => 'ข้อมูลสาขา',
        ],

        'edit branches' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไขข้อมูลสาขา',
            'group'        => 'ข้อมูลสาขา',
        ],

        'delete branches' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบข้อมูลสาขา',
            'group'        => 'ข้อมูลสาขา',
        ],
        //พื้นที่บริการของสาขา
        'view branchareas' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'พื้นที่บริการของสาขา',
        ],

        'create branchareas' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'พื้นที่บริการของสาขา',
        ],

        'edit branchareas' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'พื้นที่บริการของสาขา',
        ],

        'delete branchareas' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'พื้นที่บริการของสาขา',
        ],


        ///ฝ่าย/แผนก
        'view departments' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ฝ่าย/แผนก',
        ],

        'create departments' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ฝ่าย/แผนก',
        ],

        'edit departments' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ฝ่าย/แผนก',
        ],

        'delete departments' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ฝ่าย/แผนก',
        ],

        //ตำแหน่งงาน
        'view positions' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ตำแหน่งงาน',
        ],

        'create positions' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ตำแหน่งงาน',
        ],

        'edit positions' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ตำแหน่งงาน',
        ],

        'delete positions' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ตำแหน่งงาน',
        ],
        //ประเภทใบขับขี่
        'view driving_license_types' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ประเภทใบขับขี่',
        ],

        'create driving_license_types' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ประเภทใบขับขี่',
        ],

        'edit driving_license_types' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ประเภทใบขับขี่',
        ],

        'delete driving_license_types' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ประเภทใบขับขี่',
        ],
        //พนักงาน
        'view employees' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'พนักงาน',
        ],

        'create employees' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'พนักงาน',
        ],

        'edit employees' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'พนักงาน',
        ],

        'delete employees' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'พนักงาน',
        ],
        //ประเภทรถ
        'view cartypes' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ประเภทรถ',
        ],

        'create cartypes' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ประเภทรถ',
        ],

        'edit cartypes' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ประเภทรถ',
        ],

        'delete cartypes' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ประเภทรถ',
        ],
        //ลักษณะรถ
        'view carstyles' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ลักษณะรถ',
        ],

        'create carstyles' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ลักษณะรถ',
        ],

        'edit carstyles' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ลักษณะรถ',
        ],

        'delete carstyles' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ลักษณะรถ',
        ],
        //ตำแหน่งยาง
        'view tiretypes' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ตำแหน่งยาง',
        ],

        'create tiretypes' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ตำแหน่งยาง',
        ],

        'edit tiretypes' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ตำแหน่งยาง',
        ],

        'delete tiretypes' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ตำแหน่งยาง',
        ],
        //ข้อมูลรถ
        'view cars' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ข้อมูลรถ',
        ],

        'create cars' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ข้อมูลรถ',
        ],

        'edit cars' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ข้อมูลรถ',
        ],

        'delete cars' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ข้อมูลรถ',
        ],

        //ประเภทธุรกิจ
        'view businesstypes' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ประเภทธุรกิจ',
        ],

        'create businesstypes' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ประเภทธุรกิจ',
        ],

        'edit businesstypes' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ประเภทธุรกิจ',
        ],

        'delete businesstypes' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ประเภทธุรกิจ',
        ],
        //ข้อมูลลุกค้า
        'view customers' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ข้อมูลลูกค้า',
        ],

        'create customers' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ข้อมูลลูกค้า',
        ],

        'edit customers' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ข้อมูลลูกค้า',
        ],

        'delete customers' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ข้อมูลลูกค้า',
        ],
        //ประเภทสินค้า
        'view categories' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ประเภทสินค้า',
        ],

        'create categories' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ประเภทสินค้า',
        ],

        'edit categories' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ประเภทสินค้า',
        ],

        'delete categories' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ประเภทสินค้า',
        ],
        //ลักษณะสินค้า
        'view productstyles' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ลักษณะสินค้า',
        ],

        'create productstyles' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ลักษณะสินค้า',
        ],

        'edit productstyles' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ลักษณะสินค้า',
        ],

        'delete productstyles' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ลักษณะสินค้า',
        ],
        //หน่วยนับ
        'view units' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'หน่วยนับ',
        ],

        'create units' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'หน่วยนับ',
        ],

        'edit units' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'หน่วยนับ',
        ],

        'delete units' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'หน่วยนับ',
        ],
        //pricezones
        'view pricezones' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'โซนราคา',
        ],

        'create pricezones' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'โซนราคา',
        ],

        'edit pricezones' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'โซนราคา',
        ],

        'delete pricezones' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'โซนราคา',
        ],
        //สินค้า
        'view products' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'สินค้า',
        ],

        'create products' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'สินค้า',
        ],

        'edit products' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'สินค้า',
        ],

        'delete products' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'สินค้า',
        ],
        //พัสดุ
        'view parcels' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'พัสดุ',
        ],

        'create parcels' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'พัสดุ',
        ],

        'edit parcels' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'พัสดุ',
        ],

        'delete parcels' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'พัสดุ',
        ],
        //ค่าขนส่งตามสินค้า
        'view productservice_prices' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ค่าขนส่งตามสินค้า',
        ],

        'create productservice_prices' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ค่าขนส่งตามสินค้า',
        ],

        'edit productservice_prices' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ค่าขนส่งตามสินค้า',
        ],

        'delete productservice_prices' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ค่าขนส่งตามสินค้า',
        ],


        //ค่าขนส่งตามสินค้าตามลูกค้า
        'view customerproduct_prices' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ค่าขนส่งตามสินค้าตามลูกค้า',
        ],

        'create customerproduct_prices' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ค่าขนส่งตามสินค้าตามลูกค้า',
        ],

        'edit customerproduct_prices' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ค่าขนส่งตามสินค้าตามลูกค้า',
        ],

        'delete customerproduct_prices' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ค่าขนส่งตามสินค้าตามลูกค้า',
        ],
        //ค่าขนส่งพัสดุ
        'view serviceprices' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'รูปแบบราคาขนส่งพัสดุ',
        ],

        'create serviceprices' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'รูปแบบราคาขนส่งพัสดุ',
        ],

        'edit serviceprices' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'รูปแบบราคาขนส่งพัสดุ',
        ],

        'delete serviceprices' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'รูปแบบราคาขนส่งพัสดุ',
        ],
        //ตารางราคาค่าขนส่งพัสดุ
        'view serviceprice_items' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ตารางราคาขนส่งพัสดุ',
        ],

        'create serviceprice_items' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ตารางราคาขนส่งพัสดุ',
        ],

        'edit serviceprice_items' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ตารางราคาขนส่งพัสดุ',
        ],

        'delete serviceprice_items' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ตารางราคาขนส่งพัสดุ',
        ],
        //ค่าบริการอื่นๆ
        'view service_charges' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ค่าบริการอื่นๆ',
        ],

        'create service_charges' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ค่าบริการอื่นๆ',
        ],

        'edit service_charges' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ค่าบริการอื่นๆ',
        ],

        'delete service_charges' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ค่าบริการอื่นๆ',
        ],
        //เส้นทางขนส่งระหว่างสาขา
        'view routeto_branches' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'เส้นทางขนส่งระหว่างสาขา',
        ],

        'create routeto_branches' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'เส้นทางขนส่งระหว่างสาขา',
        ],

        'edit routeto_branches' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'เส้นทางขนส่งระหว่างสาขา',
        ],

        'delete routeto_branches' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'เส้นทางขนส่งระหว่างสาขา',
        ],
        //เส้นทางขนส่งของสาขา
        'view branch_routes' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'เส้นทางขนส่งของสาขา',
        ],

        'create branch_routes' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'เส้นทางขนส่งของสาขา',
        ],

        'edit branch_routes' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'เส้นทางขนส่งของสาขา',
        ],

        'delete branch_routes' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'เส้นทางขนส่งของสาขา',
        ],
        //ต้นทุนตามเส้นทางระหว่างสาขา
        'view routeto_branch_costs' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ต้นทุนตามเส้นทางระหว่างสาขา',
        ],

        'create routeto_branch_costs' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ต้นทุนตามเส้นทางระหว่างสาขา',
        ],

        'edit routeto_branch_costs' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ต้นทุนตามเส้นทางระหว่างสาขา',
        ],

        'delete routeto_branch_costs' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ต้นทุนตามเส้นทางระหว่างสาขา',
        ],
        //ต้นทุนตามเส้นทางขนส่งของสาขา
        'view branch_route_costs' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ต้นทุนตามเส้นทางขนส่งของสาขา',
        ],

        'create branch_route_costs' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ต้นทุนตามเส้นทางขนส่งของสาขา',
        ],

        'edit branch_route_costs' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ต้นทุนตามเส้นทางขนส่งของสาขา',
        ],

        'delete branch_route_costs' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ต้นทุนตามเส้นทางขนส่งของสาขา',
        ],
        //เส้นทางขนส่งเหมาคัน
        'view charter_routes' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'เส้นทางขนส่งเหมาคัน',
        ],

        'create charter_routes' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'เส้นทางขนส่งเหมาคัน',
        ],

        'edit charter_routes' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'เส้นทางขนส่งเหมาคัน',
        ],

        'delete charter_routes' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'เส้นทางขนส่งเหมาคัน',
        ],
        //ต้นทุนเส้นทางขนส่งเหมาคัน
        'view charter_route_costs' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ต้นทุนเส้นทางขนส่งเหมาคัน',
        ],

        'create charter_route_costs' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ต้นทุนเส้นทางขนส่งเหมาคัน',
        ],

        'edit charter_route_costs' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ต้นทุนเส้นทางขนส่งเหมาคัน',
        ],

        'delete charter_route_costs' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ต้นทุนเส้นทางขนส่งเหมาคัน',
        ],
        //ค่าขนส่งเหมาคัน
        'view charter_prices' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ค่าขนส่งเหมาคัน',
        ],

        'create charter_prices' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ค่าขนส่งเหมาคัน',
        ],

        'edit charter_prices' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ค่าขนส่งเหมาคัน',
        ],

        'delete charter_prices' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ค่าขนส่งเหมาคัน',
        ],
        //ใบเสนอราคาเหมาคัน
        'view quotations' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ใบเสนอราคาเหมาคัน',
        ],

        'create quotations' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ใบเสนอราคาเหมาคัน',
        ],

        'edit quotations' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ใบเสนอราคาเหมาคัน',
        ],

        'delete quotations' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ใบเสนอราคาเหมาคัน',
        ],
        //ใบงานขนส่งเหมาคัน
        'view charter_jobs' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ใบงานขนส่งเหมาคัน',
        ],

        'create charter_jobs' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ใบงานขนส่งเหมาคัน',
        ],

        'edit charter_jobs' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ใบงานขนส่งเหมาคัน',
        ],

        'delete charter_jobs' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ใบงานขนส่งเหมาคัน',
        ],
        //ผู้จำหน่าย
        'view vendors' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ผู้จำหน่าย',
        ],

        'create vendors' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ผู้จำหน่าย',
        ],

        'edit vendors' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ผู้จำหน่าย',
        ],

        'delete vendors' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ผู้จำหน่าย',
        ],
        //ค่าใช้จ่ายรถ
        'view car_expenses' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ค่าใช้จ่ายรถ',
        ],

        'create car_expenses' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ค่าใช้จ่ายรถ',
        ],

        'edit car_expenses' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ค่าใช้จ่ายรถ',
        ],

        'delete car_expenses' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ค่าใช้จ่ายรถ',
        ],
        //ค่าใช้จ่ายส่วนกลาง
        'view company_expenses' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ค่าใช้จ่ายส่วนกลาง',
        ],

        'create company_expenses' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ค่าใช้จ่ายส่วนกลาง',
        ],

        'edit company_expenses' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ค่าใช้จ่ายส่วนกลาง',
        ],

        'delete company_expenses' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ค่าใช้จ่ายส่วนกลาง',
        ],
        //ข้อมูลธนาคาร
        'view banks' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ข้อมูลธนาคาร',
        ],

        'create banks' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ข้อมูลธนาคาร',
        ],

        'edit banks' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ข้อมูลธนาคาร',
        ],

        'delete banks' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ข้อมูลธนาคาร',
        ],
        //ข้อมูลบัญชีธนาคาร
        'view bankaccounts' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ข้อมูลบัญชีธนาคาร',
        ],

        'create bankaccounts' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ข้อมูลบัญชีธนาคาร',
        ],

        'edit bankaccounts' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ข้อมูลบัญชีธนาคาร',
        ],

        'delete bankaccounts' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ข้อมูลบัญชีธนาคาร',
        ],
        //ข้อมูลรายการโอนเงินค่าขนส่ง
        'view order_banktransfers' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ข้อมูลรายการโอนเงินค่าขนส่ง',
        ],

        'create order_banktransfers' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ข้อมูลรายการโอนเงินค่าขนส่ง',
        ],

        'edit order_banktransfers' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ข้อมูลรายการโอนเงินค่าขนส่ง',
        ],

        'delete order_banktransfers' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ข้อมูลรายการโอนเงินค่าขนส่ง',
        ],
        //ตรวจนับสินค้า
        'view own order_checkers' => [
            'display_name' => 'ดูข้อมูลของตัวเอง',
            'description'  => 'ดูข้อมูลของตัวเอง',
            'group'        => 'ตรวจนับสินค้า',
        ],
        'view order_checkers' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ตรวจนับสินค้า',
        ],

        'manage own order_checkers' => [
            'display_name' => 'จัดการข้อมูลของตัวเอง',
            'description'  => 'จัดการข้อมูลของตัวเอง',
            'group'        => 'ตรวจนับสินค้า',
        ],

        'manage order_checkers' => [
            'display_name' => 'จัดการข้อมูล',
            'description'  => 'จัดการข้อมูล',
            'group'        => 'ตรวจนับสินค้า',
        ],
        //ใบรับส่งสินค้า
        'view own order_headers' => [
            'display_name' => 'ดูข้อมูลของตัวเอง',
            'description'  => 'ดูข้อมูลของตัวเอง',
            'group'        => 'ใบรับส่งสินค้า',
        ],
        'view order_headers' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ใบรับส่งสินค้า',
        ],

        'manage own order_headers' => [
            'display_name' => 'จัดการข้อมูลของตัวเอง',
            'description'  => 'จัดการข้อมูลของตัวเอง',
            'group'        => 'ใบรับส่งสินค้า',
        ],

        'manage order_headers' => [
            'display_name' => 'จัดการข้อมูล',
            'description'  => 'จัดการข้อมูล',
            'group'        => 'ใบรับส่งสินค้า',
        ],
        //รายการจัดขึ้น
        'view own order_loaders' => [
            'display_name' => 'ดูข้อมูลของตัวเอง',
            'description'  => 'ดูข้อมูลของตัวเอง',
            'group'        => 'รายการจัดขึ้นสินค้า',
        ],
        'view order_loaders' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'รายการจัดขึ้นสินค้า',
        ],

        'manage own order_loaders' => [
            'display_name' => 'จัดการข้อมูลของตัวเอง',
            'description'  => 'จัดการข้อมูลของตัวเอง',
            'group'        => 'รายการจัดขึ้นสินค้า',
        ],

        'manage order_loaders' => [
            'display_name' => 'จัดการข้อมูล',
            'description'  => 'จัดการข้อมูล',
            'group'        => 'รายการจัดขึ้นสินค้า',
        ],
        //รายการสินค้าในใบรับส่งสินค้า
        'view own order_details' => [
            'display_name' => 'ดูข้อมูลของตัวเอง',
            'description'  => 'ดูข้อมูลของตัวเอง',
            'group'        => 'รายการสินค้าในใบรับส่ง',
        ],
        'view order_details' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'รายการสินค้าในใบรับส่ง',
        ],

        'manage own order_details' => [
            'display_name' => 'จัดการข้อมูลของตัวเอง',
            'description'  => 'จัดการข้อมูลของตัวเอง',
            'group'        => 'รายการสินค้าในใบรับส่ง',
        ],

        'manage order_details' => [
            'display_name' => 'จัดการข้อมูล',
            'description'  => 'จัดการข้อมูล',
            'group'        => 'รายการสินค้าในใบรับส่ง',
        ],
        //ใบกำกับสินค้า
        'view own waybills' => [
            'display_name' => 'ดูข้อมูลของตัวเอง',
            'description'  => 'ดูข้อมูลของตัวเอง',
            'group'        => 'ใบกำกับสินค้า',
        ],
        'view waybills' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ใบกำกับสินค้า',
        ],

        'manage own waybills' => [
            'display_name' => 'จัดการข้อมูลของตัวเอง',
            'description'  => 'จัดการข้อมูลของตัวเอง',
            'group'        => 'ใบกำกับสินค้า',
        ],

        'manage waybills' => [
            'display_name' => 'จัดการข้อมูล',
            'description'  => 'จัดการข้อมูล',
            'group'        => 'ใบกำกับสินค้า',
        ],
        //รายการรถเข้าสาขา
        'view own branchrec_waybills' => [
            'display_name' => 'ดูข้อมูลของตัวเอง',
            'description'  => 'ดูข้อมูลของตัวเอง',
            'group'        => 'รายการรถเข้าสาขา',
        ],
        'view branchrec_waybills' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'รายการรถเข้าสาขา',
        ],

        'manage own branchrec_waybills' => [
            'display_name' => 'จัดการข้อมูลของตัวเอง',
            'description'  => 'จัดการข้อมูลของตัวเอง',
            'group'        => 'รายการรถเข้าสาขา',
        ],

        'manage branchrec_waybills' => [
            'display_name' => 'จัดการข้อมูล',
            'description'  => 'จัดการข้อมูล',
            'group'        => 'รายการรถเข้าสาขา',
        ],

        //รายการใบรับส่งเข้าสาขา
        'view own branchrec_orders' => [
            'display_name' => 'ดูข้อมูลของตัวเอง',
            'description'  => 'ดูข้อมูลของตัวเอง',
            'group'        => 'ใบรับส่งเข้าสาขา',
        ],
        'view branchrec_orders' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ใบรับส่งเข้าสาขา',
        ],

        'manage own branchrec_orders' => [
            'display_name' => 'จัดการข้อมูลของตัวเอง',
            'description'  => 'จัดการข้อมูลของตัวเอง',
            'group'        => 'ใบรับส่งเข้าสาขา',
        ],

        'manage branchrec_orders' => [
            'display_name' => 'จัดการข้อมูล',
            'description'  => 'จัดการข้อมูล',
            'group'        => 'ใบรับส่งเข้าสาขา',
        ],
        //รายการรับเงินค่าขนส่งของสาขา
        'view branchorder_payments' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'รายการรับเงินปลายทาง',
        ],
        'create branchorder_payments' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'รายการรับเงินปลายทาง',
        ],

        'edit branchorder_payments' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'รายการรับเงินปลายทาง',
        ],

        'delete branchorder_payments' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'รายการรับเงินปลายทาง',
        ],
        //รายการใบรับส่งมีปัญหา
        'view orders_problems' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'รายการใบรับส่งมีปัญหา',
        ],
        'create orders_problems' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'รายการใบรับส่งมีปัญหา',
        ],

        'edit orders_problems' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'รายการใบรับส่งมีปัญหา',
        ],

        'delete orders_problems' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'รายการใบรับส่งมีปัญหา',
        ],
        //รายการจัดส่งสินค้า
        'view deliveries' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'รายการจัดส่งสินค้า',
        ],
        'create deliveries' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'รายการจัดส่งสินค้า',
        ],

        'edit deliveries' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'รายการจัดส่งสินค้า',
        ],

        'delete deliveries' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'รายการจัดส่งสินค้า',
        ],
        //ใบรับส่งในรายการจัดส่งสินค้า
        'view delivery_items' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ใบรับส่งในรายการจัดส่งสินค้า',
        ],
        'create delivery_items' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ใบรับส่งในรายการจัดส่งสินค้า',
        ],

        'edit delivery_items' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ใบรับส่งในรายการจัดส่งสินค้า',
        ],

        'delete delivery_items' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ใบรับส่งในรายการจัดส่งสินค้า',
        ],
        //รายการเก็บเงินปลายทาง
        'view branch_balance' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'รายการเก็บเงินปลายทาง',
        ],
        'create branch_balance' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'รายการเก็บเงินปลายทาง',
        ],

        'edit branch_balance' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'รายการเก็บเงินปลายทาง',
        ],

        'delete branch_balance' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'รายการเก็บเงินปลายทาง',
        ],
        //ใบเสร็จรับเงินปลายทาง
        'view receipt' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ใบเสร็จรับเงินปลายทาง',
        ],
        'create receipt' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ใบเสร็จรับเงินปลายทาง',
        ],

        'edit receipt' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ใบเสร็จรับเงินปลายทาง',
        ],

        'delete receipt' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ใบเสร็จรับเงินปลายทาง',
        ],
        //ใบเสร็จรับเงิน
        'view receipt_all' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ใบเสร็จรับเงิน',
        ],
        'create receipt_all' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ใบเสร็จรับเงิน',
        ],

        'edit receipt_all' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ใบเสร็จรับเงิน',
        ],

        'delete receipt_all' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ใบเสร็จรับเงิน',
        ],
        //รายการบิลในใบเสร็จ
        'view receipt_item' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'รายการบิลในใบเสร็จ',
        ],
        'create receipt_item' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'รายการบิลในใบเสร็จ',
        ],

        'edit receipt_item' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'รายการบิลในใบเสร็จ',
        ],

        'delete receipt_item' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'รายการบิลในใบเสร็จ',
        ],
        //รายการเงินสดต้นทาง
        'view order_cash' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'รายการเงินสดต้นทาง',
        ],
        'create order_cash' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'รายการเงินสดต้นทาง',
        ],

        'edit order_cash' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'รายการเงินสดต้นทาง',
        ],

        'delete order_cash' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'รายการเงินสดต้นทาง',
        ],
        //รายการเงินสดปลายทาง
        'view order_branch' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'รายการเงินสดปลายทาง',
        ],
        'create order_branch' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'รายการเงินสดปลายทาง',
        ],

        'edit order_branch' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'รายการเงินสดปลายทาง',
        ],

        'delete order_branch' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'รายการเงินสดปลายทาง',
        ],
        //ข้อมูลลูกค้าวางบิล
        'view ar_customer' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ข้อมูลลูกค้าวางบิล',
        ],
        'create ar_customer' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ข้อมูลลูกค้าวางบิล',
        ],

        'edit ar_customer' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ข้อมูลลูกค้าวางบิล',
        ],

        'delete ar_customer' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ข้อมูลลูกค้าวางบิล',
        ],
        //ข้อมูลรายการวางบิล
        'view ar_balance' => [
            'display_name' => 'ดู',
            'description'  => 'ดู',
            'group'        => 'ข้อมูลรายการวางบิล',
        ],
        'create ar_balance' => [
            'display_name' => 'สร้าง',
            'description'  => 'สร้าง',
            'group'        => 'ข้อมูลรายการวางบิล',
        ],

        'edit ar_balance' => [
            'display_name' => 'แก้ไข',
            'description'  => 'แก้ไข',
            'group'        => 'ข้อมูลรายการวางบิล',
        ],

        'delete ar_balance' => [
            'display_name' => 'ลบ',
            'description'  => 'ลบ',
            'group'        => 'ข้อมูลรายการวางบิล',
        ],
        //Metrics
        'view order-incomes' => [
            'display_name' => 'รายได้ค่าขนส่ง',
            'description'  => 'ดูรายได้ค่าขนส่ง',
            'group'        => 'กราฟสรุป',
        ],
        'view orders-per-day' => [
            'display_name' => 'จำนวนใบรับส่งต่อวัน',
            'description'  => 'จำนวนใบรับส่งต่อวัน',
            'group'        => 'กราฟสรุป',
        ],
        'view charter-incomes' => [
            'display_name' => 'รายได้ค่าขนส่งเหมาคัน',
            'description'  => 'ดูรายได้ค่าขนส่งเหมาคัน',
            'group'        => 'กราฟสรุป',
        ],
        'view charter-jobs-per-day' => [
            'display_name' => 'จำนวนงานเหมาคันต่อวัน',
            'description'  => 'ดูจำนวนงานเหมาคันต่อวัน',
            'group'        => 'กราฟสรุป',
        ],
        'view customers-by-province' => [
            'display_name' => 'จำนวนลูกค้าตามจังหวัด',
            'description'  => 'จำนวนลูกค้าตามจังหวัด',
            'group'        => 'กราฟสรุป',
        ],
        'view customers-by-district' => [
            'display_name' => 'จำนวนลูกค้าตามอำเภอ',
            'description'  => 'จำนวนลูกค้าตามอำเภอ',
            'group'        => 'กราฟสรุป',
        ],
        'view new-customers' => [
            'display_name' => 'จำนวนลูกค้าใหม่',
            'description'  => 'จำนวนลูกค้าใหม่',
            'group'        => 'กราฟสรุป',
        ],
        'view customers-per-day' => [
            'display_name' => 'ลูกค้าใหม่/วัน',
            'description'  => 'ลูกค้าใหม่/วัน',
            'group'        => 'กราฟสรุป',
        ],
        //Actions
    ],
];

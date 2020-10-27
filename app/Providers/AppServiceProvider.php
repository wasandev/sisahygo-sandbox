<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\User;
use App\Observers\UserObserver;
// use App\Models\Post;
// use App\Observers\PostObserver;
// use App\Models\Comment;
// use App\Observers\CommentObserver;
use App\Models\Customer;
use App\Observers\CustomerObserver;
use App\Models\Tranjob;
use App\Observers\TranjobObserver;
use App\Models\CompanyProfile;
use App\Observers\CompanyProfileObserver;
use App\Models\Address;
use App\Observers\AddressObserver;
use App\Models\Branch;
use App\Observers\BranchObserver;
use App\Models\Branch_area;
use App\Observers\BranchAreaObserver;
use App\Models\Businesstype;
use App\Observers\BusinesstypeObserver;
use App\Models\Car_expense;
use App\Observers\Car_expenseObserver;
use App\Models\Car;
use App\Observers\CarObserver;
use App\Models\Carstyle;
use App\Observers\CarstyleObserver;
use App\Models\Cartype;
use App\Observers\CartypeObserver;
use App\Models\Category;
use App\Observers\CategoryObserver;
use App\Models\Company_expense;
use App\Observers\Company_expenseObserver;
use App\Models\Department;
use App\Observers\DepartmentObserver;
use App\Models\Driving_license_type;
use App\Observers\Driving_license_typeObserver;
use App\Models\Employee;
use App\Observers\EmployeeObserver;
use App\Models\Position;
use App\Observers\PositionObserver;
use App\Models\Product_image;
use App\Observers\Product_imageObserver;
use App\Models\Product_style;
use App\Observers\Product_styleObserver;
use App\Models\Product;
use App\Observers\ProductObserver;

use App\Models\Productservice_price;
use App\Observers\ProductServicepriceObserver;

use App\Models\Routeto_branch;
use App\Observers\RoutetoBranchObserver;

use App\Models\Tiretype;
use App\Observers\TiretypeObserver;

use App\Models\Unit;
use App\Observers\UnitObserver;

use App\Models\Vendor;
use App\Observers\VendorObserver;

use App\Models\Branch_route;
use App\Observers\BranchRouteObserver;

use App\Models\Parcel;
use App\Observers\ParcelObserver;

use App\Models\Charter_route;
use App\Observers\CharterRouteObserver;

use App\Models\Charter_price;
use App\Observers\CharterPriceObserver;

use App\Models\Quotation;
use App\Observers\QuotationObserver;

use App\Models\Charter_job;
use App\Observers\CharterJobObserver;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        //Post::observe(PostObserver::class);
        //Comment::observe(CommentObserver::class);
        Customer::observe(CustomerObserver::class);
        Tranjob::observe(TranjobObserver::class);
        CompanyProfile::observe(CompanyProfileObserver::class);
        Address::observe(AddressObserver::class);
        Branch::observe(BranchObserver::class);
        Branch_area::observe(BranchAreaObserver::class);
        Branch_route::observe(BranchRouteObserver::class);
        Routeto_branch::observe(RoutetoBranchObserver::class);
        Businesstype::observe(BusinesstypeObserver::class);
        Car_expense::observe(Car_expenseObserver::class);
        Car::observe(CarObserver::class);
        Carstyle::observe(CarstyleObserver::class);
        Cartype::observe(CartypeObserver::class);
        Category::observe(CategoryObserver::class);
        Company_expense::observe(Company_expenseObserver::class);
        Department::observe(DepartmentObserver::class);
        Driving_license_type::observe(Driving_license_typeObserver::class);
        Employee::observe(EmployeeObserver::class);
        Position::observe(PositionObserver::class);
        Product_image::observe(Product_imageObserver::class);
        Product_style::observe(Product_styleObserver::class);
        Product::observe(ProductObserver::class);
        Productservice_price::observe(ProductServicepriceObserver::class);
        Tiretype::observe(TiretypeObserver::class);
        Unit::observe(UnitObserver::class);
        Vendor::observe(VendorObserver::class);
        Parcel::observe(ParcelObserver::class);
        Charter_route::observe(CharterRouteObserver::class);
        Charter_price::observe(CharterPriceObserver::class);
        Quotation::observe(QuotationObserver::class);
        Charter_job::observe(CharterJobObserver::class);
    }
}

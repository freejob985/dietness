<?php

namespace Database\Seeders;

use App\Models\admins;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ["name"=>"orders", "title_ar" => "الطلبات", "title_en" => "Orders"],
            ["name"=>"admins", "title_ar" => "الاعضاء", "title_en" => "Admins"],
            ["name"=>"drivers", "title_ar" => "السائقين", "title_en" => "Drivers"],
            ["name"=>"governorates", "title_ar" => "المحافظات", "title_en" => "Governorates"],
            ["name"=>"regions", "title_ar" => "المناطق", "title_en" => "Regions"],
            ["name"=>"categories", "title_ar" => "الاقسام", "title_en" => "Categories"],
            ["name"=>"products", "title_ar" => "المنتجات", "title_en" => "Products"],
            ["name"=>"packages", "title_ar" => "الباقات", "title_en" => "Packages"],
            ["name"=>"users", "title_ar" => "المستخدمين", "title_en" => "Users"],
            ["name"=>"subscriptions", "title_ar" => "الاشتركات", "title_en" => "Subscriptions"],
            ["name"=>"boxes", "title_ar" => "البوكسات", "title_en" => "Boxes"],
            ["name"=>"sliders", "title_ar" => "السلايدر", "title_en" => "Sliders"],
            ["name"=>"messages", "title_ar" => "رسائل للجميع", "title_en" => "Messages for everyone"],
            ["name"=>"settings", "title_ar" => "الاعدادات", "title_en" => "Settings"],
            ["name"=>"terms", "title_ar" => "الشروط و الاحكام", "title_en" => "Terms"],
            ["name"=>"wellcome", "title_ar" => "النص الترحيبي", "title_en" => "Wellcome text"],
        ];
        Permission::insert($permissions);

        // attach all permissions to admin user
        $admin = admins::where("email", "admin@admin.com")->first();
        if($admin) {
            $permissions = Permission::pluck('id')->toArray();
            $admin->permissions()->sync($permissions);
        }
    }
}

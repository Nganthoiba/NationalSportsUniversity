<?php
namespace App\CustomLibrary;
use App\Models\Role;

class Menu{
    public static function getMenus(){
        $menus = config('menus');
        $accessibleMenus = [];
        foreach ($menus as &$menu) {
            $menu['link'] = $menu['route'] ? route($menu['route']) : null;
            if (!empty($menu['sub_menus'])) {
                $allowed_sub_menus = [];
                foreach ($menu['sub_menus'] as &$subMenu) {                    
                    $subMenu['link'] = $subMenu['route'] ? route($subMenu['route']) : null;
                    $allowed_sub_menus[] = $subMenu;                        
                }
                $menu['sub_menus'] = $allowed_sub_menus;
            }
            $accessibleMenus[$menu['menu_name']] = $menu;
        }
        return $accessibleMenus;
    }


    public static function getPermittedMenus($role_id){
        $role = Role::find($role_id);
        $availableMenus = self::getMenus();
        $allowedMenuNames = [];
        $permittedMenus = [];

        $currentRole = session('currentRole');
        if($currentRole){
            /*
            if($currentRole->role_name == "Super Admin"){
                $permittedMenus = array_values(array_filter($availableMenus, function($menu, $menu_name){
                    return (in_array('Super Admin', $menu['allowed_roles']??[]));
                }, ARRAY_FILTER_USE_BOTH));
            }
            
            if($currentRole->role_name == "University Admin"){                
                $permittedMenus = array_values(array_filter($availableMenus, function($menu, $menu_name){                    
                    return (in_array('University Admin', $menu['allowed_roles']??[]));
                }, ARRAY_FILTER_USE_BOTH));
            }*/
            
        }

        /*
        $menu_names = $role->allowed_menus??[];
        foreach($menu_names as $menu_name){
            if(isset($availableMenus[$menu_name])){
                $permittedMenus[] = $availableMenus[$menu_name];
            }
        }
        */

        foreach($availableMenus as $menu_name => $menu){
            if($role->hasPermission($menu['permission'])){
                $permittedMenus[] = $menu;
            }
        }

        usort($permittedMenus, function ($a, $b) {
            return ($a['displayOrder']??0) <=> ($b['displayOrder']??0);
        });
        return $permittedMenus;
    }
    
}
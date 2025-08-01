<?php
return [
    /** List of menus and the allowed roles */
    [
        'menu_label' => 'Home',
        'menu_name' => 'Home',
        'route' => ('home'),
        'allowed_roles' => [],//arbitrary, anyone is allowed
        'sub_menus' => [],
        'displayOrder' => 1,
    ],
    [
        'menu_label' => 'Students',
        'menu_name' => 'Students',
        'route' => 'displayStudents',
        'allowed_roles' => [],
        'sub_menus' => [],
        'displayOrder' => 2,
    ],
    [
        'menu_name' => 'DisplayDataChange',
        'menu_label' => 'Display Data Change',
        'route' => 'displayDataChange', 
        'allowed_roles' => [], 
        'sub_menus' => [],
        'displayOrder' => 3,
    ],    
    [
        'menu_name' => 'ShowUniversityAdmin',
        'menu_label' => 'University Admin Users',
        'route' => ('users.university-admins'),
        'allowed_roles' => ['Super Admin'],
        'sub_menus' => [],
        'displayOrder' => 5,
    ],
    [
        'menu_name' => 'ShowUsers',
        'menu_label' => 'Users',
        'route' => ('users.university-users'),
        'allowed_roles' => [],
        'sub_menus' => [],
        'displayOrder' => 6,
    ],   
    
    [
        'menu_name' => 'Role',
        'menu_label' => 'Role',
        'route' => 'roles.index',
        'allowed_roles' => ['Super Admin','University Admin'],
        'sub_menus' => [],
        'displayOrder' => 7,
    ],
    [
        'menu_name' => 'Courses',
        'menu_label' => 'Courses',
        'route' => 'courses.list',
        'allowed_roles' => [],
        'sub_menus' => [],
        'displayOrder' => 8,
    ],
    [
        'menu_name' => 'Departments',
        'menu_label' => 'Departments',
        'route' => 'departments.index',
        'allowed_roles' => [],
        'sub_menus' => [],
        'displayOrder' => 9,
    ],
    [
        'menu_name' => 'MenuAssignment',
        'menu_label' => 'Menu Assignment',
        'route' => 'menu.assignMenuRoles',
        'allowed_roles' => ['University Admin','Super Admin'],
        'sub_menus' => [],
        'displayOrder' => 10,
    ],
    [
        'menu_name' => 'Sports',
        'menu_label' => 'Sports',
        'route' => 'sports.index',
        'sub_menus' => [], 
        'displayOrder' => 11,
    ],
];
<?php
/** These are the permissions which will be assigned to users */
return [
    [
        'task_name' => 'excel_file_upload',
        'label' => 'Excel File Upload',
        'description' => 'This permission is for uploading excel file.',
        
    ], 
    [
        'task_name' => 'view_students',
        'label' => 'View Students',
        'description' => 'Permission for viewing student record.',
        
    ],
    [
        'task_name' => 'student_data_entry',
        'label' => 'Student Data Entry',
        'description' => 'Data entry permission for student',
        
    ],
    [
        'task_name' => 'student_data_edit',
        'label' => 'Student Data edit',
        'description' => 'A permission for updating student\'s information.',
        
    ],
    [
        'task_name' => 'esign_student_data_entry',
        'label' => 'Esign on Student Data Entry',
        'description' => 'A permission for esigning on the record of student ensuring the correctness of the data. This task is to be done by the user after a new student record is entered.',
        
    ],
    [
        'task_name' => 'esign_student_data_approval',
        'label' => 'Student Data Approval By Esigning',
        'description' => 'A permission for approving student\'s information, digital signature is required.',
        
    ],
    [
        'task_name' => 'student_edit_and_approve',
        'label' => 'Student Data edit & approve',
        'description' => 'A permission for updating student\'s information and thereby approving the changes made.',
        
    ],
    [
        'task_name' => 'mis_report',
        'label' => 'MIS - Report',
        'description' => 'A report on student details and data change history.',
        
    ],
    [
        'task_name' => 'print_certificate',
        'label' => 'Print Certificate',
        'description' => 'A permission for printing certificate',
    ],  
    [
        'task_name' => 'view_role',
        'label' => 'Role',
        'description' => 'An administrative permission for viewing user role',
        
    ],
    [
        'task_name' => 'add_role',
        'label' => 'Add Role',
        'description' => 'An administrative permission for creating user role',
        
    ],
    [
        'task_name' => 'edit_role',
        'label' => 'Edit Role',
        'description' => 'An administrative permission to edit user role',        
    ],
    [
        'task_name' => 'enable_or_disable_role',
        'label' => 'Enable/Disable Role',
        'description' => 'A permission for ceither to enable or disable user role',
        
    ],
    [
        'task_name' => 'view_user',
        'label' => 'View University Staff User',
        'description' => 'An administrative permission for viewing user',        
    ],  
    [
        'task_name' => 'enable_or_disable_user',
        'label' => 'Enable/Disable User',
        'description' => 'An administrative permission to either enable or disable user',        
    ],  
    [
        'task_name' => 'view_university_admin_user',
        'label' => 'View University Admin User',
        'description' => 'An administrative permission for viewing admin users',
        
    ],  
    [
        'task_name' => 'create_user',
        'label' => 'Create User',
        'description' => 'An administrative permission for creating user',
        
    ],  
    [
        'task_name' => 'assign_user_role',
        'label' => 'Change User Role',
        'description' => 'An administrative permission for assigning a role to a user or remove a role from user',
        
    ],  
    [
        'task_name' => 'view_department',
        'label' => 'Department',
        'description' => 'An administrative permission for viewing department',
        
    ],  
    [
        'task_name' => 'add_department',
        'label' => 'Add Department',
        'description' => 'An administrative permission for adding department',
        
    ],  
    [
        'task_name' => 'enable_or_disable_department',
        'label' => 'Enable/Disable Department',
        'description' => 'An administrative permission for enabling or disabling department',
        
    ],  
    [
        'task_name' => 'view_course',
        'label' => 'Course',
        'description' => 'An administrative permission for viewing course',
        
    ],  
    [
        'task_name' => 'add_course',
        'label' => 'Add Course',
        'description' => 'An administrative permission for adding course',        
    ],  
    [
        'task_name' => 'enable_or_disable_course',
        'label' => 'Add Course',
        'description' => 'An administrative permission for enabling or disabling course',        
    ],  
    [
        'task_name' => 'view_sport',
        'label' => 'Sports',
        'description' => 'An administrative permission for displaying sports menu',
        
    ],  
    [
        'task_name' => 'add_sport',
        'label' => 'Add Sports',
        'description' => 'An administrative permission for viewing sports',
        
    ],  
    [
        'task_name' => 'enable_or_disable_sport',
        'label' => 'Enable/Disable Sports',
        'description' => 'An administrative permission for enabling or disabling sports',
        
    ],  
    [
        'task_name' => 'show_home_page',
        'label' => 'Show Home Page',
        'description' => 'A permission for displaying home page menu',        
    ],  
];
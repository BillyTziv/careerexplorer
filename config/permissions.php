<?php

// config/permissions.php
return [
    /* USERS */
    ['name' => 'View All Users', 'code' => 'view-all-users', 'entity' => 'user_management' ],
    ['name' => 'View User', 'code' => 'view-user', 'entity' => 'user_management' ],
    ['name' => 'Edit User', 'code' => 'edit-user', 'entity' => 'user_management' ],
    ['name' => 'Delete User', 'code' => 'delete-user', 'entity' => 'user_management' ],

    /* ROLES */
    ['name' => 'View All Roles', 'code' => 'view-all-roles', 'entity' => 'user_management' ],
    ['name' => 'View Role', 'code' => 'view-role', 'entity' => 'user_management' ],
    ['name' => 'Edit Role', 'code' => 'edit-role', 'entity' => 'user_management' ],
    ['name' => 'Delete Role', 'code' => 'delete-role', 'entity' => 'user_management' ],

    /* PERMISSIONS */
    ['name' => 'View All Permissions', 'code' => 'view-all-permissions', 'entity' => 'user_management' ],
    ['name' => 'View Permission', 'code' => 'view-permission', 'entity' => 'user_management' ],
    ['name' => 'Edit Permission', 'code' => 'edit-permission', 'entity' => 'user_management' ],
    ['name' => 'Delete Permission', 'code' => 'delete-permission', 'entity' => 'user_management' ],

    /* STATISTICS */
    ['name' => 'View Statistics', 'code' => 'view-statistics', 'entity' => 'other' ],
    ['name' => 'View Test Templates', 'code' => 'view-all-test-templates', 'entity' => 'other' ],
    ['name' => 'View Test Submissions', 'code' => 'view-all-test-submissions', 'entity' => 'other' ],

    /* VOLUNTEERS */
    ['name' => 'View All Volunteers', 'code' => 'view-all-volunteers', 'entity' => 'volunteer' ],
    ['name' => 'Create Volunteer', 'code' => 'create-volunteer', 'entity' => 'volunteer' ],
    ['name' => 'View Volunteer', 'code' => 'view-volunteer', 'entity' => 'volunteer' ],
    ['name' => 'Edit Volunteer', 'code' => 'edit-volunteer', 'entity' => 'volunteer' ],
    ['name' => 'Update Volunteer', 'code' => 'update-volunteer', 'entity' => 'volunteer' ],
    ['name' => 'Delete Volunteer', 'code' => 'delete-volunteer', 'entity' => 'volunteer' ],
    ['name' => 'Volunteer Settings', 'code' => 'view-volunteer-settings', 'entity' => 'volunteer' ],
    ['name' => 'invite-volunteer', 'code' => 'invite-volunteer', 'entity' => 'volunteer' ],

    /* VOLUNTEER ROLES */
    ['name' => 'View All Volunteer Roles', 'code' => 'view-all-volunteer-roles', 'entity' => 'volunteer' ],
    ['name' => 'Create Volunteer Role', 'code' => 'create-volunteer-role', 'entity' => 'volunteer' ],
    ['name' => 'Edit Volunteer Role', 'code' => 'edit-volunteer-role', 'entity' => 'volunteer' ],
    ['name' => 'Delete Volunteer Role', 'code' => 'delete-volunteer-role', 'entity' => 'volunteer' ],

    /* VOLUNTEER STATUS */
    ['name' => 'View All Volunteer Statuses', 'code' => 'view-all-volunteer-statuses', 'entity' => 'volunteer' ],
    ['name' => 'Create Volunteer Status', 'code' => 'create-volunteer-status', 'entity' => 'volunteer' ],
    ['name' => 'Edit Volunteer Status', 'code' => 'edit-volunteer-status', 'entity' => 'volunteer' ],
    ['name' => 'Delete Volunteer Status', 'code' => 'delete-volunteer-status', 'entity' => 'volunteer' ],

    /* SESSION REQUESTΣ */
    ['name' => 'View All Session Requests', 'code' => 'view-all-session-requests', 'entity' => 'session' ],
    ['name' => 'View My Session Requests', 'code' => 'view-my-session-requests', 'entity' => 'session' ],
    ['name' => 'Edit Session Request', 'code' => 'edit-session-request', 'entity' => 'session' ],
    ['name' => 'Delete Session Request', 'code' => 'delete-session-request', 'entity' => 'session' ],
    
    /* APPLICATIONS */
    ['name' => 'View All Applications', 'code' => 'view-all-applications', 'entity' => 'application' ],
    ['name' => 'View Application', 'code' => 'view-application', 'entity' => 'application' ],
    ['name' => 'Edit Application', 'code' => 'edit-application', 'entity' => 'application' ],
    ['name' => 'Delete Application', 'code' => 'delete-application', 'entity' => 'application' ],
                
    /* CAREERS */
    ['name' => 'View All Careers', 'code' => 'view-all-careers', 'entity' => 'career' ],
    ['name' => 'Approve Career', 'code' => 'approve-career', 'entity' => 'career' ],
    ['name' => 'Reject Career', 'code' => 'reject-career', 'entity' => 'career' ],
    ['name' => 'Create Career', 'code' => 'create-career', 'entity' => 'career' ],
    ['name' => 'Edit Career', 'code' => 'edit-career', 'entity' => 'career' ],
    ['name' => 'Delete Career', 'code' => 'delete-career', 'entity' => 'career' ],
    ['name' => 'Career Settings', 'code' => 'view-career-settings', 'entity' => 'career' ],

    /* CAREER INTERESTS */
    ['name' => 'View All Career Interests', 'code' => 'view-all-career-interests', 'entity' => 'career' ],
    ['name' => 'Create Career Interest', 'code' => 'create-career-interest', 'entity' => 'career' ],
    ['name' => 'Edit Career Interest', 'code' => 'edit-career-interest', 'entity' => 'career' ],
    ['name' => 'Delete Career Interest', 'code' => 'delete-career-interest', 'entity' => 'career' ],

    /* CAREER SKILLS */
    ['name' => 'View All Career Skills', 'code' => 'view-all-career-skills', 'entity' => 'career' ],
    ['name' => 'Create Career Skill', 'code' => 'create-career-skill', 'entity' => 'career' ],
    ['name' => 'Edit Career Skill', 'code' => 'edit-career-skill', 'entity' => 'career' ],
    ['name' => 'Delete Career Skill', 'code' => 'delete-career-skill', 'entity' => 'career' ],

    /* CAREER VALUES */
    ['name' => 'View All Career Values', 'code' => 'view-all-career-values', 'entity' => 'career' ],
    ['name' => 'Create Career Value', 'code' => 'create-career-value', 'entity' => 'career' ],
    ['name' => 'Edit Career Value', 'code' => 'edit-career-value', 'entity' => 'career' ],
    ['name' => 'Delete Career Value', 'code' => 'delete-career-value', 'entity' => 'career' ],

    /* CAREER RIASEC CODES */
    ['name' => 'View All Career RIASEC Codes', 'code' => 'view-all-career-riasec-codes', 'entity' => 'career' ],
    ['name' => 'Create Career RIASEC Code', 'code' => 'create-career-riasec-code', 'entity' => 'career' ],
    ['name' => 'Edit Career RIASEC Code', 'code' => 'edit-career-riasec-code', 'entity' => 'career' ],
    ['name' => 'Delete Career RIASEC Code', 'code' => 'delete-career-riasec-code', 'entity' => 'career' ],

    /* UNIVERSITIES */
    ['name' => 'View All Universities', 'code' => 'view-all-universities', 'entity' => 'career' ],
    ['name' => 'Create University', 'code' => 'create-university', 'entity' => 'career' ],
    ['name' => 'Edit University', 'code' => 'edit-university', 'entity' => 'career' ],
    ['name' => 'Delete University', 'code' => 'delete-university', 'entity' => 'career' ],

    /* COURSES */
    ['name' => 'View All Courses', 'code' => 'view-all-courses', 'entity' => 'courses' ],
    ['name' => 'Create Course', 'code' => 'create-course', 'entity' => 'course' ],
    ['name' => 'Edit Course', 'code' => 'edit-course', 'entity' => 'course' ],
    ['name' => 'Delete Course', 'code' => 'delete-course', 'entity' => 'course' ],
    ['name' => 'Course Settings', 'code' => 'view-course-settings', 'entity' => 'course' ],

    /* COURSE COMPANIES */
    ['name' => 'View All Course Companies', 'code' => 'view-all-course-companies', 'entity' => 'course' ],
    ['name' => 'Create Course Company', 'code' => 'create-course-company', 'entity' => 'course' ],
    ['name' => 'Edit Course Company', 'code' => 'edit-course-company', 'entity' => 'course' ],
    ['name' => 'Delete Course Company', 'code' => 'delete-course-company', 'entity' => 'course' ],
];
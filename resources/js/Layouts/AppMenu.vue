<script setup>
    import { ref } from 'vue';

    import AppSubMenu from './AppSubMenu.vue';

    const model = ref( [
        {
            label: 'ΔΙΑΧΕΙΡΙΣΗ ΧΡΗΣΤΩΝ',
            icon: 'pi pi-fw pi-user',
            items: [
                {
                    label: 'Χρήστες',
                    icon: 'pi pi-fw pi-list',
                    permission: "view-all-users",
                    to: '/users'
                },
                {
                    label: 'Ρόλοι',
                    icon: 'pi pi-fw pi-list',
                    permission: "view-all-roles",
                    to: '/roles'
                },
                {
                    label: 'Δικαιώματα',
                    icon: 'pi pi-fw pi-list',
                    permission: "view-all-permissions",
                    to: '/permissions'
                },
            ]
        },
        {
            label: 'ΕΘΕΛΟΝΤΙΣΜΟΣ',
            items: [
                {
                    label: 'Λίστα Εθελοντών',
                    icon: 'pi pi-fw pi-users',
                    permission: "view-all-volunteers",
                    to: '/volunteers'
                },
                {
                    label: 'Διαχείριση Καταστάσεων',
                    icon: 'pi pi-fw pi-cog',
                    permission: "view-all-volunteer-statuses",
                    to: '/volunteer-statuses'
                },
                {
                    label: 'Διαχείριση Ρόλων',
                    icon: 'pi pi-fw pi-cog',
                    permission: "view-all-volunteer-roles",
                    to: '/volunteer-roles'
                }
                // {
                //     label: 'Δημιουργία Εθελοντή',
                //     icon: 'pi pi-fw pi-plus',
                //     permission: "create-volunteer",
                //     to: '/volunteers/create'
                // }
            ]
        },
        {
            label: 'TESTS',
            items: [
                {
                    label: 'Πρότυπα Tests',
                    icon: 'pi pi-fw pi-list',
                    permission: "view-all-test-templates",
                    to: '/test-templates'
                },
                {
                    label: 'Καταχωρήσεις',
                    icon: 'pi pi-fw pi-envelope',
                    permission: "view-all-test-submissions",
                    to: '/test-submissions'
                }
            ]
        },
        {
            label: 'ΠΡΟΤΥΠΑ EMAIL',
            items: [
                {
                    label: 'Λίστα Προτύπων',
                    icon: 'pi pi-fw pi-envelope',
                    permission: "view-all-email-templates",
                    to: '/email-templates'
                }
            ]
        },
        {
            label: 'ΑΚΑΔΗΜΙΑ',
            icon: 'pi pi-fw pi-list',
            items: [
                {
                    label: 'Μαθήματα',
                    icon: 'pi pi-fw pi-desktop',
                    permission: "view-all-courses",
                    to: '/courses'
                },
                {
                    label: 'Φορείς Εκπαίδευσης',
                    icon: 'pi pi-fw pi-book',
                    permission: "view-all-course-companies",
                    to: '/course-companies'
                }
            ]
        },
        
        {
            label: 'ΚΑΡΙΕΡΑ',
            items: [
                {
                    label: 'Επαγγέλματα',
                    icon: 'pi pi-fw pi-briefcase',
                    permission: "view-all-careers",
                    to: '/careers'
                },
                {
                    label: 'Κλάδοι Επαγγελμάτων',
                    icon: 'pi pi-fw pi-list',
                    permission: "view-all-job-sectors",
                    to: '/job-sectors'
                },
                {
                    label: 'Εργασιακές Αξίες',
                    icon: 'pi pi-fw pi-bolt',
                    permission: "view-all-career-values",
                    to: '/career-values'
                },
                {
                    label: 'Δεξιότητες',
                    icon: 'pi pi-fw pi-star',
                    permission: "view-all-career-skills",
                    to: '/career-skills'
                },
                {
                    label: 'Ενδιαφέροντα',
                    icon: 'pi pi-fw pi-heart',
                    permission: "view-all-career-interests",
                    to: '/career-interests'
                },
                {
                    label: 'Holland Codes',
                    icon: 'pi pi-fw pi-star',
                    permission: "view-all-career-riasec-codes",
                    to: '/career-riasec-codes'
                },
                // {
                //     label: 'Δημιουργία',
                //     icon: 'pi pi-fw pi-plus',
                //     items: [
                //         {
                //             label: 'Επάγγελμα',
                //             icon: 'pi pi-fw pi-plus',
                //             permission: "create-career",
                //             to: '/careers/create'
                //         },
                //         {
                //             label: 'Δεξιότητας',
                //             icon: 'pi pi-fw pi-list',
                //             permission: "create-career-skill",
                //             to: '/career-skills/create'
                //         },
                //         {
                //             label: 'Ενδιαφέροντος',
                //             icon: 'pi pi-fw pi-list',
                //             permission: "create-career-interest",
                //             to: '/career-interests/create'
                //         }
                //     ]
                // },
            ]
        },
        {
            label: 'ΕΡΩΤΗΜΑΤΟΛΟΓΙΑ',
            icon: 'pi pi-fw pi-check-square',
            items: [
                {
                    label: 'Πρότυπα',
                    icon: 'pi pi-fw pi-list',
                    permission: "view-all-templates",
                    to: '/tests/templates'
                },
                {
                    label: 'Απαντήσεις',
                    icon: 'pi pi-fw pi-list',
                    permission: "view-all-submissions",
                    to: '/tests/submissions'
                }
            ]
        },
        {
            label: 'ΣΥΝΕΔΡΙΕΣ',
            icon: 'pi pi-fw pi-download',
            items: [
                {
                    label: 'Αιτήσεις Συνεδριών',
                    icon: 'pi pi-fw pi-list',
                    permission: "view-all-session-requests",
                    to: '/session-requests'
                },
                {
                    label: 'Οι Συνεδρίες μου',
                    icon: 'pi pi-fw pi-calendar',
                    permission: "view-my-session-requests",
                    to: '/my-session-requests'
                },
                {
                    label: 'Δημιουργία Συνεδρίας',
                    icon: 'pi pi-fw pi-plus',
                    permission: "create-session-request",
                    to: '/session-requests/create'
                }
            ]
        },
        {
            label: 'ΑΝΑΛΥΣΗ ΟΡΓΑΝΙΣΜΟΥ',
            items: [
                {
                    label: 'Στατιστικά',
                    icon: 'pi pi-fw pi-chart-bar',
                    permission: "view-statistics",
                    to: '/statistics'
                }
            ]
        }

    ]);

    const model_old = ref([
        {
            label: 'Dashboards',
            icon: 'pi pi-home',
            items: [
                {
                    label: 'E-Commerce',
                    icon: 'pi pi-fw pi-home',
                    to: '/'
                },
                {
                    label: 'Banking',
                    icon: 'pi pi-fw pi-image',
                    to: '/dashboard-banking'
                }
            ]
        },
        {
            label: 'Apps',
            icon: 'pi pi-th-large',
            items: [
                {
                    label: 'Blog',
                    icon: 'pi pi-fw pi-comment',
                    items: [
                        {
                            label: 'List',
                            icon: 'pi pi-fw pi-image',
                            to: '/apps/blog/list'
                        },
                        {
                            label: 'Detail',
                            icon: 'pi pi-fw pi-list',
                            to: '/apps/blog/detail'
                        },
                        {
                            label: 'Edit',
                            icon: 'pi pi-fw pi-pencil',
                            to: '/apps/blog/edit'
                        }
                    ]
                },
                {
                    label: 'Calendar',
                    icon: 'pi pi-fw pi-calendar',
                    to: '/apps/calendar'
                },
                {
                    label: 'Chat',
                    icon: 'pi pi-fw pi-comments',
                    to: '/apps/chat'
                },
                {
                    label: 'Files',
                    icon: 'pi pi-fw pi-folder',
                    to: '/apps/files'
                },
                {
                    label: 'Mail',
                    icon: 'pi pi-fw pi-envelope',
                    items: [
                        {
                            label: 'Inbox',
                            icon: 'pi pi-fw pi-inbox',
                            to: '/apps/mail/inbox'
                        },
                        {
                            label: 'Compose',
                            icon: 'pi pi-fw pi-pencil',
                            to: '/apps/mail/compose'
                        },
                        {
                            label: 'Detail',
                            icon: 'pi pi-fw pi-comment',
                            to: '/apps/mail/detail/1000'
                        }
                    ]
                },
                {
                    label: 'Task List',
                    icon: 'pi pi-fw pi-check-square',
                    to: '/apps/tasklist'
                }
            ]
        },
        {
            label: 'UI Kit',
            icon: 'pi pi-fw pi-star-fill',
            items: [
                {
                    label: 'Form Layout',
                    icon: 'pi pi-fw pi-id-card',
                    to: '/uikit/formlayout'
                },
                {
                    label: 'Input',
                    icon: 'pi pi-fw pi-check-square',
                    to: '/uikit/input'
                },
                {
                    label: 'Float Label',
                    icon: 'pi pi-fw pi-bookmark',
                    to: '/uikit/floatlabel'
                },
                {
                    label: 'Invalid State',
                    icon: 'pi pi-fw pi-exclamation-circle',
                    to: '/uikit/invalidstate'
                },
                {
                    label: 'Button',
                    icon: 'pi pi-fw pi-box',
                    to: '/uikit/button'
                },
                {
                    label: 'Table',
                    icon: 'pi pi-fw pi-table',
                    to: '/uikit/table'
                },
                {
                    label: 'List',
                    icon: 'pi pi-fw pi-list',
                    to: '/uikit/list'
                },
                {
                    label: 'Tree',
                    icon: 'pi pi-fw pi-share-alt',
                    to: '/uikit/tree'
                },
                {
                    label: 'Panel',
                    icon: 'pi pi-fw pi-tablet',
                    to: '/uikit/panel'
                },
                {
                    label: 'Overlay',
                    icon: 'pi pi-fw pi-clone',
                    to: '/uikit/overlay'
                },
                {
                    label: 'Media',
                    icon: 'pi pi-fw pi-image',
                    to: '/uikit/media'
                },
                {
                    label: 'Menu',
                    icon: 'pi pi-fw pi-bars',
                    to: '/uikit/menu'
                },
                {
                    label: 'Message',
                    icon: 'pi pi-fw pi-comment',
                    to: '/uikit/message'
                },
                {
                    label: 'File',
                    icon: 'pi pi-fw pi-file',
                    to: '/uikit/file'
                },
                {
                    label: 'Chart',
                    icon: 'pi pi-fw pi-chart-bar',
                    to: '/uikit/charts'
                },
                {
                    label: 'Misc',
                    icon: 'pi pi-fw pi-circle-off',
                    to: '/uikit/misc'
                }
            ]
        },
        {
            label: 'Prime Blocks',
            icon: 'pi pi-fw pi-prime',
            items: [
                {
                    label: 'Free Blocks',
                    icon: 'pi pi-fw pi-eye',
                    to: '/blocks'
                },
                {
                    label: 'All Blocks',
                    icon: 'pi pi-fw pi-globe',
                    url: 'https://www.primefaces.org/primeblocks-vue',
                    target: '_blank'
                }
            ]
        },
        {
            label: 'Utilities',
            icon: 'pi pi-fw pi-compass',
            items: [
                {
                    label: 'PrimeIcons',
                    icon: 'pi pi-fw pi-prime',
                    to: '/utilities/icons'
                },
                {
                    label: 'Colors',
                    icon: 'pi pi-fw pi-palette',
                    to: '/utilities/colors'
                },
                {
                    label: 'PrimeFlex',
                    icon: 'pi pi-fw pi-desktop',
                    url: 'https://www.primefaces.org/primeflex/',
                    target: '_blank'
                },
                {
                    label: 'Figma',
                    icon: 'pi pi-fw pi-pencil',
                    url: 'https://www.figma.com/file/zQOW0XBXdCTqODzEOqwBtt/Preview-%7C-Apollo-2022?node-id=335%3A21768&t=69qVULK1NqM8pmnE-1',
                    target: '_blank'
                }
            ]
        },
        {
            label: 'Pages',
            icon: 'pi pi-fw pi-briefcase',
            items: [
                {
                    label: 'Landing',
                    icon: 'pi pi-fw pi-globe',
                    to: '/landing'
                },
                {
                    label: 'Auth',
                    icon: 'pi pi-fw pi-user',
                    items: [
                        {
                            label: 'Login',
                            icon: 'pi pi-fw pi-sign-in',
                            to: '/auth/login'
                        },
                        {
                            label: 'Error',
                            icon: 'pi pi-fw pi-times-circle',
                            to: '/auth/error'
                        },
                        {
                            label: 'Access Denied',
                            icon: 'pi pi-fw pi-lock',
                            to: '/auth/access'
                        },
                        {
                            label: 'Register',
                            icon: 'pi pi-fw pi-user-plus',
                            to: '/auth/register'
                        },
                        {
                            label: 'Forgot Password',
                            icon: 'pi pi-fw pi-question',
                            to: '/auth/forgotpassword'
                        },
                        {
                            label: 'New Password',
                            icon: 'pi pi-fw pi-cog',
                            to: '/auth/newpassword'
                        },
                        {
                            label: 'Verification',
                            icon: 'pi pi-fw pi-envelope',
                            to: '/auth/verification'
                        },
                        {
                            label: 'Lock Screen',
                            icon: 'pi pi-fw pi-eye-slash',
                            to: '/auth/lockscreen'
                        }
                    ]
                },
                {
                    label: 'Crud',
                    icon: 'pi pi-fw pi-pencil',
                    to: '/pages/crud'
                },
                {
                    label: 'Timeline',
                    icon: 'pi pi-fw pi-calendar',
                    to: '/pages/timeline'
                },
                {
                    label: 'Invoice',
                    icon: 'pi pi-fw pi-dollar',
                    to: '/pages/invoice'
                },
                {
                    label: 'About Us',
                    icon: 'pi pi-fw pi-user',
                    to: '/pages/aboutus'
                },
                {
                    label: 'Help',
                    icon: 'pi pi-fw pi-question-circle',
                    to: '/pages/help'
                },
                {
                    label: 'Not Found',
                    icon: 'pi pi-fw pi-exclamation-circle',
                    to: '/pages/notfound'
                },
                {
                    label: 'Empty',
                    icon: 'pi pi-fw pi-circle-off',
                    to: '/pages/empty'
                },
                {
                    label: 'FAQ',
                    icon: 'pi pi-fw pi-question',
                    to: '/pages/faq'
                },
                {
                    label: 'Contact Us',
                    icon: 'pi pi-fw pi-phone',
                    to: '/pages/contact'
                }
            ]
        },
        {
            label: 'E-Commerce',
            icon: 'pi pi-fw pi-wallet',
            items: [
                {
                    label: 'Product Overview',
                    icon: 'pi pi-fw pi-image',
                    to: '/ecommerce/product-overview'
                },
                {
                    label: 'Product List',
                    icon: 'pi pi-fw pi-list',
                    to: '/ecommerce/product-list'
                },
                {
                    label: 'New Product',
                    icon: 'pi pi-fw pi-plus',
                    to: '/ecommerce/new-product'
                },
                {
                    label: 'Shopping Cart',
                    icon: 'pi pi-fw pi-shopping-cart',
                    to: '/ecommerce/shopping-cart'
                },
                {
                    label: 'Checkout Form',
                    icon: 'pi pi-fw pi-check-square',
                    to: '/ecommerce/checkout-form'
                },
                {
                    label: 'Order History',
                    icon: 'pi pi-fw pi-history',
                    to: '/ecommerce/order-history'
                },
                {
                    label: 'Order Summary',
                    icon: 'pi pi-fw pi-file',
                    to: '/ecommerce/order-summary'
                }
            ]
        },
        {
            label: 'User Management',
            icon: 'pi pi-fw pi-user',
            items: [
                {
                    label: 'List',
                    icon: 'pi pi-fw pi-list',
                    to: '/profile/list'
                },
                {
                    label: 'Create',
                    icon: 'pi pi-fw pi-plus',
                    to: '/profile/create'
                }
            ]
        },
        {
            label: 'Hierarchy',
            icon: 'pi pi-fw pi-align-left',
            items: [
                {
                    label: 'Submenu 1',
                    icon: 'pi pi-fw pi-align-left',
                    items: [
                        {
                            label: 'Submenu 1.1',
                            icon: 'pi pi-fw pi-align-left',
                            items: [
                                {
                                    label: 'Submenu 1.1.1',
                                    icon: 'pi pi-fw pi-align-left'
                                },
                                {
                                    label: 'Submenu 1.1.2',
                                    icon: 'pi pi-fw pi-align-left'
                                },
                                {
                                    label: 'Submenu 1.1.3',
                                    icon: 'pi pi-fw pi-align-left'
                                }
                            ]
                        },
                        {
                            label: 'Submenu 1.2',
                            icon: 'pi pi-fw pi-align-left',
                            items: [
                                {
                                    label: 'Submenu 1.2.1',
                                    icon: 'pi pi-fw pi-align-left'
                                }
                            ]
                        }
                    ]
                },
                {
                    label: 'Submenu 2',
                    icon: 'pi pi-fw pi-align-left',
                    items: [
                        {
                            label: 'Submenu 2.1',
                            icon: 'pi pi-fw pi-align-left',
                            items: [
                                {
                                    label: 'Submenu 2.1.1',
                                    icon: 'pi pi-fw pi-align-left'
                                },
                                {
                                    label: 'Submenu 2.1.2',
                                    icon: 'pi pi-fw pi-align-left'
                                }
                            ]
                        },
                        {
                            label: 'Submenu 2.2',
                            icon: 'pi pi-fw pi-align-left',
                            items: [
                                {
                                    label: 'Submenu 2.2.1',
                                    icon: 'pi pi-fw pi-align-left'
                                }
                            ]
                        }
                    ]
                }
            ]
        },
        {
            label: 'Start',
            icon: 'pi pi-fw pi-download',
            items: [
                {
                    label: 'Buy Now',
                    icon: 'pi pi-fw pi-shopping-cart',
                    url: 'https://www.primefaces.org/store'
                },
                {
                    label: 'Documentation',
                    icon: 'pi pi-fw pi-info-circle',
                    to: '/documentation'
                }
            ]
        }
    ]);
</script>

<template>
    <AppSubMenu :model="model" />
</template>

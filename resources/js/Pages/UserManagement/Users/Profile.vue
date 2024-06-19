<template>
    <Head title="Dashboard" />
  
    <BreezeAuthenticatedLayout>
        <template #page-header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                View User
            </h2>
        </template>
  
        <div class="py-12" v-if="user">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">  
                      <h2 style="font-size: 26px; font-weight: bold;">{{ user.firstname }} {{ user.lastname }} </h2>
                      <ul>
                        <li>Username: {{ user.username }}</li>
                        <li>Phone: {{ user.phone }}</li>
                        <li>Email: {{ user.email }}</li>
                        <li>Role:
                          <span v-for="role in user.roles" class="bg-lime-100 text-lime-800 text-md font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-lime-200 dark:text-lime-300">
                            {{ role.name }}
                          </span>
                        </li>
                      </ul>
                      
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
  </template>
  
  <script>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import { Inertia } from '@inertiajs/inertia';
    import 'boxicons';
    import moment from 'moment';
    import Notification from '../../Common/Notification.vue';
  
    export default {
        setup () {
            function viewUser( id ) {
                Inertia.get('/users/' + id)
            }
  
            const destroy = ( id ) => {
                Inertia.delete('users/' + id, {
                    onBefore: () => confirm('Are you sure you want to delete this user?'),
                } )
            }
  
            function formatDate( date ) {
                return moment(date).format('DD/mm/YYYY HH:mm');
            }
  
            return { viewUser, destroy, formatDate }
        },
  
        components: {
            Head,
            Link,
            Notification,
            PreviousPageButton,
            BreezeAuthenticatedLayout,
        },
        props:  {
            user: Object
        }
    }
  </script>
  
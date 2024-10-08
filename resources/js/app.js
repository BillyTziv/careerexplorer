import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createPinia } from 'pinia';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

/* Import PrimeVue and Theme */
import PrimeVuePlugin from './plugins/primevue';
import BlockViewer from '@/Components/BlockViewer.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const pinia = createPinia();

createInertiaApp({
    title: (title) => `${appName}`, //${title} - 
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({
            render: () => h(App, props)
        });
        
        app.use(pinia)
        app.use(plugin);
        app.use(ZiggyVue);
        app.use(PrimeVuePlugin);

        app.component('BlockViewer', BlockViewer);

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

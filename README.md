# Installation Procedure

1. Setup package.json with all the required depedencies.
2.

Include the theme style by adding the following line in the app.de.php file.

```
<link id="theme-link" href="/layout/styles/theme/theme-light/indigo/theme.css" rel="stylesheet"/>
```

Add primevue style in the app.css file. Add those two lines only. Without anything else.

```
@import 'primeflex/primeflex.scss';
@import 'primeicons/primeicons.css';
```

Create a plugin to install the theme and import it in app.js main file.

import PrimeVuePlugin from './plugins/primevue';
import BlockViewer from '@/Components/BlockViewer.vue';

```

Change the setup to something like this
```

setup({ el, App, props, plugin }) {
const app = createApp({ render: () => h(App, props) });

        app.use(plugin);
        app.use(ZiggyVue);
        app.use(PrimeVuePlugin);

        app.component('BlockViewer', BlockViewer);

        app.mount(el);
    },
    ```

Then add the BlockViewer.vue file into the Components folder.

```
resources/js/Components/BlockViewer.vue
```

Next, install all the required packages.
``
npm install

```

# Setup Database
Next, configure the .env file with the appropriate database credetials.

```

run php artisan migrate

```

```

Create a folder with the layour in the public directory.

window._ = require('lodash');

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
// import Layout from './Components/Layouts/Layout';

InertiaProgress.init({
    delay: 200,
    color: '#AD9C68',
    showSpinner: true
});

createInertiaApp({
    resolve: name => require(`./Pages/${name}`), // single file
    // resolve: async name => {
    //     const page = (await import(`./Pages/${name}`)).default;
    //     page.layout = page.layout || Layout;

    //     return page;
    // }, // split code: use extra request
    setup({ el, app, props, plugin }) {
        createApp({ render: () => h(app, props) })
            .use(plugin)
            // .mixin({ methods: { route: window.route } }) // enable route() on template
            .mount(el);
    },
});
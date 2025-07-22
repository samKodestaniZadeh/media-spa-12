import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { createHead } from '@vueuse/head';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    // setup({ el, App, props, plugin }) {
    //     const head = createHead();
    //     createApp({ render: () => h(App, props) })

    //         .use(plugin)
    //         .use(head)
    //         .use(ZiggyVue)
    //         .mount(el);
    // },
    // progress: {
    //     color: '#4B5563',
    // },
    setup({ el, App, props, plugin }) {
        const head = createHead();
        const app = createApp({ render: () => h(App, props) });

        app
            .use(plugin)
            .use(head)
            .use(ZiggyVue)
            .mount(el);

        // حذف لودر اولیه
        const loader = document.getElementById('initial-loader');
        if (loader) loader.remove();

        // حذف opacity-0 برای نمایش نرم
        document.body.classList.remove('opacity-0');
    },
});

// This will set light / dark mode on page load...
initializeTheme();

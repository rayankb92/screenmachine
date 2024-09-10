import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';
import { PageProps as AppPageProps } from './';

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    interface Screenshot {
        id: number;
        file_path: string;
        name: string;
        url: string;
        created_at: string;
        updated_at: string;
    }

    interface NewScan {
        temporaryUrl: string;
        screenshot: Screenshot;
    }

    interface ScanningSite {
        url: string;
        name: string;
    }


    var route: typeof ziggyRoute;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

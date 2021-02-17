import Vue from 'vue';

import * as Sentry from '@sentry/vue';
import {Integrations} from '@sentry/tracing';

window.sentry = {
    client: Sentry,
    report: function(e) {
        if (window.laravel && window.laravel.sentry) {
            if (typeof e === 'string') {
                sentry.client.captureMessage(e);
            } else {
                sentry.client.captureException(e);
            }
        }
    },
};

if (window.laravel && window.laravel.sentry) {
    const options = {
        Vue: Vue,
        dsn: laravel.sentry.dsn,
        release: laravel.sentry.release,
        environment: laravel.env,
        debug: laravel.debug,
        logErrors: true,
        integrations: [],
    };
    if (laravel.sentry.traces_sample_rate) {
        options.integrations.push(new Integrations.BrowserTracing());
        options.tracesSampleRate = laravel.sentry.traces_sample_rate;
        options.tracingOptions = {
            trackComponents: true,
        };
    }
    sentry.client.init(options);

    sentry.client.setTags(laravel.sentry.tags);
    sentry.client.setUser(laravel.sentry.user);
}

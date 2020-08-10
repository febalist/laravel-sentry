import Vue from 'vue';

import * as Sentry from '@sentry/browser';
import * as Integrations from '@sentry/integrations';
import {Integrations as ApmIntegrations} from '@sentry/apm';

window.sentry = {
  client: Sentry,
  report: function(error) {
    if (app.sentry) {
      if (typeof error === 'string') {
        sentry.client.captureMessage(error);
      } else {
        sentry.client.captureException(error);
      }
    }
  },
};

if (app.sentry) {
  const options = {
    dsn: app.sentry.dsn,
    release: app.sentry.release,
    environment: app.env,
    debug: app.debug,
    integrations: [
      new Integrations.Vue({
        Vue,
        attachProps: true,
        tracing: !!app.sentry.traces_sample_rate,
        tracingOptions: {
          trackComponents: true,
        },
      }),
    ],
    beforeSend (event, hint) {
      if (hint.originalException) {
        console.error(hint.originalException);
      }

      return event;
    },
  };
  if (app.sentry.traces_sample_rate) {
    options.tracesSampleRate = app.sentry.traces_sample_rate;
    options.integrations.push(new ApmIntegrations.Tracing());
  }
  sentry.client.init(options);

  sentry.client.setTags(app.sentry.tags);
  sentry.client.setUser(app.sentry.user);
}

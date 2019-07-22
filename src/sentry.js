import Vue from 'vue'

import * as Sentry from '@sentry/browser'
import * as Integrations from '@sentry/integrations'

window.sentry = {
  client: Sentry,
}

if (app.sentry) {
  sentry.client.init({
    dsn: app.sentry.dsn,
    release: app.sentry.release,
    environment: app.env,
    debug: app.debug,
    integrations: [
      new Integrations.Vue({Vue, attachProps: true}),
    ],
  })

  sentry.client.setTags(app.sentry.tags)
  sentry.client.setUser(app.sentry.user)
}

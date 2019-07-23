import Vue from 'vue'

import * as Sentry from '@sentry/browser'
import * as Integrations from '@sentry/integrations'

window.sentry = {
  client: Sentry,
  report: function(error) {
    if (app.sentry) {
      if (typeof error === 'string') {
        sentry.client.captureMessage(error)
      } else {
        sentry.client.captureException(error)
      }
    }
  },
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
    beforeSend (event, hint) {
      if (hint.originalException) {
        console.error(hint.originalException)
      }

      return event
    },
  })

  sentry.client.setTags(app.sentry.tags)
  sentry.client.setUser(app.sentry.user)
}

<?php

$release = trim(@file_get_contents(base_path('VERSION')))
    ?: preg_replace('/^v/', '', trim(@exec('git --git-dir '.base_path('.git').' describe --tags')));
if ($release) {
    if ($project = env('SENTRY_PROJECT')) {
        $release = "$project@$release";
    }
} else {
    $release = trim(@exec('git --git-dir '.base_path('.git').' log --pretty="%H" -n1 HEAD')) ?: null;
}

return [
    'dsn' => env('SENTRY_DSN'),
    'release' => $release,
    'send_default_pii' => true,
    'traces_sample_rate' => (float) env('SENTRY_TRACES_SAMPLE_RATE', 0),
];

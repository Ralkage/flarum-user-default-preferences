<?php

namespace PT\Preferences;

use Illuminate\Contracts\Events\Dispatcher;

return [
    function (Dispatcher $events) {
        $events->subscribe(Listeners\BeforeUserWillBeSaved::class);
    },
];

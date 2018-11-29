<?php

namespace App\Http\Controllers;


use BotMan\BotMan\BotMan;
use BotTemplateFramework\TemplateEngine;
use Illuminate\Http\Request;

class WebhookController {

    public static $scenario;

    public function listen(Request $request) {
        /** @var BotMan $botman */
        $botman = resolve('botman');
        /** @var TemplateEngine $engine */
        $engine = resolve('engine');
        if ($request->getMethod() == 'POST') {
            $engine->listen();
        }
        $botman->listen();
    }

    public static function getScenario() {
        return self::$scenario ?? self::$scenario = file_get_contents(app_path('scenario.json'));
    }

}
<?php

namespace App\CustomMailer;

use Illuminate\Mail\MailManager;
use App\CustomMailer\CustomTransport;

/**
 * Custom email manager
 * 2022-07-08   Charles T2. Initial creation.
 */
class CustomMailManager extends MailManager
{
    protected function createCustomTransport()
    {
        $config = $this->app['config']->get('services.custom_mail', []);

        return new CustomTransport(
            $config['key'], $config['url']
        );
    }
}
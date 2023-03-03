<?php

namespace Biblys\Service;

class Log
{
    /**
     * @deprecated Legacy Log service is deprecated. Use LoggerService->log("errors", …) instead.
     */
    public static function error(string $level, string $message, array $context = []): void
    {
        $logger = new LoggerService();
        $logger->log('errors', $level, $message, $context);
    }

    /**
     * @deprecated Legacy Log service is deprecated. Use LoggerService->log("sql", …) instead.
     */
    public static function sql(string $level, string $message, array $context = []): void
    {
        $logger = new LoggerService();
        $logger->log('sql', $level, $message, $context);
    }

    /**
     * @deprecated Legacy Log service is deprecated. Use LoggerService->log("mails", …) instead.
     */
    public static function mail(string $level, string $message, array $context = []): void
    {
        $logger = new LoggerService();
        $logger->log('mails', $level, $message, $context);
    }

    /**
     * @deprecated Legacy Log service is deprecated. Use LoggerService->log("security", …) instead.
     */
    public static function security(string $level, string $message, array $context = []): void
    {
        $logger = new LoggerService();
        $logger->log('security', $level, $message, $context);
    }

    /**
     * @deprecated Legacy Log service is deprecated. Use LoggerService->log("paypal", …) instead.
     */
    public static function paypal(string $level, string $message, array $context = []): void
    {
        $logger = new LoggerService();
        $logger->log('paypal', $level, $message, $context);
    }

    /**
     * @deprecated Legacy Log service is deprecated. Use LoggerService->log("payplug", …) instead.
     */
    public static function payplug(string $level, string $message, array $context = []): void
    {
        $logger = new LoggerService();
        $logger->log('payplug', $level, $message, $context);
    }

    /**
     * @deprecated Legacy Log service is deprecated. Use LoggerService->log("stripe", …) instead.
     */
    public static function stripe(string $level, string $message, array $context = []): void
    {
        $logger = new LoggerService();
        $logger->log('stripe', $level, $message, $context);
    }
}

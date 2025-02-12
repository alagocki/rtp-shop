<?php declare(strict_types=1);

namespace Rtp\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class StockUpdateTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'swag.stock.update';
    }

    public static function getDefaultInterval(): int
    {
        return 300; // 5 minutes
    }
}

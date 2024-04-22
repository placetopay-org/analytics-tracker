<?php

namespace Placetopay\AnalyticsTracker\Enums;

enum TrackerLabelsEnum: string
{
    case REQUESTED_3DS = 'Process requested 3DS';
    case TRANSACTION_MADE = 'Process transaction made';
}

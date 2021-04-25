<?php

namespace App\Constant;

final class PRPlace
{
    public const DESCRIBE = 'describe';
    public const CODING = 'coding';
    public const REVIEW = 'review';
    public const TEST = 'test';
    public const MERGED = 'merged';
    public const CLOSED = 'closed';

    public const ALL = [
        self::DESCRIBE,
        self::CODING,
        self::REVIEW,
        self::TEST,
        self::MERGED,
        self::CLOSED
    ];
}

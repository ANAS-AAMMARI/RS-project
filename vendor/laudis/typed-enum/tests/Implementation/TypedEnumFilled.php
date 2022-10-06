<?php

declare(strict_types=1);

/*
 * This file is part of the Laudis TypedEnum library
 *
 * (c) Laudis <https://laudis.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Laudis\TypedEnum\Tests\Implementation;

use Laudis\TypedEnum\TypedEnum;

/**
 * @method static TypedEnumFilled TEST()
 * @method static TypedEnumFilled TEST_NO_SAME_VALUE()
 * @method static TypedEnumFilled TEST_NUMERIC()
 * @method static TypedEnumFilled TEST_SAME_ONE()
 * @method static TypedEnumFilled TEST_SAME_TWO()
 *
 * @extends TypedEnum<string|int>
 */
final class TypedEnumFilled extends TypedEnum
{
    private const TEST = 'test';
    private const TEST_NO_SAME_VALUE = 'abc';
    private const TEST_NUMERIC = 1;
    private const TEST_SAME_ONE = 'same';
    private const TEST_SAME_TWO = 'same';
}

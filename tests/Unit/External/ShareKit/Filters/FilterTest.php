<?php

declare(strict_types=1);

namespace Tests\Unit\External\ShareKit\Filters;

use App\External\ShareKit\Filters\Filter;
use Tests\Helpers\Instance;
use Tests\TestCase;

class FilterTest extends TestCase
{
    /** @test */
    public function if_no_value_is_provided_it_will_consider_the_operator_the_value(): void
    {
        $filter = new Filter('::STRING::', '::STRING_VALUE::');

        $this->assertEquals(
            '::STRING_VALUE::',
            Instance::getProperty($filter, 'value')
        );
    }

    /** @test */
    public function if_no_value_is_provided_it_will_consider_the_operator_will_be_set_to_equal(): void
    {
        $filter = new Filter('::STRING::', '::STRING_VALUE::');

        $this->assertEquals(
            'EQ',
            Instance::getProperty($filter, 'operator')
        );
    }

    /** @test */
    public function get_url_key_will_prefix_the_key_with_filter(): void
    {
        $filter = new Filter('::STRING::', '::STRING_VALUE::');

        $this->assertStringStartsWith(
            'filter',
            $filter->getUrlKey()
        );
    }

    /** @test */
    public function get_url_key_will_wrap_the_key(): void
    {
        $filter = new Filter('::STRING::', 'EQ', '::STRING_VALUE::');

        $this->assertStringContainsString(
            '::STRING::',
            $filter->getUrlKey()
        );
    }

    /** @test */
    public function get_url_key_will_suffix_the_key_with_the_wrapped_operator(): void
    {
        $filter = new Filter('::STRING::', 'EQ', '::STRING_VALUE::');

        $this->assertStringEndsWith(
            '[EQ]',
            $filter->getUrlKey()
        );
    }

    /** @test */
    public function get_url_value_will_return_the_value(): void
    {
        $filter = new Filter('::STRING::', 'EQ', '::STRING_VALUE::');

        $this->assertStringEndsWith(
            '::STRING_VALUE::',
            $filter->getUrlValue()
        );
    }
}

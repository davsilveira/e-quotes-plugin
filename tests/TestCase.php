<?php

declare(strict_types=1);

namespace Emplement\eQuotes\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;

class TestCase extends \PHPUnit\Framework\TestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		Functions\when('wp_remote_get')->justReturn(null);

		//Returns an arbitrary url
		Functions\expect('plugins_url')
			->zeroOrMoreTimes()
			->with('', TUT_ABSPATH)
			->andReturn('https://foo.test/the-users-table/wp-content/plugins/the-users-table');

		Functions\expect('plugin_dir_path')
			->zeroOrMoreTimes()
			->with(__DIR__)
			->andReturn(TUT_ABSPATH);

		Monkey\setUp();
	}

	protected function tearDown(): void
	{
		Monkey\tearDown();
		parent::tearDown();
	}
}

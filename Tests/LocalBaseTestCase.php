<?php
declare(strict_types=1);
namespace Supseven\Cleverreach\Tests;

use PHPUnit\Framework\TestCase;
use Supseven\Cleverreach\Service\ConfigurationService;

/**
 * @author Georg Großberger <g.grossberger@supseven.at>
 */
class LocalBaseTestCase extends TestCase
{
    protected function getConfiguration(): ConfigurationService
    {
        $config = $this->createMock(ConfigurationService::class);
        $config->expects(self::any())->method('getRestUrl')->willReturn('https://api.cleverreach.com');
        $config->expects(self::any())->method('getClientId')->willReturn('123');
        $config->expects(self::any())->method('getLoginName')->willReturn('abc');
        $config->expects(self::any())->method('getPassword')->willReturn('def');
        $config->expects(self::any())->method('getGroupId')->willReturn(123);

        return $config;
    }
}

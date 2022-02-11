<?php
declare(strict_types=1);
namespace Supseven\Cleverreach\Tests\Cleverreach;

use Supseven\Cleverreach\CleverReach\Api;
use Supseven\Cleverreach\Domain\Model\Receiver;
use Supseven\Cleverreach\Tests\LocalBaseTestCase;
use Supseven\Cleverreach\Tools\Rest;

/**
 * @author Georg Großberger <g.grossberger@supseven.at>
 */
class ApiTest extends LocalBaseTestCase
{
    public function testConnect(): void
    {
        $params = [
            'client_id' => 123,
            'login'     => 'abc',
            'password'  => 'def',
        ];

        $token = 'abcdef';

        $rest = $this->createMock(Rest::class);
        $rest->expects(self::once())->method('init')->with(self::equalTo('https://api.cleverreach.com'));
        $rest->expects(self::once())->method('post')->with(self::equalTo('/login'), self::equalTo($params))->willReturn($token);
        $rest->expects(self::once())->method('setAuthMode')->with(self::equalTo('bearer'), self::equalTo($token));

        $subject = new Api($this->getConfiguration(), $rest);
        $subject->connect();

        // Calling again must not do anything
        $subject->connect();
    }

    /**
     * @dataProvider receiversProvider
     * @param $receivers
     * @param $groupId
     */
    public function testAddReReceiversToGroup($receivers, $groupId): void
    {
        $groupId ??= 123;

        $expectedList = [];

        if ($receivers instanceof Receiver) {
            $expectedList[] = $receivers->toArray();
        }

        if (is_array($receivers)) {
            $expectedList = array_map(static fn (Receiver $r): array => $r->toArray(), $receivers);
        }

        if (is_string($receivers)) {
            $expectedList[] = (new Receiver($receivers))->toArray();
        }

        $result = new \stdClass();
        $result->status = 'insert success';

        $rest = $this->createMock(Rest::class);
        $rest->expects(self::once())->method('post')->with(
            self::equalTo('/groups.json/' . $groupId . '/receivers/insert'),
            self::equalTo($expectedList)
        )->willReturn($result);

        $subject = new Api($this->getConfiguration(), $rest);
        $subject->connected = true;

        self::assertTrue($subject->addReceiversToGroup($receivers, $groupId));
    }

    public function receiversProvider(): \Generator
    {
        $receiver1 = new Receiver('example@domain.com');

        yield 'One receiver object, no group ID' => [$receiver1, null];
        yield 'One receiver object, with group ID' => [$receiver1, 789];

        $receiver2 = new Receiver('another-example@domain.com');

        yield 'Two receiver objects, no group ID' => [[$receiver1, $receiver2], null];
        yield 'One receiver string, with group ID' => ['someone@domain.com', 789];
    }

    public function testRemoveReceiversFromGroup(): void
    {
        $rest = $this->createMock(Rest::class);
        $rest->expects(self::once())->method('delete')->with('/groups.json/123/receivers/456');

        $subject = new Api($this->getConfiguration(), $rest);
        $subject->connected = true;

        $subject->removeReceiversFromGroup('456');
    }
}

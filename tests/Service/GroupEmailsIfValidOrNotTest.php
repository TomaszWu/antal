<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\GroupEmailsIfValidOrNot;
use App\Service\Validators\IsEmailValidValidator;

class GroupEmailsIfValidOrNotTest extends TestCase
{
    /**
     * @param $expected
     * @param $given
     * @dataProvider dataProvider
     */
    public function testIfGivenEmailsWillBeGroupedCorrectly($expected, $given)
    {
        $isEmailValidValidator = new IsEmailValidValidator();

        $groupEmailsIfValidOrNot = new GroupEmailsIfValidOrNot($isEmailValidValidator);

        $this->assertTrue(($expected == $groupEmailsIfValidOrNot->group($given)), true);
    }

    public function dataProvider()
    {
        return [
            [
                [
                    'validEmails' => [
                        'ivoaparecido@yahoo.com.br',

                    ],
                    'invalidEmails' => [
                        'test.@test.com',
                        'test@.test.com',
                        'randomtext',
                    ]
                ],
                [
                    'test.@test.com',
                    'test@.test.com',
                    'randomtext',
                    'ivoaparecido@yahoo.com.br',
                ],
            ]
        ];
    }
}
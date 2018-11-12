<?php

namespace App\Service;

use App\Service\Validators\IsEmailValidValidator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GroupEmailsIfValidOrNot
{
    /**
     * @var IsEmailValidValidator $validator
     */
    private $validator;

    /**
     * GroupEmailsIfValidOrNot constructor.
     * @param IsEmailValidValidator $validator
     */
    public function __construct(IsEmailValidValidator $validator)
    {
        $this->validator = $validator;
    }

    public function group(array $emails): array
    {
        $validEmails = [];
        $invalidEmails = [];

        foreach ($emails as $email) {
            if (!$this->validator->validate(trim($email))) {
                $invalidEmails[] = $email;
                continue;
            }
            $validEmails[] = $email;
        }

        return [
            'validEmails' => $validEmails,
            'invalidEmails' => $invalidEmails
        ];
    }
}

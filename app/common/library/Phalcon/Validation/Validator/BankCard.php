<?php

/*
  +------------------------------------------------------------------------+
  | Phalcon Framework                                                      |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2016 Phalcon Team (https://www.phalconphp.com)      |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file LICENSE.txt.                             |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Grigory Parshikov <root@parshikov.github.io>                  |
  +------------------------------------------------------------------------+
*/

namespace Phalcon\Validation\Validator;

use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\Exception as ValidationException;
use Wdxr\Models\Services\BankInfo;

/**
 * Phalcon\Mvc\Model\Validator\CardNumber
 *
 * Checks if a credit card number using Luhn algorithm
 *
 * <code>
 * use Phalcon\Validation\Validator\CardNumber as CreditCardValidator;
 *
 * $validator->add('creditcard', new CreditCardValidator([
 *     'message' => 'The credit card number is not valid',
 * ]));
 * </code>
 */
class BankCard extends Validator
{
    /**
     * {@inheritdoc}
     *
     * @param Validation $validation
     * @param string $attribute
     *
     * @return bool
     * @throws Exception
     */
    public function validate(Validation $validation, $attribute)
    {
        $value = preg_replace('/[^\d]/', '', $validation->getValue($attribute));
        $message = ($this->hasOption('message')) ? $this->getOption('message') : '不合法的银行卡号';

        if(BankInfo::info($value) === false) {
            $validation->appendMessage(new Message($message, $attribute, 'BankCard'));
            return false;
        }

        return true;
    }
}

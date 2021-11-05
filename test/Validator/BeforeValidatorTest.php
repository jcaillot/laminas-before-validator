<?php

declare(strict_types=1);

namespace ChamanTest\Validator;

use PHPUnit\Framework\TestCase;

use Chaman\Validator\BeforeValidator;

// php vendor/bin/phpunit test/Validator/BeforeValidatorTest.php
class BeforeValidatorTest extends TestCase
{


    public function dataProviderInvalidUsingStrict()
    {
        return [
            ['2012/08/01', ['date2' => '2012/08/01']],
            ['2012/08/19', ['date2' => '2012/08/03']],
            ['2021/09/12', ['date2' => '2019/08/23']],
            ['2021/10/12', ['date2' => '2021/10/12']],
        ];
    }

    /**
     * @dataProvider dataProviderInvalidUsingStrict
     * @param string $date
     * @param array $context
     */
    public function testInvalidUsingStrictOption(string $date, array $context)
    {
        $options = [
            'second_date_field' => 'date2',
            'date_format' => 'Y/m/d',
            'strict' => true,
        ];
        $validator = new BeforeValidator($options);
        $isValid = $validator->isvalid($date, $context);
        $this->assertFalse($isValid);
    }


    public function testValidUsingStrictOption()
    {
        $options = [
            'second_date_field' => 'date2',
            'date_format' => 'Y/m/d',
            'strict' => false,
        ];
        $validator = new BeforeValidator($options);
        $value = '2012/08/01';
        $context = ['date2' => '2012/08/01'];

        $isValid = $validator->isvalid($value, $context);
        $this->assertTrue($isValid);
    }

    public function testInvalidDate()
    {
        $options = [
            'second_date_field' => 'date2',
            'date_format' => 'Y-m-d',
            'messages' => [
                BeforeValidator::NOT_BEFORE => "'%value%' should be before the second date.",
                BeforeValidator::NOT_A_VALID_DATE => "'%value%' is not a valid date.",
            ],
        ];
        $validator = new BeforeValidator($options);
        $value = '2021/10/10';
        $context = ['date2' => '2021-10-11'];

        $isValid = $validator->isvalid($value, $context);

        $this->assertFalse($isValid);
        $messages = $validator->getMessages();
        $this->assertNotEmpty($messages);
        $this->assertStringContainsString('is not a valid date', $messages[BeforeValidator::NOT_A_VALID_DATE]);
    }

    public function testValidDate()
    {
        $options = [
            'second_date_field' => 'date2',
            'date_format' => 'Y-m-d',
            'messages' => [
                BeforeValidator::NOT_BEFORE => "'%value%' should be before the second date.",
                BeforeValidator::NOT_A_VALID_DATE => "'%value%' is not a valid date.",
            ],
        ];
        $validator = new BeforeValidator($options);
        $value = '2021-10-10';
        $context = ['date2' => '2021-10-11'];

        $isValid = $validator->isvalid($value, $context);

        $this->assertTrue($isValid);

        $value = '2021-10-12';
        $context = ['date2' => '2021-10-11'];

        $isValid = $validator->isvalid($value, $context);

        $this->assertFalse($isValid);

        $messages = $validator->getMessages();
        $this->assertNotEmpty($messages);
        $this->assertStringContainsString('should be before the second date', $messages[BeforeValidator::NOT_BEFORE]);


        $options = [
            'second_date_field' => 'date2',
            'date_format' => 'Y-m-d H:i:s',
        ];

        $validator = new BeforeValidator($options);
        $value = '2021-10-10 09:00:10';
        $context = ['date2' => '2021-10-10 09:00:12'];

        $isValid = $validator->isvalid($value, $context);
        $this->assertTrue($isValid);


        $validator = new BeforeValidator($options);
        $value = '2021-10-10 09:00:15';
        $context = ['date2' => '2021-10-10 09:00:10'];

        $isValid = $validator->isvalid($value, $context);
        $this->assertFalse($isValid);
    }
}

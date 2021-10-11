# jcaillot/laminas-before-validator

### Date Before validator for the Laminas framework

> Laminas\Validator\GreaterThan supports only the validation of numbers.
> This custom Validator, let you compare two date strings.

## Installation

`composer require jcaillot/laminas-before-validator`

## Usage

Add Validator as usual in your fieldset:

```php 

   public function getInputFilterSpecification(): array
    {
        return [
            // date1
            'date1' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'NotEmpty', 
                        'break_chain_on_failure' => true
                    ],
                    [
                        'name' => BeforeValidator::class,
                        'options' => [
                            'second_date_field' => 'date2',
                            'date_format' => 'Y-m-d', 
                            'messages' => [
                                BeforeValidator::NOT_BEFORE => 'This date must antecede',
                                BeforeValidator::NOT_A_VALID_DATE => 'The date is not valid',
                            ],
                        ]
                    ]
                ],
            ],
            // date2
            'date2' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'NotEmpty', 
                        'break_chain_on_failure' => true
                    ],
                ],
            ],

        ];

    }

```

## License

[MIT](https://choosealicense.com/licenses/mit/)

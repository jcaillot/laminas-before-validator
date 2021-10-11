# jcaillot/laminas-before-validator

### Date Before validator for the Laminas framework

> Laminas\Validator\GreaterThan supports only the validation of numbers.
> This simple custom Validator, will let you compare two date strings.

## Installation

`composer require jcaillot/laminas-before-validator`

## Usage

The validator needs to know the name of the other date field for comparison. It needs to know the input date format.
Here are the three required options:

+ *second_date_field* (default to endDate): The name the second date field.
+ *date_format* (default to Y-m-d H:i:s): the string format for the two dates.
+ *strict* (default to false): will allow two identical dates to validate.

Add Validator as usual in your fieldset:

```php 

    use Laminas\Form\Fieldset;
    use Laminas\InputFilter\InputFilterProviderInterface;
    use Chaman\Validator\BeforeValidator;

    class DemoFieldset extends Fieldset implements InputFilterProviderInterface
    {
    
       ...
       
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
                                'date_format'       => 'Y-m-d', 
                                'strict'            => true,
                                'messages' => [
                                    BeforeValidator::NOT_BEFORE => '"%value%" should be before the second date.',
                                    BeforeValidator::NOT_A_VALID_DATE => '"%value%" is not a valid date.',
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
        
        ...

```

## License

[MIT](https://choosealicense.com/licenses/mit/)

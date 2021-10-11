<?php

namespace Chaman\Validator;

use DateTime;
use Laminas\Validator\AbstractValidator;

class BeforeValidator extends AbstractValidator
{
    public const NOT_BEFORE = 'not-before';
    public const NOT_A_VALID_DATE = 'not-a-date';

    protected array $messageTemplates = [
        self::NOT_BEFORE => "'%value%' should be before the second date.",
        self::NOT_A_VALID_DATE => "'%value%' is not a valid date.",
    ];
    protected string $dateTimeFormat = 'Y-m-d H:i:s';
    protected string $secondDateFieldName = 'endDate';
    protected bool $strictlyBefore = false;

    /**
     * @param array|null $options
     */
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            if (array_key_exists('second_date_field', $options)) {
                $this->secondDateFieldName = $options['second_date_field'];
            }
            if (array_key_exists('date_format', $options)) {
                $this->dateTimeFormat = $options['date_format'];
            }
            if (array_key_exists('strict', $options)) {
                $this->strictlyBefore = $options['strict'];
            }
            if (array_key_exists('messages', $options)) {
                $this->messageTemplates = array_merge($this->messageTemplates, $options['messages']);
            }
        }

        parent::__construct($options);
    }


    /**
     * @param mixed $value
     * @param null $context
     *
     * @return bool
     */
    public function isValid($value, $context = null): bool
    {
        $secondDateFieldValue = $context[$this->secondDateFieldName];

        $date1 = DateTime::createFromFormat($this->dateTimeFormat, $value);
        $date2 = DateTime::createFromFormat($this->dateTimeFormat, $secondDateFieldValue);

        $isValid = true;

        if (!$date1 || !$date2) {
            $this->error(self::NOT_A_VALID_DATE, $value);
            $isValid = false;
        }

        if ($this->strictlyBefore) {
            if ($date1 >= $date2) {
                $this->error(self::NOT_BEFORE, $value);
                $isValid = false;
            }
        } else {
            if ($date1 > $date2) {
                $this->error(self::NOT_BEFORE, $value);
                $isValid = false;
            }
        }

        return $isValid;
    }
}

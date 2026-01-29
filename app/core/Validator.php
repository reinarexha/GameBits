<?php

declare(strict_types=1);


class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    /**
     * Validate data against rules.
     * $rules format: [ 'field_name' => 'required|email|min_length:8' ]
     *
     * @param array<string, mixed> $data
     * @param array<string, string> $rules
     * @return array<string, string> list of field => error message
     */
    public function validate(array $data, array $rules): array
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? '';
            $value = is_string($value) ? trim($value) : $value;

            foreach (explode('|', $ruleString) as $rule) {
                $rule = trim($rule);
                if ($rule === '') {
                    continue;
                }

                if (strpos($rule, ':') !== false) {
                    [$ruleName, $param] = explode(':', $rule, 2);
                    $param = trim($param);
                } else {
                    $ruleName = $rule;
                    $param = null;
                }

                if ($ruleName === 'required' && !$this->required($value)) {
                    $this->errors[$field] = $this->errors[$field] ?? ucfirst($field) . ' is required.';
                    break;
                }
                if ($ruleName === 'email' && $value !== '' && !$this->email($value)) {
                    $this->errors[$field] = $this->errors[$field] ?? 'Please enter a valid email.';
                    break;
                }
                if ($ruleName === 'min_length' && $param !== null && !$this->minLength($value, (int) $param)) {
                    $this->errors[$field] = $this->errors[$field] ?? ucfirst($field) . ' must be at least ' . $param . ' characters.';
                    break;
                }
            }
        }

        return $this->errors;
    }

    public function required(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }
        return is_string($value) ? trim($value) !== '' : true;
    }

    public function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function minLength(mixed $value, int $min): bool
    {
        if ($value === null || $value === '') {
            return true; // use required for empty
        }
        return strlen((string) $value) >= $min;
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }

    public function fails(): bool
    {
        return count($this->errors) > 0;
    }
}

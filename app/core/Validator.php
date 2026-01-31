<?php


class Validator
{
    /**
     * Validates data against the given rules.
     *
     * Rules format: each key is a field name, each value is a string of rules.
     * Example: ['email' => 'required|email', 'password' => 'required|min_length:8']
     *
     * Supported rules:
     * - required       : field must not be empty
     * - email          : field must be a valid email address
     * - min_length:N   : field must have at least N characters (e.g. min_length:8)
     *
     * @param array $data  The data to validate (e.g. $_POST)
     * @param array $rules The rules for each field
     * @return array Associative array of field name => error message. Empty if no errors.
     */
    public function validate(array $data, array $rules): array
    {
        $errors = [];

        
        foreach ($rules as $field => $ruleString) {
            
            $value = isset($data[$field]) ? $data[$field] : '';
           
            if (is_string($value)) {
                $value = trim($value);
            }

            
            $ruleList = explode('|', $ruleString);

            foreach ($ruleList as $rule) {
                $rule = trim($rule);
                if ($rule === '') {
                    continue;
                }

                
                $param = null;
                if (strpos($rule, ':') !== false) {
                    $parts = explode(':', $rule, 2);
                    $rule = trim($parts[0]);
                    $param = isset($parts[1]) ? trim($parts[1]) : null;
                }

                // --- required ---
                if ($rule === 'required') {
                    if ($value === null || $value === '') {
                        $errors[$field] = ucfirst($field) . ' is required.';
                        break; 
                    }
                }

                // --- email ---
                if ($rule === 'email') {
                    if ($value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
                        $errors[$field] = 'Please enter a valid email.';
                        break;
                    }
                }

                // --- min_length:N ---
                if ($rule === 'min_length' && $param !== null) {
                    $min = (int) $param;
                    if ($value !== '' && $value !== null) {
                        if (strlen((string) $value) < $min) {
                            $errors[$field] = ucfirst($field) . ' must be at least ' . $min . ' characters.';
                            break;
                        }
                    }
                }
            }
        }

        return $errors;
    }
}

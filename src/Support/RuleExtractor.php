<?php

namespace Proghasan\BladeFormValidator\Support;

class RuleExtractor
{
    /**
     * Extracts validation rules from a Blade view by parsing input, textarea, and select elements.
     *
     * It looks for elements with a `name` attribute and a custom `validated` attribute,
     * then returns an associative array where keys are normalized input names (dot notation)
     * and values are the corresponding validation rules.
     *
     * @param string $viewPath The path to the Blade view file to extract rules from.
     * @return array<string, string> An associative array of field names and validation rules.
     */
    public static function extract(string $viewPath): array
    {
        // Render the Blade view as HTML string
        $html = view($viewPath)->render();

        // Find all input, textarea, and select elements in the HTML
        preg_match_all('/<(input|textarea|select)[^>]*>/i', $html, $elements);

        $rules = [];

        // Loop through each matched element
        foreach ($elements[0] as $element) {
            // Extract the value of the "name" attribute, if present
            preg_match('/name="([^"]+)"/', $element, $name);

            // Extract the value of the custom "validated" attribute, if present
            preg_match('/validated="([^"]+)"/', $element, $validated);

            // If both name and validated attributes exist, process the rule
            if (!empty($name) && !empty($validated)) {
                // Normalize the input name from PHP array syntax to dot notation
                $fieldName = self::normalizeName($name[1]);

                // Assign the validation rules for the normalized field name
                $rules[$fieldName] = $validated[1];
            }
        }

        return $rules;
    }

    /**
     * Converts PHP array-style field names to dot notation.
     *
     * For example:
     *  - "items[0]" becomes "items.0"
     *  - "items[0][name]" becomes "items.0.name"
     *
     * This format is compatible with Laravel validation keys.
     *
     * @param string $name The raw field name possibly containing array brackets.
     * @return string The normalized field name in dot notation.
     */
    public static function normalizeName(string $name): string
    {
        // Replace all occurrences of [key] with .key
        return preg_replace_callback('/\[(.*?)\]/', fn($m) => '.' . $m[1], $name);
    }
}

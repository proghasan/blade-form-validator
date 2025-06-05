<?php

namespace Proghasan\BladeFormValidator\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Proghasan\BladeFormValidator\Support\RuleExtractor;

class BladeFormValidateRequest extends FormRequest
{
    /**
     * The Blade view path used to extract validation rules.
     *
     * This can be set via route defaults, config, or inferred from the route name.
     *
     * @var string|null
     */
    protected ?string $formView = null;

    /**
     * Prepare the data for validation.
     *
     * Determines which Blade view to use for extracting validation rules.
     * Priority:
     *  1. Route default parameter 'formView'
     *  2. Default view inferred from the current route name
     *
     * Called automatically before validation rules are resolved.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->formView = $this->route()->defaults['formView'] ?? $this->getDefaultView();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Uses the RuleExtractor class to parse the specified Blade view
     * and extract validation rules from elements with the `validated` attribute.
     *
     * @return array<string, string> Validation rules keyed by field name.
     */
    public function rules(): array
    {
        if (!$this->formView) {
            return [];
        }

        return RuleExtractor::extract($this->formView);
    }

    /**
     * Get the default Blade view path based on the current route name.
     *
     * Converts dot notation route name to a slash-based view path.
     * For example, route name 'user.register' becomes 'user/register'.
     *
     * @return string The inferred default Blade view path.
     */
    protected function getDefaultView(): string
    {
        return str_replace('.', '/', $this->route()->getName());
    }
}

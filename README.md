# BladeFormValidator

A Laravel package for form validation using validation rules defined directly inside Blade form inputs via a `validated` attribute.  
This package extracts validation rules from Blade views automatically, simplifying controller validation logic.

---

## Features

- Define validation rules inside your Blade form inputs using `validated` attributes.
- Use a custom FormRequest class that automatically extracts rules from your Blade form.
- Supports nested input names (`name="user[email]"`) and converts them to dot notation (`user.email`).
- Configurable default form view or automatic form view detection from route names.
- Easy to extend by subclassing the base `BladeFormValidateRequest`.

---

## Installation

Require the package via Composer:

```bash
composer require proghasan/blade-form-validator
```

## Usage

### 1. Defining Validation Rules in Blade Views
Add `validated` attributes to your form inputs:

```php
<form method="POST" action="{{ route('register.form') }}">
    @csrf

    <input 
        type="text"
        name="name"
        validated="required|string|max:255" 
    />

    <input
        type="email"
        name="email"
        validated="required|email|unique:users,email"
    />

    <input 
        type="password" 
        name="password" 
        validated="required|string|min:8|confirmed" 
        placeholder="Enter Password"
    />

    <input 
        type="password" 
        name="password_confirmation" 
        validated="required|string|min:8" 
        placeholder="Confirm Password"
    />
    <button type="submit">Register</button>
</form>
```

### 2. Using the built-in FormRequest
Use the provided FormRequest class in your controller methods to validate input automatically.

```php

use Proghasan\BladeFormValidator\Requests\BladeFormValidateRequest;

class RegisterController extends Controller
{
    public function store(BladeFormValidateRequest $request)
    {
        // Validation is automatically performed based on your form's Blade view rules

        $validated = $request->validated();

        // Proceed with validated data...
    }
}
```

### 3. Route Default `formView` Syntax

If you need to explicitly specify a form view for a route, use **route defaults**:

```php
Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.form')
    ->defaults('formView', 'forms.register');
```

### 4. Customizing the Form View
By default, the package tries to find the Blade view from the current route name, converting dots to slashes.
If your route or view folder naming differs, set the `$formView` property in a subclassed FormRequest:

```php
use Proghasan\BladeFormValidator\Requests\BladeFormValidateRequest;

class RegisterFormRequest extends BladeFormValidateRequest
{
    // Specify the form view explicitly
    protected string $formView = 'forms.register';
}
```

Then use your custom request in the controller:

```php
public function store(RegisterFormRequest $request)
{
    $validated = $request->validated();
    // ...
}
```

## How It Works

- The package renders the specified Blade form view.
- It parses all `<input>`, `<textarea>`, and `<select>` elements to extract `name` and `validated` attributes.
- Converts array-style input names like `user[email]` to dot notation (`user.email`).
- Uses Laravel’s validation engine with the extracted rules.

### License

This package is open-sourced software licensed under the MIT license.
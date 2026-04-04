# Form Helper Tester Module

Comprehensive unit tests for all `form_helper` functions in the Trongate framework.

## Usage

1. Navigate to `/form_helper_tester` in your browser.
2. Results are grouped by helper in official docs order, showing test description, expected, actual, and PASS/FAIL/SKIP per assertion.

## Tests Performed

### form_button()
- Generates `<button>` element
- Label text present in output
- Extra attributes applied
- No value: still generates button

### form_checkbox()
- Has `type="checkbox"`, correct name/value
- Checked state applied
- Unchecked: no `checked` attribute
- Default value is `"1"`
- Truthy string `"1"` treated as checked

### form_close()
- Contains closing `</form>` tag

### form_date()
- Has `type="date"`, correct name/value
- Extra attributes applied
- Null value: no `value` attribute

### form_datetime_local()
- Has `type="datetime-local"`, correct name/value

### form_dropdown()
- Contains `<select>`, correct name/id
- All option values and labels present
- `selected` attribute applied to chosen option
- No selection: no `selected` attribute

### form_email()
- Has `type="email"`, correct name/value
- Null value: no `value` attribute

### form_file_select()
- Has `type="file"`, extra attributes applied
- Accept attribute rendered

### form_hidden()
- Has `type="hidden"`, correct name/value, extra attributes
- Integer value rendered as string
- Null value: element still generated

### form_input()
- Correct name, value, and extra attributes
- Null value: no `value` attribute
- Empty string: `value=""` present
- XSS in value: raw `<` not present in attribute

### form_label()
- Contains `<label>`, `for` attribute, class, text, `</label>`
- No attributes: text still rendered

### form_month()
- Has `type="month"`, correct name/value

### form_number()
- Has `type="number"`, correct name/value, `min`/`max` attributes
- Float value rendered correctly
- Null value: no `value` attribute

### form_open()
- Contains `<form>`, `action=`, location URL, `method="post"`, extra attributes

### form_open_upload()
- Contains `<form>`, `method="post"`, `enctype="multipart/form-data"`, location, extra attributes

### form_password()
- Has `type="password"`, correct name/value
- Null value: no `value` attribute

### form_radio()
- Has `type="radio"`, correct name/value
- Checked/unchecked state

### form_search()
- Has `type="search"`, correct name/value
- Null value: no `value` attribute

### form_submit()
- Has `type="submit"`, correct name/value, extra attributes
- Null value: element still generated

### form_textarea()
- Contains `<textarea>`, correct name, id, rows, cols
- Value placed between opening and closing tags
- Null value: textarea still generated

### form_time()
- Has `type="time"`, correct name/value

### form_week()
- Has `type="week"`, correct name/value

### post() *(partially testable)*
- No POST data: returns empty array or empty string
- Missing key returns empty string
- **SKIPPED**: POST field retrieval (requires form submission context)
- **SKIPPED**: `clean_up=true`, `cast_numeric=true` modes

### validation_errors() *(partially testable)*
- No validation errors: returns `null`
- **SKIPPED**: errors with actual failed validation (requires `validation_run()` context)
- **SKIPPED**: custom HTML wrapping test

## Notes

- SKIPPED tests are not counted as failures — they are environment-dependent and cannot be exercised in a GET page context.
- `form_file_select()` passes `$name` as first arg but the engine service may not include it in the `name` attribute — verify in output if needed.

## Dependencies

- Requires the `form` module and `validation` module to be available.
- Assumes `form_helper.php` is loaded (auto-loaded in Trongate).

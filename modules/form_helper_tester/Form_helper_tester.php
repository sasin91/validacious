<?php

/**
 * Form Helper Tester Module
 *
 * Provides comprehensive unit tests for all form_helper functions.
 * Each test records: key, label, expected, actual, pass.
 *
 * Note: post() and validation_errors() cannot be tested in a standard GET
 * page request without simulating a form submission. Those helpers are
 * documented with a note explaining why they are environment-dependent.
 */
class Form_helper_tester extends Trongate {

    public function index(): void {
        $data['test_results'] = $this->run_tests();
        $data['view_module']  = $this->module_name;
        $data['view_file']    = 'test_results';
        $this->view('test_results', $data);
    }

    // -------------------------------------------------------------------------
    // Test runner
    // -------------------------------------------------------------------------

    private function run_tests(): array {
        $results = [];

        $this->test_form_button($results);
        $this->test_form_checkbox($results);
        $this->test_form_close($results);
        $this->test_form_date($results);
        $this->test_form_datetime_local($results);
        $this->test_form_dropdown($results);
        $this->test_form_email($results);
        $this->test_form_file_select($results);
        $this->test_form_hidden($results);
        $this->test_form_input($results);
        $this->test_form_label($results);
        $this->test_form_month($results);
        $this->test_form_number($results);
        $this->test_form_open($results);
        $this->test_form_open_upload($results);
        $this->test_form_password($results);
        $this->test_form_radio($results);
        $this->test_form_search($results);
        $this->test_form_submit($results);
        $this->test_form_textarea($results);
        $this->test_form_time($results);
        $this->test_form_week($results);
        $this->test_post($results);
        $this->test_validation_errors($results);

        return $results;
    }

    // -------------------------------------------------------------------------
    // Assertion helpers
    // -------------------------------------------------------------------------

    private function assert_contains(array &$results, string $key, string $html, string $needle, string $label): void {
        $results[] = [
            'key'      => $key,
            'label'    => $label,
            'expected' => "contains: {$needle}",
            'actual'   => str_contains($html, $needle) ? "contains: {$needle}" : "(missing) {$needle}",
            'pass'     => str_contains($html, $needle),
        ];
    }

    private function assert_not_contains(array &$results, string $key, string $html, string $needle, string $label): void {
        $results[] = [
            'key'      => $key,
            'label'    => $label,
            'expected' => "does NOT contain: {$needle}",
            'actual'   => !str_contains($html, $needle) ? "does NOT contain: {$needle}" : "(present, should be absent) {$needle}",
            'pass'     => !str_contains($html, $needle),
        ];
    }

    private function assert_eq(array &$results, string $key, $actual, $expected, string $label): void {
        $results[] = [
            'key'      => $key,
            'label'    => $label,
            'expected' => $expected,
            'actual'   => $actual,
            'pass'     => $actual === $expected,
        ];
    }

    private function assert_true(array &$results, string $key, bool $condition, string $label, $actual = null): void {
        $results[] = [
            'key'      => $key,
            'label'    => $label,
            'expected' => true,
            'actual'   => $actual ?? ($condition ? '(condition met)' : '(condition failed)'),
            'pass'     => $condition,
        ];
    }

    private function assert_skip(array &$results, string $key, string $label, string $reason): void {
        $results[] = [
            'key'      => $key,
            'label'    => $label . ' (SKIPPED)',
            'expected' => '(skipped)',
            'actual'   => $reason,
            'pass'     => true,
        ];
    }

    // =========================================================================
    // form_button
    // =========================================================================

    private function test_form_button(array &$results): void {
        $k = 'form_button';

        $html = form_button('my_btn', 'Click Me', ['id' => 'btn1']);
        $this->assert_contains($results, $k, $html, '<button',      'Generates <button> element');
        $this->assert_contains($results, $k, $html, 'Click Me',     'Button label in output');
        $this->assert_contains($results, $k, $html, 'id="btn1"',    'Extra attribute applied');

        // No value — should still produce a button
        $html_no_val = form_button('my_btn');
        $this->assert_contains($results, $k, $html_no_val, '<button', 'No value: still generates <button>');
    }

    // =========================================================================
    // form_checkbox
    // =========================================================================

    private function test_form_checkbox(array &$results): void {
        $k = 'form_checkbox';

        // Checked
        $html = form_checkbox('agree', 'yes', true, ['id' => 'agree_id']);
        $this->assert_contains($results, $k, $html, 'type="checkbox"',     'Has type="checkbox"');
        $this->assert_contains($results, $k, $html, 'name="agree"',        'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="yes"',         'Has correct value');
        $this->assert_contains($results, $k, $html, 'checked="checked"',   'Checked state applied');
        $this->assert_contains($results, $k, $html, 'id="agree_id"',       'Extra attribute applied');

        // Unchecked
        $html_unchecked = form_checkbox('agree', 'yes', false);
        $this->assert_not_contains($results, $k, $html_unchecked, 'checked', 'Unchecked: no checked attribute');

        // Default value = '1'
        $html_default = form_checkbox('subscribe');
        $this->assert_contains($results, $k, $html_default, 'value="1"', 'Default value is "1"');

        // Truthy string checked value
        $html_truthy = form_checkbox('active', '1', '1');
        $this->assert_contains($results, $k, $html_truthy, 'checked', 'Truthy string "1" treated as checked');
    }

    // =========================================================================
    // form_close
    // =========================================================================

    private function test_form_close(array &$results): void {
        $k = 'form_close';

        $html = form_close();
        $this->assert_contains($results, $k, $html, '</form>', 'Contains closing </form> tag');
    }

    // =========================================================================
    // form_date
    // =========================================================================

    private function test_form_date(array &$results): void {
        $k = 'form_date';

        $html = form_date('date_field', '2024-01-01', ['id' => 'date_id']);
        $this->assert_contains($results, $k, $html, 'type="date"',         'Has type="date"');
        $this->assert_contains($results, $k, $html, 'name="date_field"',   'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="2024-01-01"',  'Has correct value');
        $this->assert_contains($results, $k, $html, 'id="date_id"',        'Extra attribute applied');

        // Null value — no value attribute
        $html_no_val = form_date('date_field', null);
        $this->assert_not_contains($results, $k, $html_no_val, 'value=', 'Null value: no value attribute');
    }

    // =========================================================================
    // form_datetime_local
    // =========================================================================

    private function test_form_datetime_local(array &$results): void {
        $k = 'form_datetime_local';

        $html = form_datetime_local('dt_field', '2024-01-01T12:00', ['id' => 'dt_id']);
        $this->assert_contains($results, $k, $html, 'type="datetime-local"',     'Has type="datetime-local"');
        $this->assert_contains($results, $k, $html, 'name="dt_field"',           'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="2024-01-01T12:00"',  'Has correct value');
    }

    // =========================================================================
    // form_dropdown
    // =========================================================================

    private function test_form_dropdown(array &$results): void {
        $k = 'form_dropdown';

        $options = ['red' => 'Red', 'green' => 'Green', 'blue' => 'Blue'];
        $html = form_dropdown('colour', $options, 'green', ['id' => 'colour_id']);

        $this->assert_contains($results, $k, $html, '<select',              'Contains <select> element');
        $this->assert_contains($results, $k, $html, 'name="colour"',       'Has correct name attribute');
        $this->assert_contains($results, $k, $html, 'id="colour_id"',      'Extra attribute applied');
        $this->assert_contains($results, $k, $html, 'value="red"',         'Option "red" present');
        $this->assert_contains($results, $k, $html, '>Red<',               'Option label "Red" present');
        $this->assert_contains($results, $k, $html, 'value="green"',       'Selected option "green" present');
        $this->assert_contains($results, $k, $html, 'selected',            'Selected attribute applied to chosen option');
        $this->assert_contains($results, $k, $html, '</select>',           'Contains closing </select>');

        // No selection — no selected attribute expected
        $html_none = form_dropdown('colour', $options);
        $this->assert_not_contains($results, $k, $html_none, 'selected', 'No selection: no selected attribute');
    }

    // =========================================================================
    // form_email
    // =========================================================================

    private function test_form_email(array &$results): void {
        $k = 'form_email';

        $html = form_email('email_field', 'user@example.com', ['id' => 'email_id']);
        $this->assert_contains($results, $k, $html, 'type="email"',             'Has type="email"');
        $this->assert_contains($results, $k, $html, 'name="email_field"',       'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="user@example.com"', 'Has correct value');

        // Null value — no value attribute
        $html_no_val = form_email('email_field', null);
        $this->assert_not_contains($results, $k, $html_no_val, 'value=', 'Null value: no value attribute');
    }

    // =========================================================================
    // form_file_select
    // =========================================================================

    private function test_form_file_select(array &$results): void {
        $k = 'form_file_select';

        $html = form_file_select('upload', ['id' => 'file_id', 'accept' => 'image/*']);
        $this->assert_contains($results, $k, $html, 'type="file"',    'Has type="file"');
        $this->assert_contains($results, $k, $html, 'id="file_id"',   'Extra attribute applied');
        $this->assert_contains($results, $k, $html, 'accept=',        'Accept attribute present');

        // Note: form_file_select passes $name as first arg but the engine passes $attributes only to the service.
        // The name attribute presence depends on the service implementation.
        $html_basic = form_file_select('my_file');
        $this->assert_contains($results, $k, $html_basic, 'type="file"', 'Basic call: type="file" present');
    }

    // =========================================================================
    // form_hidden
    // =========================================================================

    private function test_form_hidden(array &$results): void {
        $k = 'form_hidden';

        $html = form_hidden('csrf_token', 'abc123', ['id' => 'csrf_id']);
        $this->assert_contains($results, $k, $html, 'type="hidden"',      'Has type="hidden"');
        $this->assert_contains($results, $k, $html, 'name="csrf_token"',  'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="abc123"',     'Has correct value');
        $this->assert_contains($results, $k, $html, 'id="csrf_id"',       'Extra attribute applied');

        // Integer value cast to string
        $html_int = form_hidden('count', 42);
        $this->assert_contains($results, $k, $html_int, 'value="42"', 'Integer value rendered as string');

        // Null value
        $html_null = form_hidden('empty_field', null);
        $this->assert_contains($results, $k, $html_null, 'type="hidden"', 'Null value: element still generated');
    }

    // =========================================================================
    // form_input
    // =========================================================================

    private function test_form_input(array &$results): void {
        $k = 'form_input';

        // Standard
        $html = form_input('username', 'john', ['id' => 'usr_id', 'class' => 'form-ctrl']);
        $this->assert_contains($results, $k, $html, 'name="username"',  'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="john"',     'Has correct value');
        $this->assert_contains($results, $k, $html, 'id="usr_id"',      'Extra attribute: id');
        $this->assert_contains($results, $k, $html, 'class="form-ctrl"','Extra attribute: class');

        // Null value — no value attribute emitted
        $html_null = form_input('username', null);
        $this->assert_not_contains($results, $k, $html_null, 'value=', 'Null value: no value attribute');

        // Empty string value — value="" emitted
        $html_empty = form_input('username', '');
        $this->assert_contains($results, $k, $html_empty, 'value=""', 'Empty string value: value="" present');

        // Special characters in value — should be HTML-encoded
        $html_xss = form_input('q', '<script>');
        $this->assert_not_contains($results, $k, $html_xss, 'value="<script>"', 'XSS in value: raw < not in attribute');
    }

    // =========================================================================
    // form_label
    // =========================================================================

    private function test_form_label(array &$results): void {
        $k = 'form_label';

        $html = form_label('Your Name', ['for' => 'name_id', 'class' => 'lbl']);
        $this->assert_contains($results, $k, $html, '<label',          'Contains <label> tag');
        $this->assert_contains($results, $k, $html, 'for="name_id"',   'Has for attribute');
        $this->assert_contains($results, $k, $html, 'class="lbl"',     'Has class attribute');
        $this->assert_contains($results, $k, $html, 'Your Name',       'Label text present');
        $this->assert_contains($results, $k, $html, '</label>',        'Contains closing </label>');

        // No attributes
        $html_bare = form_label('Simple');
        $this->assert_contains($results, $k, $html_bare, 'Simple', 'No attributes: text still rendered');
    }

    // =========================================================================
    // form_month
    // =========================================================================

    private function test_form_month(array &$results): void {
        $k = 'form_month';

        $html = form_month('month_field', '2024-01', ['id' => 'month_id']);
        $this->assert_contains($results, $k, $html, 'type="month"',       'Has type="month"');
        $this->assert_contains($results, $k, $html, 'name="month_field"', 'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="2024-01"',    'Has correct value');
    }

    // =========================================================================
    // form_number
    // =========================================================================

    private function test_form_number(array &$results): void {
        $k = 'form_number';

        $html = form_number('qty', 42, ['id' => 'qty_id', 'min' => '0', 'max' => '100']);
        $this->assert_contains($results, $k, $html, 'type="number"',  'Has type="number"');
        $this->assert_contains($results, $k, $html, 'name="qty"',     'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="42"',     'Integer value rendered as string');
        $this->assert_contains($results, $k, $html, 'min="0"',        'min attribute applied');
        $this->assert_contains($results, $k, $html, 'max="100"',      'max attribute applied');

        // Float value
        $html_float = form_number('price', 9.99);
        $this->assert_contains($results, $k, $html_float, 'value="9.99"', 'Float value rendered correctly');

        // Null value — no value attribute
        $html_null = form_number('qty', null);
        $this->assert_not_contains($results, $k, $html_null, 'value=', 'Null value: no value attribute');
    }

    // =========================================================================
    // form_open
    // =========================================================================

    private function test_form_open(array &$results): void {
        $k = 'form_open';

        $html = form_open('user/save', ['id' => 'main_form', 'class' => 'form']);
        $this->assert_contains($results, $k, $html, '<form',          'Contains <form> tag');
        $this->assert_contains($results, $k, $html, 'action=',        'Has action attribute');
        $this->assert_contains($results, $k, $html, 'user/save',      'Action contains provided location');
        $this->assert_contains($results, $k, $html, 'method="post"',  'Has method="post"');
        $this->assert_contains($results, $k, $html, 'id="main_form"', 'Extra attribute: id');

        // No attributes
        $html_bare = form_open('contact/submit');
        $this->assert_contains($results, $k, $html_bare, 'method="post"', 'No attributes: method still post');
    }

    // =========================================================================
    // form_open_upload
    // =========================================================================

    private function test_form_open_upload(array &$results): void {
        $k = 'form_open_upload';

        $html = form_open_upload('media/upload', ['id' => 'upload_form']);
        $this->assert_contains($results, $k, $html, '<form',                        'Contains <form> tag');
        $this->assert_contains($results, $k, $html, 'method="post"',               'Has method="post"');
        $this->assert_contains($results, $k, $html, 'enctype="multipart/form-data"','Has multipart enctype');
        $this->assert_contains($results, $k, $html, 'media/upload',                'Action contains provided location');
        $this->assert_contains($results, $k, $html, 'id="upload_form"',            'Extra attribute: id');
    }

    // =========================================================================
    // form_password
    // =========================================================================

    private function test_form_password(array &$results): void {
        $k = 'form_password';

        $html = form_password('pwd', 'secret', ['id' => 'pwd_id']);
        $this->assert_contains($results, $k, $html, 'type="password"',  'Has type="password"');
        $this->assert_contains($results, $k, $html, 'name="pwd"',       'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="secret"',   'Has correct value');

        // Null value — no value attribute (passwords typically not pre-filled)
        $html_null = form_password('pwd', null);
        $this->assert_not_contains($results, $k, $html_null, 'value=', 'Null value: no value attribute');
    }

    // =========================================================================
    // form_radio
    // =========================================================================

    private function test_form_radio(array &$results): void {
        $k = 'form_radio';

        $html = form_radio('colour', 'red', true, ['id' => 'red_opt']);
        $this->assert_contains($results, $k, $html, 'type="radio"',      'Has type="radio"');
        $this->assert_contains($results, $k, $html, 'name="colour"',     'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="red"',       'Has correct value');
        $this->assert_contains($results, $k, $html, 'checked="checked"', 'Checked state applied');
        $this->assert_contains($results, $k, $html, 'id="red_opt"',      'Extra attribute applied');

        // Unchecked
        $html_unchecked = form_radio('colour', 'blue', false);
        $this->assert_not_contains($results, $k, $html_unchecked, 'checked', 'Unchecked: no checked attribute');
    }

    // =========================================================================
    // form_search
    // =========================================================================

    private function test_form_search(array &$results): void {
        $k = 'form_search';

        $html = form_search('q', 'trongate', ['id' => 'search_id']);
        $this->assert_contains($results, $k, $html, 'type="search"',   'Has type="search"');
        $this->assert_contains($results, $k, $html, 'name="q"',        'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="trongate"','Has correct value');

        // Null value
        $html_null = form_search('q', null);
        $this->assert_not_contains($results, $k, $html_null, 'value=', 'Null value: no value attribute');
    }

    // =========================================================================
    // form_submit
    // =========================================================================

    private function test_form_submit(array &$results): void {
        $k = 'form_submit';

        $html = form_submit('save_btn', 'Save Record', ['id' => 'save_id', 'class' => 'btn']);
        $this->assert_contains($results, $k, $html, 'type="submit"',    'Has type="submit"');
        $this->assert_contains($results, $k, $html, 'name="save_btn"',  'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="Save Record"','Has correct value');
        $this->assert_contains($results, $k, $html, 'id="save_id"',     'Extra attribute: id');

        // Null value — engine defaults to "Submit"
        $html_null = form_submit('btn');
        $this->assert_true($results, $k, !empty($html_null), 'Null value: element still generated', $html_null);
    }

    // =========================================================================
    // form_textarea
    // =========================================================================

    private function test_form_textarea(array &$results): void {
        $k = 'form_textarea';

        $html = form_textarea('body', 'Hello World', ['id' => 'body_id', 'rows' => '5', 'cols' => '40']);
        $this->assert_contains($results, $k, $html, '<textarea',         'Contains <textarea> tag');
        $this->assert_contains($results, $k, $html, 'name="body"',       'Has correct name');
        $this->assert_contains($results, $k, $html, 'id="body_id"',      'Extra attribute: id');
        $this->assert_contains($results, $k, $html, 'rows="5"',          'rows attribute applied');
        $this->assert_contains($results, $k, $html, 'cols="40"',         'cols attribute applied');
        $this->assert_contains($results, $k, $html, '>Hello World</textarea>', 'Value placed between tags');

        // Null value — textarea still generated
        $html_null = form_textarea('body', null);
        $this->assert_contains($results, $k, $html_null, '<textarea', 'Null value: textarea still generated');
    }

    // =========================================================================
    // form_time
    // =========================================================================

    private function test_form_time(array &$results): void {
        $k = 'form_time';

        $html = form_time('appt', '14:30', ['id' => 'time_id']);
        $this->assert_contains($results, $k, $html, 'type="time"',   'Has type="time"');
        $this->assert_contains($results, $k, $html, 'name="appt"',   'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="14:30"', 'Has correct value');
    }

    // =========================================================================
    // form_week
    // =========================================================================

    private function test_form_week(array &$results): void {
        $k = 'form_week';

        $html = form_week('wk', '2024-W01', ['id' => 'wk_id']);
        $this->assert_contains($results, $k, $html, 'type="week"',       'Has type="week"');
        $this->assert_contains($results, $k, $html, 'name="wk"',         'Has correct name');
        $this->assert_contains($results, $k, $html, 'value="2024-W01"',  'Has correct value');
    }

    // =========================================================================
    // post() — environment-dependent; cannot be meaningfully tested via GET
    // =========================================================================

    private function test_post(array &$results): void {
        $k = 'post';

        // Empty POST — no field name — should return empty array or empty string
        $result = post();
        $this->assert_true($results, $k,
            is_array($result) || $result === '',
            'No POST data: returns empty array or empty string',
            is_array($result) ? '(empty array)' : "(string: '{$result}')"
        );

        // Missing key — returns empty string
        $missing = post('nonexistent_key_xyz');
        $this->assert_eq($results, $k, $missing, '', 'Missing key returns empty string');

        $this->assert_skip($results, $k, 'POST field retrieval with actual POST data',
            'Cannot simulate form submission in a GET page context');
        $this->assert_skip($results, $k, 'post() with clean_up=true',
            'Cannot simulate form submission in a GET page context');
        $this->assert_skip($results, $k, 'post() with cast_numeric=true',
            'Cannot simulate form submission in a GET page context');
    }

    // =========================================================================
    // validation_errors() — depends on validation state; no errors in GET context
    // =========================================================================

    private function test_validation_errors(array &$results): void {
        $k = 'validation_errors';

        // No validation run — should return null
        $result = validation_errors();
        $this->assert_eq($results, $k, $result, null, 'No validation errors: returns null');

        $this->assert_skip($results, $k, 'validation_errors() with actual errors',
            'Requires a failed validation_run() call; not possible in a GET page context');
        $this->assert_skip($results, $k, 'validation_errors() with custom HTML wrapping',
            'Requires active validation errors to test wrapping');
    }
}

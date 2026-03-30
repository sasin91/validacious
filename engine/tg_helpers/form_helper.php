<?php
function generate_input_element(string $type, string $name, ?string $value = null, bool|string|null $checked = false, array $attributes = []): string {
    $data = ['type' => $type, 'name' => $name, 'value' => $value, 'checked' => $checked, 'attributes' => $attributes];
    return Modules::run('form/generate_input_element', $data);
}

function form_checkbox(string $name, ?string $value = null, bool|string|null $checked = false, array $attributes = []): string {
    $data = ['name' => $name, 'value' => $value, 'checked' => $checked, 'attributes' => $attributes];
    return Modules::run('form/form_checkbox', $data);
}

function form_radio(string $name, ?string $value = null, bool|string|null $checked = false, array $attributes = []): string {
    $data = ['name' => $name, 'value' => $value, 'checked' => $checked, 'attributes' => $attributes];
    return Modules::run('form/form_radio', $data);
}

function form_input(array $attributes = []): string {
    return Modules::run('form/form_input', $attributes);
}

function form_email(array $attributes = []): string {
    return Modules::run('form/form_email', $attributes);
}

function form_password(array $attributes = []): string {
    return Modules::run('form/form_password', $attributes);
}

function form_search(array $attributes = []): string {
    return Modules::run('form/form_search', $attributes);
}

function form_number(array $attributes = []): string {
    return Modules::run('form/form_number', $attributes);
}

function form_hidden(string $name, ?string $value = null, array $attributes = []): string {
    $data = ['name' => $name, 'value' => $value, 'attributes' => $attributes];
    return Modules::run('form/form_hidden', $data);
}

function form_open(string $location = '', string $method = 'post', array $attributes = []): string {
    $data = ['location' => $location, 'method' => $method, 'attributes' => $attributes];
    return Modules::run('form/form_open', $data);
}

function form_open_upload(string $location = '', array $attributes = []): string {
    $data = ['location' => $location, 'attributes' => $attributes];
    return Modules::run('form/form_open_upload', $data);
}

function form_close(): string {
    return Modules::run('form/form_close');
}

function get_attributes_str(array $attributes = []): string {
    return Modules::run('form/get_attributes_str', $attributes);
}

function form_label(string $label_text, string $input_id, array $attributes = []): string {
    $data = ['label_text' => $label_text, 'input_id' => $input_id, 'attributes' => $attributes];
    return Modules::run('form/form_label', $data);
}

function form_textarea(array $attributes = []): string {
    return Modules::run('form/form_textarea', $attributes);
}

function form_date(array $attributes = []): string {
    return Modules::run('form/form_date', $attributes);
}

function form_datetime_local(array $attributes = []): string {
    return Modules::run('form/form_datetime_local', $attributes);
}

function form_time(array $attributes = []): string {
    return Modules::run('form/form_time', $attributes);
}

function form_month(array $attributes = []): string {
    return Modules::run('form/form_month', $attributes);
}

function form_week(array $attributes = []): string {
    return Modules::run('form/form_week', $attributes);
}

function form_submit(string $submit_value = 'Submit', array $attributes = []): string {
    $data = ['submit_value' => $submit_value, 'attributes' => $attributes];
    return Modules::run('form/form_submit', $data);
}

function form_button(string $button_value = 'Button', array $attributes = []): string {
    $data = ['button_value' => $button_value, 'attributes' => $attributes];
    return Modules::run('form/form_button', $data);
}

function form_dropdown(string $name, array $options, ?string $selected = null, array $attributes = []): string {
    $data = ['name' => $name, 'options' => $options, 'selected' => $selected, 'attributes' => $attributes];
    return Modules::run('form/form_dropdown', $data);
}

function form_file_select(array $attributes = []): string {
    return Modules::run('form/form_file_select', $attributes);
}

function post(string|bool|null $field_name = null, bool $clean_up = false, bool $cast_numeric = false): string|int|float|array {
    $data = ['field_name' => $field_name, 'clean_up' => $clean_up, 'cast_numeric' => $cast_numeric];
    return Modules::run('form/post', $data);
}

function validation_errors(string|int|null $first_arg = null, ?string $closing_html = null): ?string {
    return Modules::run('validation/display_errors', ['first_arg' => $first_arg, 'closing_html' => $closing_html]);
}
<?php
function truncate_str(string $value, int $max_length): string {
    $data = ['value' => $value, 'max_length' => $max_length];
    return Modules::run('string_service/truncate_str', $data);
}

function truncate_words(string $value, int $max_words): string {
    $data = ['value' => $value, 'max_words' => $max_words];
    return Modules::run('string_service/truncate_words', $data);
}

function get_last_part(string $str, string $delimiter = '-'): string {
    $data = ['str' => $str, 'delimiter' => $delimiter];
    return Modules::run('string_service/get_last_part', $data);
}

function extract_content(string $string, string $start_delim, string $end_delim): string {
    $data = ['string' => $string, 'start_delim' => $start_delim, 'end_delim' => $end_delim];
    return Modules::run('string_service/extract_content', $data);
}

function remove_substr_between(string $start, string $end, string $haystack, bool $remove_all = false): string {
    $data = ['start' => $start, 'end' => $end, 'haystack' => $haystack, 'remove_all' => $remove_all];
    return Modules::run('string_service/remove_substr_between', $data);
}

function nice_price(float $num, ?string $currency_symbol = null): string|float {
    $data = ['num' => $num, 'currency_symbol' => $currency_symbol];
    return Modules::run('string_service/nice_price', $data);
}

function url_title(string $value, bool $transliteration = true): string {
    $data = ['value' => $value, 'transliteration' => $transliteration];
    return Modules::run('string_service/url_title', $data);
}

function sanitize_filename(string $filename, bool $transliteration = true, int $max_length = 200): string {
    $data = ['filename' => $filename, 'transliteration' => $transliteration, 'max_length' => $max_length];
    return Modules::run('string_service/sanitize_filename', $data);
}

function out(?string $input, string $output_format = 'html', string $encoding = 'UTF-8'): string {
    $data = ['input' => $input, 'output_format' => $output_format, 'encoding' => $encoding];
    return Modules::run('string_service/out', $data);
}

function make_rand_str(int $length = 32, bool $uppercase = false): string {
    $data = ['length' => $length, 'uppercase' => $uppercase];
    return Modules::run('string_service/make_rand_str', $data);
}

function replace_html_tags(string $content, array $specifications): string {
    $data = ['content' => $content, 'specifications' => $specifications];
    return Modules::run('string_service/replace_html_tags', $data);
}

function remove_html_code(string $content, string $opening_pattern, string $closing_pattern): string {
    $data = ['content' => $content, 'opening_pattern' => $opening_pattern, 'closing_pattern' => $closing_pattern];
    return Modules::run('string_service/remove_html_code', $data);
}

function filter_str(string $str, array $allowed_tags = []): string {
    $data = ['str' => $str, 'allowed_tags' => $allowed_tags];
    return Modules::run('string_service/filter_str', $data);
}

function filter_string(string $string, array $allowed_tags = []): string {
    $data = ['string' => $string, 'allowed_tags' => $allowed_tags];
    return Modules::run('string_service/filter_string', $data);
}

function filter_name(string $name, array $allowed_chars = []): string {
    $data = ['name' => $name, 'allowed_chars' => $allowed_chars];
    return Modules::run('string_service/filter_name', $data);
}
<?php
function current_url(): string {
    return Modules::run('url/current_url');
}

function segment(int $num, ?string $var_type = null): mixed {
    $data = [
        'num' => $num,
        'var_type' => $var_type
    ];
    return Modules::run('url/segment', $data);
}

function remove_query_string(string $string): string {
    return Modules::run('url/remove_query_string', $string);
}

function get_num_segments(): int {
    return Modules::run('url/get_num_segments');
}

function get_last_segment(): string {
    return Modules::run('url/get_last_segment');
}

function redirect(string $target_url): void {
    Modules::run('url/redirect', $target_url);
}

function previous_url(): string {
    return Modules::run('url/previous_url');
}

function anchor(string $url, ?string $text = null, array $attributes = []): string {
    $data = [
        'url' => $url,
        'text' => $text,
        'attributes' => $attributes
    ];
    return Modules::run('url/anchor', $data);
}
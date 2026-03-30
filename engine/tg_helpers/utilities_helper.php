<?php
function block_url(string $block_path = ''): void {
    Modules::run('utilities/block_url', $block_path);
}

function json($data, ?bool $kill_script = null): void {
    $params = [
        'data' => $data,
        'kill_script' => $kill_script
    ];
    Modules::run('utilities/json', $params);
}

function ip_address(): string {
    return Modules::run('utilities/ip_address');
}

function display(array $data): void {
    Modules::run('utilities/display', $data);
}

function return_file_info(string $file_string): array {
    return Modules::run('utilities/return_file_info', $file_string);
}

function sort_by_property(array &$array, string $property, string $direction = 'asc'): array {
    $params = [
        'array' => $array,
        'property' => $property,
        'direction' => $direction
    ];
    return Modules::run('utilities/sort_by_property', $params);
}

function sort_rows_by_property(array $array, string $property, string $direction = 'asc'): array {
    $params = [
        'array' => $array,
        'property' => $property,
        'direction' => $direction
    ];
    return Modules::run('utilities/sort_rows_by_property', $params);
}

function from_trongate_mx(): bool {
    return Modules::run('utilities/from_trongate_mx');
}
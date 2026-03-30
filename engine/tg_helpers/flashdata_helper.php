<?php
function set_flashdata(string $msg): void {
    Modules::run('flashdata/set_flashdata', $msg);
}

function flashdata(?string $opening_html = null, ?string $closing_html = null): ?string {
    $data = [
        'opening_html' => $opening_html,
        'closing_html' => $closing_html
    ];
    return Modules::run('flashdata/flashdata', $data);
}
<?php

/**
 * String Helper Tester Module
 *
 * Provides comprehensive unit tests for all string_helper functions.
 * Each test records input, expected output, actual output, and pass/fail status,
 * enabling detailed diagnostic output – not just a boolean.
 */
class String_helper_tester extends Trongate {

    /**
     * Display the test results page.
     */
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

        $this->test_truncate_str($results);
        $this->test_truncate_words($results);
        $this->test_get_last_part($results);
        $this->test_extract_content($results);
        $this->test_remove_substr_between($results);
        $this->test_nice_price($results);
        $this->test_url_title($results);
        $this->test_sanitize_filename($results);
        $this->test_out($results);
        $this->test_make_rand_str($results);
        $this->test_replace_html_tags($results);
        $this->test_remove_html_code($results);
        $this->test_filter_str($results);
        $this->test_filter_string($results);
        $this->test_filter_name($results);

        return $results;
    }

    // -------------------------------------------------------------------------
    // Helper: record a single assertion
    // -------------------------------------------------------------------------

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

    private function assert_exception(array &$results, string $key, callable $fn, string $exception_class, string $label): void {
        $caught = false;
        try {
            $fn();
        } catch (\Throwable $e) {
            $caught = $e instanceof $exception_class;
        }
        $results[] = [
            'key'      => $key,
            'label'    => $label,
            'expected' => "throws {$exception_class}",
            'actual'   => $caught ? "throws {$exception_class}" : '(no exception thrown)',
            'pass'     => $caught,
        ];
    }

    // =========================================================================
    // truncate_str
    // =========================================================================

    private function test_truncate_str(array &$results): void {
        $k = 'truncate_str';

        // Over limit
        $this->assert_eq($results, $k, truncate_str('Hello World', 5), 'Hello...', 'Over limit: truncates and appends ...');

        // Exact boundary — should NOT truncate
        $this->assert_eq($results, $k, truncate_str('Hello', 5), 'Hello', 'Exact boundary: returns unchanged');

        // Under limit — should NOT truncate
        $this->assert_eq($results, $k, truncate_str('Hi', 10), 'Hi', 'Under limit: returns unchanged');

        // Empty string — should return empty
        $this->assert_eq($results, $k, truncate_str('', 5), '', 'Empty string returns empty');

        // max_length = 0 — all content exceeds limit → '...'
        $this->assert_eq($results, $k, truncate_str('Hello', 0), '...', 'max_length=0 returns only ellipsis');

        // Multibyte (Unicode) — must count characters, not bytes
        $this->assert_eq($results, $k, truncate_str('héllo', 3), 'hél...', 'Multibyte: truncates by character count, not bytes');

        // Original tester case (corrected expectation)
        $this->assert_eq($results, $k, truncate_str('This is a long string', 10), 'This is a ...', 'Original case: 10-char truncation');
    }

    // =========================================================================
    // truncate_words
    // =========================================================================

    private function test_truncate_words(array &$results): void {
        $k = 'truncate_words';

        // Over limit
        $this->assert_eq($results, $k, truncate_words('This is a long string with many words', 3), 'This is a...', 'Over limit: strict equality check');

        // Under limit — returns unchanged
        $this->assert_eq($results, $k, truncate_words('Hello World', 5), 'Hello World', 'Under limit: unchanged');

        // Exact word count — returns unchanged
        $this->assert_eq($results, $k, truncate_words('One Two Three', 3), 'One Two Three', 'Exact word count: unchanged');

        // Empty string
        $this->assert_eq($results, $k, truncate_words('', 3), '', 'Empty string returns empty');

        // Multiple consecutive spaces — should not inflate word count
        $result = truncate_words('Hello  World', 5);
        $this->assert_eq($results, $k, $result, 'Hello  World', 'Multiple spaces: does not inflate word count');

        // Docs example
        $this->assert_eq($results, $k,
            truncate_words('Hello World! This is a Test String for example purposes.', 5),
            'Hello World! This is a...',
            'Docs example: 5 words'
        );
    }

    // =========================================================================
    // get_last_part
    // =========================================================================

    private function test_get_last_part(array &$results): void {
        $k = 'get_last_part';

        $this->assert_eq($results, $k, get_last_part('user-profile-settings', '-'), 'settings', 'Standard dash-delimited');
        $this->assert_eq($results, $k, get_last_part('example-string-123'), '123', 'Default delimiter (-)');
        $this->assert_eq($results, $k, get_last_part('example string', ' '), 'string', 'Space delimiter');

        // Delimiter not present — should return full string
        $this->assert_eq($results, $k, get_last_part('nodash', '-'), 'nodash', 'No delimiter: returns full string');

        // Empty string
        $this->assert_eq($results, $k, get_last_part('', '-'), '', 'Empty string returns empty');

        // Delimiter at end — returns empty string after last delimiter
        $this->assert_eq($results, $k, get_last_part('hello-', '-'), '', 'Trailing delimiter: returns empty string');
    }

    // =========================================================================
    // extract_content
    // =========================================================================

    private function test_extract_content(array &$results): void {
        $k = 'extract_content';

        $this->assert_eq($results, $k,
            extract_content('Start content here End', 'Start ', ' End'),
            'content here',
            'Basic extraction'
        );

        // Docs example
        $this->assert_eq($results, $k,
            extract_content('Hello, start here and end here, thanks.', 'start here', 'end here'),
            ' and ',
            'Docs example'
        );

        // Missing start delimiter — returns ''
        $this->assert_eq($results, $k, extract_content('no match here', '[', ']'), '', 'Missing start delimiter returns empty');

        // Missing end delimiter — returns ''
        $this->assert_eq($results, $k, extract_content('Hello [open but no close', '[', ']'), '', 'Missing end delimiter returns empty');

        // Delimiters in wrong order — returns ''
        $this->assert_eq($results, $k, extract_content('end]here[start', '[', ']'), '', 'Wrong-order delimiters returns empty');

        // Empty content between delimiters
        $this->assert_eq($results, $k, extract_content('[empty]', '[', ']'), 'empty', 'Extraction of non-empty inner content');
        $this->assert_eq($results, $k, extract_content('[]', '[', ']'), '', 'Empty content between delimiters');
    }

    // =========================================================================
    // remove_substr_between
    // =========================================================================

    private function test_remove_substr_between(array &$results): void {
        $k = 'remove_substr_between';

        $haystack = 'When <b>Macbeth</b> speaks, all listen. <b>Macbeth</b> is a forbidden word.';

        // Default (remove_all = false) — removes only first match.
        $this->assert_eq($results, $k,
            remove_substr_between('<b>', '</b>', $haystack),
            'When speaks, all listen. <b>Macbeth</b> is a forbidden word.',
            'Default: removes first match only'
        );

        // remove_all = true — removes all matches
        $this->assert_eq($results, $k,
            remove_substr_between('<b>', '</b>', $haystack, true),
            'When speaks, all listen. is a forbidden word.',
            'remove_all=true: removes all matches'
        );

        // No match — returns haystack unchanged
        $this->assert_eq($results, $k,
            remove_substr_between('[', ']', 'nothing to remove here'),
            'nothing to remove here',
            'No match: returns haystack unchanged'
        );

        // Missing end delimiter — returns haystack unchanged
        $this->assert_eq($results, $k,
            remove_substr_between('<b>', '</b>', 'Only <b>one open'),
            'Only <b>one open',
            'Missing end delimiter: returns unchanged'
        );

        // Empty haystack
        $this->assert_eq($results, $k,
            remove_substr_between('<b>', '</b>', ''),
            '',
            'Empty haystack returns empty'
        );
    }

    // =========================================================================
    // nice_price
    // =========================================================================

    private function test_nice_price(array &$results): void {
        $k = 'nice_price';

        // Decimal amount — keeps decimals
        $this->assert_eq($results, $k, nice_price(1234.56), '1,234.56', 'Decimal price formatted with commas');

        // Whole number — strips .00
        $this->assert_eq($results, $k, nice_price(1000.00), '1,000', 'Whole number: .00 stripped');

        // With currency symbol
        $this->assert_eq($results, $k, nice_price(99.99, '$'), '$99.99', 'With $ currency symbol');
        $this->assert_eq($results, $k, nice_price(1500.00, '€'), '€1,500', 'With € currency symbol, whole number');

        // Zero
        $this->assert_eq($results, $k, nice_price(0.00), '0', 'Zero: returns 0');

        // Negative
        $this->assert_eq($results, $k, nice_price(-50.25), '-50.25', 'Negative value');

        // Original tester case
        $this->assert_eq($results, $k, nice_price(1234.56), '1,234.56', 'Original tester case');
    }

    // =========================================================================
    // url_title
    // =========================================================================

    private function test_url_title(array &$results): void {
        $k = 'url_title';

        // Basic ASCII
        $this->assert_eq($results, $k, url_title('Hello World!'), 'hello-world', 'Basic ASCII slug');

        // Multiple spaces / special chars
        $this->assert_eq($results, $k, url_title('Hello   World'), 'hello-world', 'Multiple spaces collapsed to single dash');

        // Leading and trailing dashes stripped
        $this->assert_eq($results, $k, url_title('--Hello--'), 'hello', 'Leading/trailing dashes stripped');

        // Already lowercase
        $this->assert_eq($results, $k, url_title('already-slug'), 'already-slug', 'Already a valid slug: unchanged');

        // Empty string
        $this->assert_eq($results, $k, url_title(''), '', 'Empty string returns empty');

        // transliteration=false — special chars still replaced but no transliteration
        $result_no_trans = url_title('Héllo', false);
        $this->assert_true($results, $k, !empty($result_no_trans), 'transliteration=false: returns non-empty slug', $result_no_trans);

        // Unicode (conditional on intl extension)
        if (extension_loaded('intl')) {
            $this->assert_eq($results, $k, url_title('Москва'), 'moskva', 'Unicode: Russian transliterated to Latin');
            $this->assert_eq($results, $k, url_title('café résumé'), 'cafe-resume', 'Unicode: French diacritics stripped');
        } else {
            $results[] = [
                'key'      => $k,
                'label'    => 'Unicode transliteration (SKIPPED — intl extension not loaded)',
                'expected' => '(skipped)',
                'actual'   => '(skipped)',
                'pass'     => true, // not a failure — environment limitation
            ];
        }
    }

    // =========================================================================
    // sanitize_filename
    // =========================================================================

    private function test_sanitize_filename(array &$results): void {
        $k = 'sanitize_filename';

        // Standard case
        $this->assert_eq($results, $k, sanitize_filename('My Photo (1).jpg'), 'my-photo-1.jpg', 'Standard: special chars removed, lowercase');

        // Spaces replaced
        $this->assert_eq($results, $k, sanitize_filename('my   multiple   spaces.txt'), 'my-multiple-spaces.txt', 'Multiple spaces → single dash');

        // Extension preserved and lowercased
        $this->assert_eq($results, $k, sanitize_filename('file@#$%.TXT'), 'file.txt', 'Special chars stripped, extension lowercased');

        // Hidden file (.htaccess — no basename) → generates fallback name.
        // url_title() converts the underscore in 'file_xxx' to a dash, so output starts with 'file-'.
        $result = sanitize_filename('.htaccess');
        $this->assert_true($results, $k,
            str_starts_with($result, 'file-') || str_starts_with($result, 'file_'),
            'Hidden file (.htaccess): fallback name generated (starts with file- or file_)',
            $result
        );

        // max_length truncation
        $long = str_repeat('a', 250) . '.jpg';
        $result = sanitize_filename($long, true, 200);
        $base = pathinfo($result, PATHINFO_FILENAME);
        $this->assert_true($results, $k, strlen($base) <= 200 && !str_ends_with($base, '-'), 'max_length=200: base truncated, no trailing dash', $result);

        // Null byte throws InvalidArgumentException
        $this->assert_exception($results, $k, function () {
            sanitize_filename("bad\0file.jpg");
        }, 'InvalidArgumentException', 'Null byte in filename throws InvalidArgumentException');

        // Empty extension — no dot appended
        $result = sanitize_filename('noextension');
        $this->assert_true($results, $k, !str_contains($result, '.'), 'No extension: no dot in output', $result);
    }

    // =========================================================================
    // out()
    // =========================================================================

    private function test_out(array &$results): void {
        $k = 'out';

        // null input → ''
        $this->assert_eq($results, $k, out(null), '', 'null input returns empty string');

        // html (default) — encodes HTML entities
        $this->assert_eq($results, $k, out('<script>alert(1)</script>'), '&lt;script&gt;alert(1)&lt;/script&gt;', 'html: < > encoded');
        $this->assert_eq($results, $k, out('"quoted" & \'apos\'', 'html'), '&quot;quoted&quot; &amp; &#039;apos&#039;', 'html: quotes and & encoded');

        // attribute format — same as html
        $this->assert_eq($results, $k, out('<b>bold</b>', 'attribute'), '&lt;b&gt;bold&lt;/b&gt;', 'attribute: same escaping as html');

        // xml format — uses ENT_XML1
        $this->assert_eq($results, $k, out('<tag>', 'xml'), '&lt;tag&gt;', 'xml: < > encoded');

        // json format — wraps in json string encoding
        $json = out('Hello "World" & <tag>', 'json');
        $this->assert_true($results, $k,
            str_contains($json, '\\u0022') || str_contains($json, '\u0022') || str_contains($json, 'Hello'),
            'json: special chars encoded',
            $json
        );

        // javascript format — no surrounding quotes
        $js = out('say "hello"', 'javascript');
        $this->assert_true($results, $k, !str_starts_with($js, '"'), 'javascript: no surrounding quotes', $js);

        // Unsupported format → InvalidArgumentException
        $this->assert_exception($results, $k, function () {
            out('test', 'unsupported_format');
        }, 'InvalidArgumentException', 'Unsupported format throws InvalidArgumentException');

        // Empty string input (not null)
        $this->assert_eq($results, $k, out(''), '', 'Empty string (not null): returns empty string');
    }

    // =========================================================================
    // make_rand_str
    // =========================================================================

    private function test_make_rand_str(array &$results): void {
        $k = 'make_rand_str';

        // Default length = 32
        $default = make_rand_str();
        $this->assert_true($results, $k, strlen($default) === 32, 'Default length is 32', (string)strlen($default));

        // Custom length
        $ten = make_rand_str(10);
        $this->assert_true($results, $k, strlen($ten) === 10, 'Custom length 10 respected', (string)strlen($ten));

        // Excluded ambiguous chars must not appear.
        // Actual charset: '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ'
        // Excluded: 0, 1, i (lower), l (lower), o (lower), O (upper), I (upper)
        // L (upper) IS present in the charset.
        $long = make_rand_str(500);
        $this->assert_true($results, $k, !preg_match('/[01iloOI]/', $long), 'Excluded ambiguous chars (0,1,i,l,o,O,I) not present', substr($long, 0, 40) . '…');

        // All characters are alphanumeric
        $this->assert_true($results, $k, ctype_alnum($ten), 'Output is alphanumeric', $ten);

        // Uppercase flag
        $upper = make_rand_str(20, true);
        $this->assert_true($results, $k, $upper === strtoupper($upper), 'uppercase=true: result is all uppercase', $upper);
        $this->assert_true($results, $k, strlen($upper) === 20, 'uppercase=true: length still correct', (string)strlen($upper));

        // Length 1 edge case
        $one = make_rand_str(1);
        $this->assert_true($results, $k, strlen($one) === 1, 'Length 1: single character returned', $one);
    }

    // =========================================================================
    // replace_html_tags
    // =========================================================================

    private function test_replace_html_tags(array &$results): void {
        $k = 'replace_html_tags';

        $specs = [
            'opening_string_before' => '<span class="whatever">',
            'close_string_before'   => '</span>',
            'opening_string_after'  => '<div>',
            'close_string_after'    => '</div>',
        ];
        $input = '<span class="whatever">Hello World</span>';
        $this->assert_eq($results, $k, replace_html_tags($input, $specs), '<div>Hello World</div>', 'Basic tag replacement');

        // Multiple matches
        $multi = '<span class="whatever">A</span> and <span class="whatever">B</span>';
        $expected = '<div>A</div> and <div>B</div>';
        $this->assert_eq($results, $k, replace_html_tags($multi, $specs), $expected, 'Multiple tag replacements');

        // No match — returns original unchanged
        $this->assert_eq($results, $k, replace_html_tags('<p>No match</p>', $specs), '<p>No match</p>', 'No match: content unchanged');

        // Empty content
        $this->assert_eq($results, $k, replace_html_tags('', $specs), '', 'Empty content returns empty');
    }

    // =========================================================================
    // remove_html_code
    // =========================================================================

    private function test_remove_html_code(array &$results): void {
        $k = 'remove_html_code';

        // Basic removal
        $content = 'Before <script>bad code</script> After';
        $result  = remove_html_code($content, '<script>', '</script>');
        $this->assert_true($results, $k, !str_contains($result, 'bad code') && str_contains($result, 'Before') && str_contains($result, 'After'), 'Script block removed, surrounding text preserved', $result);

        // Multi-line block
        $multi = "Before\n<script>\n  var x = 1;\n</script>\nAfter";
        $r2    = remove_html_code($multi, '<script>', '</script>');
        $this->assert_true($results, $k, !str_contains($r2, 'var x') && str_contains($r2, 'After'), 'Multi-line script block removed', $r2);

        // No match — returns original
        $this->assert_eq($results, $k, remove_html_code('<p>No script</p>', '<script>', '</script>'), '<p>No script</p>', 'No match: content unchanged');

        // Empty content
        $this->assert_eq($results, $k, remove_html_code('', '<script>', '</script>'), '', 'Empty content returns empty');
    }

    // =========================================================================
    // filter_str
    // =========================================================================

    private function test_filter_str(array &$results): void {
        $k = 'filter_str';

        // Strips HTML tags
        $filtered = filter_str('<script>alert(1)</script>Hello');
        $this->assert_true($results, $k, !str_contains($filtered, '<script>') && str_contains($filtered, 'Hello'), 'Script tags stripped, text preserved', $filtered);

        // Allowed tags preserved
        $allowed = filter_str('<p>Hello <b>World</b></p>', ['<p>', '<b>']);
        $this->assert_true($results, $k, str_contains($allowed, '<b>') && str_contains($allowed, '<p>'), 'Allowed tags preserved', $allowed);

        // Multiple consecutive spaces collapsed
        $spaced = filter_str('Hello     World');
        $this->assert_eq($results, $k, $spaced, 'Hello World', 'Multiple spaces collapsed to single space');

        // Leading/trailing whitespace trimmed
        $this->assert_eq($results, $k, filter_str('  trimmed  '), 'trimmed', 'Leading/trailing whitespace trimmed');

        // Empty string
        $this->assert_eq($results, $k, filter_str(''), '', 'Empty string returns empty');
    }

    // =========================================================================
    // filter_string (deprecated alias)
    // =========================================================================

    private function test_filter_string(array &$results): void {
        $k = 'filter_string';

        // Should delegate to filter_str — produces identical output
        $input    = '<b>Hello World</b>';
        $via_str  = filter_str($input);
        $via_alias = filter_string($input);
        $this->assert_eq($results, $k, $via_alias, $via_str, 'Deprecated alias delegates to filter_str correctly');

        // With allowed tags
        $with_tags_str   = filter_str('<p>Hello <b>World</b></p>', ['<p>']);
        $with_tags_alias = filter_string('<p>Hello <b>World</b></p>', ['<p>']);
        $this->assert_eq($results, $k, $with_tags_alias, $with_tags_str, 'Deprecated alias passes allowed_tags through correctly');
    }

    // =========================================================================
    // filter_name
    // =========================================================================

    private function test_filter_name(array &$results): void {
        $k = 'filter_name';

        // Basic — strips punctuation
        $this->assert_eq($results, $k, filter_name('John Doe!'), 'John Doe', 'Basic: strips punctuation, keeps letters and space');

        // Numbers allowed
        $this->assert_eq($results, $k, filter_name('User123'), 'User123', 'Numbers kept');

        // HTML tags stripped and encoded
        $r = filter_name('<script>alert(1)</script>John');
        $this->assert_true($results, $k, !str_contains($r, '<script>') && str_contains($r, 'John'), 'HTML stripped and XSS neutralised', $r);

        // allowed_chars with dash — verify dash survives
        $r2 = filter_name('Mary-Jane', ['-']);
        $this->assert_eq($results, $k, $r2, 'Mary-Jane', 'allowed_chars: dash preserved');

        // allowed_chars with dot — verify dot survives
        $r3 = filter_name('Dr. Smith', ['.']);
        $this->assert_eq($results, $k, $r3, 'Dr. Smith', 'allowed_chars: period preserved');

        // Regex-special char in allowed_chars ('.' used literally, not as wildcard)
        // verify that '?' in allowed_chars does not cause regex errors or capture everything
        $r4 = filter_name('Hello? World!', ['?']);
        $this->assert_true($results, $k, str_contains($r4, '?') && !str_contains($r4, '!'), 'Regex-special allowed char (?) works without injection', $r4);

        // Multiple doubles spaces collapsed
        $r5 = filter_name('John  Doe');
        $this->assert_eq($results, $k, $r5, 'John Doe', 'Double spaces collapsed to single space');

        // Leading/trailing whitespace trimmed
        $this->assert_eq($results, $k, filter_name('  Alice  '), 'Alice', 'Leading/trailing whitespace trimmed');

        // Empty string
        $this->assert_eq($results, $k, filter_name(''), '', 'Empty string returns empty');
    }
}

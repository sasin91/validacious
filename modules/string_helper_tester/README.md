# String Helper Tester Module

This module provides comprehensive unit tests for all `string_helper` functions in the Trongate framework.

## Usage

1. Navigate to `/string_helper_tester` in your browser.
2. The page displays per-assertion results grouped by helper, showing expected value, actual value, and PASS/FAIL.

## Tests Performed

### Truncation
- **truncate_str**: Over limit, exact boundary, under limit, empty string, max_length=0
- **truncate_words**: Over limit (strict equality), under limit, exact word count, empty string, multiple consecutive spaces, docs example

### Extraction & Removal
- **get_last_part**: Standard, default delimiter, space delimiter, no delimiter present, empty string, trailing delimiter
- **extract_content**: Basic, docs example, missing start/end delimiter, wrong-order delimiters, empty between delimiters
- **remove_substr_between**: First match only, remove_all=true, no match, missing end delimiter, empty haystack

### Formatting
- **nice_price**: Decimal, whole number (.00 stripped), with currency symbol, zero, negative

### Slug & Filename
- **url_title**: Basic ASCII, multiple spaces, leading/trailing dashes, empty string, transliteration=false; Unicode (if intl loaded)
- **sanitize_filename**: Standard, multiple spaces, special chars, hidden file (.htaccess fallback), max_length truncation, null byte → exception, no extension

### Output Safety
- **out**: null input, html/attribute/xml/json/javascript formats, unsupported format → exception, empty string

### Randomisation
- **make_rand_str**: Default length (32), custom length, excluded ambiguous chars, alphanum-only, uppercase flag, length=1

### HTML Manipulation
- **replace_html_tags**: Basic swap, multiple matches, no match, empty content
- **remove_html_code**: Basic removal, multi-line block, no match, empty content

### Filtering
- **filter_str**: Strip HTML, allowed tags preserved, multiple spaces collapsed, trim, empty
- **filter_string** *(deprecated alias)*: Delegates correctly to filter_str (with and without allowed_tags)
- **filter_name**: Basic, numbers, HTML/XSS, allowed_chars (dash, dot, regex-special), double spaces, trim, empty

## Dependencies

- Requires the `string_service` module to be available.
- Assumes `string_helper.php` is loaded (typically auto-loaded in Trongate).
- Unicode transliteration tests require the PHP `intl` extension (skipped gracefully if unavailable).

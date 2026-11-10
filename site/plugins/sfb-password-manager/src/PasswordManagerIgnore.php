<?php

/**
 * Tells the browser extensions of the common password managers to leave the form fields of a page alone.
 *
 * The forms of this site (contact, membership, ...) are no login or registration forms, so password managers
 * shall neither offer to fill them nor to save their values. Besides "autocomplete" (which is deliberately
 * not touched, the browser autofill helps the visitors) every password manager has its own opt-out attribute.
 * Except for 1Password, which also accepts it on the <body> (see site/snippets/header.php), these attributes
 * are only evaluated on the field itself. That's why they are added to every field of the rendered page.
 */
class PasswordManagerIgnore
{
    /**
     * Opt-out attributes of the password managers (name => value, null renders a boolean attribute).
     *
     * @var array<string, string|null>
     */
    const ATTRIBUTES = [
        // 1Password
        'data-1p-ignore' => null,
        // Bitwarden (only the presence matters)
        'data-bwignore' => 'true',
        // LastPass
        'data-lpignore' => 'true',
        // Proton Pass
        'data-protonpass-ignore' => 'true',
        // Dashlane (the value "other" prevents the autofill of the field)
        'data-form-type' => 'other',
    ];

    /**
     * Input types password managers don't offer anything for.
     */
    const SKIPPED_INPUT_TYPES = ['hidden', 'submit', 'button', 'image', 'reset'];

    /**
     * Add the opt-out attributes of all password managers to every <input>, <textarea> and <select> of the HTML.
     *
     * Comments, scripts and styles are left untouched, as well as fields which don't take any input
     * (hidden, submit, button, ...) and attributes which are already set on a field.
     */
    public static function apply(string $html): string
    {
        // odd entries are the comments, scripts and styles which are skipped
        $parts = preg_split(
            '~(<!--.*?-->|<script\b.*?</script\s*>|<style\b.*?</style\s*>)~is',
            $html,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

        if ($parts === false) {
            return $html;
        }

        foreach ($parts as $index => $part) {
            if ($index % 2 === 0) {
                $parts[$index] = static::applyToFields($part);
            }
        }

        return implode('', $parts);
    }

    /**
     * Add the attributes to the fields of a HTML fragment without comments, scripts and styles.
     */
    protected static function applyToFields(string $html): string
    {
        // the attributes may contain a ">" within quotes, e.g. placeholder="a > b"
        $result = preg_replace_callback(
            '~<(input|textarea|select)(?=[\s/>])((?:[^>"\']|"[^"]*"|\'[^\']*\')*)>~i',
            static function (array $match): string {
                return static::applyToTag($match[0], strtolower($match[1]), $match[2]);
            },
            $html
        );

        return $result ?? $html;
    }

    /**
     * Add the missing attributes to a single field tag.
     *
     * @param string $tag The complete tag, returned unchanged if nothing has to be added
     * @param string $name Lowercase tag name
     * @param string $attributes The attributes of the tag, including a trailing slash of self-closing tags
     */
    protected static function applyToTag(string $tag, string $name, string $attributes): string
    {
        if ($name === 'input' && static::isSkippedInput($attributes)) {
            return $tag;
        }

        $missing = [];

        foreach (static::ATTRIBUTES as $attribute => $value) {
            if (preg_match('~\s' . preg_quote($attribute, '~') . '(?=[\s=/>]|$)~i', $attributes) === 1) {
                continue;
            }

            $missing[] = $value === null ? $attribute : $attribute . '="' . $value . '"';
        }

        if ($missing === []) {
            return $tag;
        }

        // keep the slash of self-closing tags behind the new attributes
        preg_match('~^(.*?)(\s*/?)$~s', $attributes, $split);

        return '<' . substr($tag, 1, strlen($name)) . $split[1] . ' ' . implode(' ', $missing) . $split[2] . '>';
    }

    /**
     * Whether the attributes of an <input> declare a type which doesn't take any input.
     */
    protected static function isSkippedInput(string $attributes): bool
    {
        $pattern = '~\stype\s*=\s*(?:"\s*(' . implode('|', static::SKIPPED_INPUT_TYPES) . ')\s*"|\'\s*(' . implode('|', static::SKIPPED_INPUT_TYPES) . ')\s*\'|(' . implode('|', static::SKIPPED_INPUT_TYPES) . ')(?=[\s/>]|$))~i';

        return preg_match($pattern, $attributes) === 1;
    }
}

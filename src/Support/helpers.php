<?php

use PHLAK\SemVer\Exceptions\InvalidVersionException;
use PHLAK\SemVer\Version;

if (! function_exists('semver')) {
    /**
     * Create a SemVer version object.
     *
     * @throws InvalidVersionException If the provided semantic version string is invalid.
     */
    function semver(string $string): Version
    {
        return new Version($string);
    }
}

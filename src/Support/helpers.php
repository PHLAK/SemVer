<?php

use PHLAK\SemVer\Version;

if (! function_exists('semver')) {
    /**
     * Create a SemVer version object.
     *
     * @return \PHLAK\SemVer\Version
     * @throws \PHLAK\SemVer\Exceptions\InvalidVersionException
     */
    function semver(string $string): Version
    {
        return new PHLAK\SemVer\Version($string);
    }
}

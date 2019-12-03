<?php

use PHLAK\SemVer\Version;

if (! function_exists('semver')) {
    /**
     * Create a SemVer version object.
     *
     * @return \PHLAK\SemVer\Version
     */
    function semver(string $string) : Version
    {
        return new PHLAK\SemVer\Version($string);
    }
}

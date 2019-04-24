<?php

namespace PHLAK\SemVer\Traits;

use PHLAK\SemVer\Version;

trait Comparable
{
    /**
     * Check if this Version object is greater than another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is greater than the comparing
     *              object, otherwise false
     */
    public function gt(Version $version) : bool
    {
        $thisVersion = [$this->major, $this->minor, $this->patch, $this->preRelease];
        $thatVersion = [$version->major, $version->minor, $version->patch, $version->preRelease];

        return ($thisVersion <=> $thatVersion) == 1;
    }

    /**
     * Check if this Version object is less than another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is less than the comparing
     *              object, otherwise false
     */
    public function lt(Version $version) : bool
    {
        $thisVersion = [$this->major, $this->minor, $this->patch, $this->preRelease];
        $thatVersion = [$version->major, $version->minor, $version->patch, $version->preRelease];

        return ($thisVersion <=> $thatVersion) == -1;
    }

    /**
     * Check if this Version object is equal to than another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is equal to the comparing
     *              object, otherwise false
     */
    public function eq(Version $version) : bool
    {
        $thisVersion = [$this->major, $this->minor, $this->patch, $this->preRelease];
        $thatVersion = [$version->major, $version->minor, $version->patch, $version->preRelease];

        return ($thisVersion <=> $thatVersion) == 0;
    }

    /**
     * Check if this Version object is not equal to another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is not equal to the comparing
     *              object, otherwise false
     */
    public function neq(Version $version) : bool
    {
        $thisVersion = [$this->major, $this->minor, $this->patch, $this->preRelease];
        $thatVersion = [$version->major, $version->minor, $version->patch, $version->preRelease];

        return ($thisVersion <=> $thatVersion) != 0;
    }

    /**
     * Check if this Version object is greater than or equal to another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is greater than or equal to the
     *              comparing object, otherwise false
     */
    public function gte(Version $version) : bool
    {
        $thisVersion = [$this->major, $this->minor, $this->patch, $this->preRelease];
        $thatVersion = [$version->major, $version->minor, $version->patch, $version->preRelease];

        return ($thisVersion <=> $thatVersion) >= 0;
    }

    /**
     * Check if this Version object is less than or equal to another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is less than or equal to the
     *              comparing object, otherwise false
     */
    public function lte(Version $version) : bool
    {
        $thisVersion = [$this->major, $this->minor, $this->patch, $this->preRelease];
        $thatVersion = [$version->major, $version->minor, $version->patch, $version->preRelease];

        return ($thisVersion <=> $thatVersion) <= 0;
    }
}

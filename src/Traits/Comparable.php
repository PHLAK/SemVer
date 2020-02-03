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
        // compare without prerelease
        $thisVersion = [$this->major, $this->minor, $this->patch];
        $thatVersion = [$version->major, $version->minor, $version->patch];
        $result = $thisVersion <=> $thatVersion;
        // if both version are equal we should compare pre release only if both property are sets
        if (0 === $result) {
            $result = $this->comparePreReleases($this->preRelease, $version->preRelease);
        }

        return 1 === $result;
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
        $thisVersion = [$this->major, $this->minor, $this->patch];
        $thatVersion = [$version->major, $version->minor, $version->patch];
        $result = $thisVersion <=> $thatVersion;
        // if both version are equal we should compare pre release only if both property are sets
        if (0 === $result) {
            $result = $this->comparePreReleases($this->preRelease, $version->preRelease);
        }

        return $result === -1;
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

        return ($thisVersion <=> $thatVersion) === 0;
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

        return ($thisVersion <=> $thatVersion) !== 0;
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
        $thisVersion = [$this->major, $this->minor, $this->patch];
        $thatVersion = [$version->major, $version->minor, $version->patch];
        $result = $thisVersion <=> $thatVersion;
        // if both version are equal we should compare pre release only if both property are sets
        if (0 === $result) {
            $result = $this->comparePreReleases($this->preRelease, $version->preRelease);
        }

        return $result >= 0;
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
        $thisVersion = [$this->major, $this->minor, $this->patch];
        $thatVersion = [$version->major, $version->minor, $version->patch];
        $result = $thisVersion <=> $thatVersion;
        // if both version are equal we should compare pre release only if both property are sets
        if (0 === $result) {
            $result = $this->comparePreReleases($this->preRelease, $version->preRelease);
        }

        return $result <= 0;
    }

    /**
     * Compare two pre-releases.
     *
     * @param string|null $preRelease1
     * @param string|null $preRelease2
     *
     * @return int
     */
    private function comparePreReleases($preRelease1, $preRelease2) : int
    {
        if ($preRelease1 !== null && $preRelease2 === null) {
            return -1;
        }

        if ($preRelease1 === null && $preRelease2 !== null) {
            return 1;
        }

        $preReleases1 = explode('.', $preRelease1 ?? '');
        $preReleases2 = explode('.', $preRelease2 ?? '');

        $preReleases1 = array_pad($preReleases1, count($preReleases2), null);
        $preReleases2 = array_pad($preReleases2, count($preReleases1), null);

        return $preReleases1 <=> $preReleases2;
    }
}

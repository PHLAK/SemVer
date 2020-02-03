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
    public function gt(Version $version): bool
    {
        // compare without prerelease
        $thisVersion = [$this->major, $this->minor, $this->patch];
        $thatVersion = [$version->major, $version->minor, $version->patch];
        $result = $thisVersion <=> $thatVersion;
        // if both version are equal we should compare pre release only if both property are sets
        if (0 === $result) {
            $result = $this->comparePrerelease($this->preRelease, $version->preRelease);
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
    public function lt(Version $version): bool
    {
        $thisVersion = [$this->major, $this->minor, $this->patch];
        $thatVersion = [$version->major, $version->minor, $version->patch];
        $result = $thisVersion <=> $thatVersion;
        // if both version are equal we should compare pre release only if both property are sets
        if (0 === $result) {
            $result = $this->comparePrerelease($this->preRelease, $version->preRelease);
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
    public function eq(Version $version): bool
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
    public function neq(Version $version): bool
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
    public function gte(Version $version): bool
    {
        $thisVersion = [$this->major, $this->minor, $this->patch];
        $thatVersion = [$version->major, $version->minor, $version->patch];
        $result = $thisVersion <=> $thatVersion;
        // if both version are equal we should compare pre release only if both property are sets
        if (0 === $result) {
            $result = $this->comparePrerelease($this->preRelease, $version->preRelease);
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
    public function lte(Version $version): bool
    {
        $thisVersion = [$this->major, $this->minor, $this->patch];
        $thatVersion = [$version->major, $version->minor, $version->patch];
        $result = $thisVersion <=> $thatVersion;
        // if both version are equal we should compare pre release only if both property are sets
        if (0 === $result) {
            $result = $this->comparePrerelease($this->preRelease, $version->preRelease);
        }

        return $result <= 0;
    }

    /**
     * return 1 if $prerelease is greater than $prerelease, 0 if equal else -1.
     *
     * @param $prerelease1
     * @param $prerelease2
     *
     * @return int
     */
    private function comparePrerelease($prerelease1, $prerelease2): int
    {
        // empty pre-release is greater than filled one
        if ($prerelease1 === null && $prerelease2 !== null) {
            return 1;
        }
        if ($prerelease1 !== null && $prerelease2 === null) {
            return -1;
        }
        // pre-release can be composed of multiple pre-release identifier like alpha.beta.1 or .beta.11
        // beta.2 is smaller than beta.11 we should split each identifier and compare them
        $prereleaseList1 = explode('.', $prerelease1);
        $prereleaseList2 = explode('.', $prerelease2);
        // both list must have the same size
        $prereleaseList1 = array_pad($prereleaseList1, count($prereleaseList2), null);
        $prereleaseList2 = array_pad($prereleaseList2, count($prereleaseList1), null);
        $result = 0;
        foreach ($prereleaseList1 as $idx => $pre1) {
            $pre2 = $prereleaseList2[$idx];
            $result = $pre1 <=> $pre2;
            if (0 !== $result) {
                return $result;
            }
        }

        return $result;
    }
}

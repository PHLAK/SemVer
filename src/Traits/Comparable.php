<?php

namespace PHLAK\SemVer\Traits;

use PHLAK\SemVer\Enums\Compare;
use PHLAK\SemVer\Version;

trait Comparable
{
    /**
     * Compare two versions. Returns -1, 0 or 1 if the first version is less
     * than, equal to or greater than the second version respectively.
     *
     * @param Version $version1 An instance of SemVer/Version
     * @param Version $version2 An instance of SemVer/Version
     */
    public static function compare(Version $version1, Version $version2, Compare $comparison = Compare::FULL): int
    {
        [$v1, $v2] = match ($comparison) {
            Compare::MAJOR => [[$version1->major], [$version2->major]],
            Compare::MINOR => [[$version1->major, $version1->minor], [$version2->major, $version2->minor]],
            default => [[$version1->major, $version1->minor, $version1->patch], [$version2->major, $version2->minor, $version2->patch]]
        };

        $baseComparison = $v1 <=> $v2;

        if ($baseComparison !== 0 || $comparison !== Compare::FULL) {
            return $baseComparison;
        }

        if ($version1->preRelease !== null && $version2->preRelease === null) {
            return -1;
        }

        if ($version1->preRelease === null && $version2->preRelease !== null) {
            return 1;
        }

        $v1preReleaseParts = explode('.', $version1->preRelease ?? '');
        $v2preReleaseParts = explode('.', $version2->preRelease ?? '');

        $preReleases1 = array_pad($v1preReleaseParts, count($v2preReleaseParts), null);
        $preReleases2 = array_pad($v2preReleaseParts, count($v1preReleaseParts), null);

        return $preReleases1 <=> $preReleases2;
    }

    /**
     * Check if this Version object is greater than another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is greater than the comparing
     *              object, otherwise false
     */
    public function gt(Version $version, Compare $comparison = Compare::FULL): bool
    {
        return self::compare($this, $version, $comparison) > 0;
    }

    /**
     * Check if this Version object is less than another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is less than the comparing
     *              object, otherwise false
     */
    public function lt(Version $version, Compare $comparison = Compare::FULL): bool
    {
        return self::compare($this, $version, $comparison) < 0;
    }

    /**
     * Check if this Version object is equal to than another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is equal to the comparing
     *              object, otherwise false
     */
    public function eq(Version $version, Compare $comparison = Compare::FULL): bool
    {
        return self::compare($this, $version, $comparison) === 0;
    }

    /**
     * Check if this Version object is not equal to another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is not equal to the comparing
     *              object, otherwise false
     */
    public function neq(Version $version, Compare $comparison = Compare::FULL): bool
    {
        return self::compare($this, $version, $comparison) !== 0;
    }

    /**
     * Check if this Version object is greater than or equal to another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is greater than or equal to the
     *              comparing object, otherwise false
     */
    public function gte(Version $version, Compare $comparison = Compare::FULL): bool
    {
        return self::compare($this, $version, $comparison) >= 0;
    }

    /**
     * Check if this Version object is less than or equal to another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is less than or equal to the
     *              comparing object, otherwise false
     */
    public function lte(Version $version, Compare $comparison = Compare::FULL): bool
    {
        return self::compare($this, $version, $comparison) <= 0;
    }
}

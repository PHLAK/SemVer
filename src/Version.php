<?php

namespace PHLAK\SemVer;

use PHLAK\SemVer\Exceptions\InvalidVersionException;

class Version
{
    /** @var int Major release number */
    protected $major;

    /** @var int Minor release number */
    protected $minor;

    /** @var int Patch release number */
    protected $patch;

    /** @var string|null Pre-release value */
    protected $preRelease;

    /** @var string|null Build release value */
    protected $build;

    /**
     * Class constructor, runs on object creation.
     *
     * @param string $version Version string
     */
    public function __construct(string $version = '0.1.0')
    {
        $this->setVersion($version);
    }

    /**
     * Magic get method; privied access to version properties.
     *
     * @param string $property Version property
     *
     * @return mixed Version property value
     */
    public function __get(string $property)
    {
        return $this->$property;
    }

    /**
     * Magic toString method; allows object interaction as if it were a string.
     *
     * @return string Current version string
     */
    public function __toString() : string
    {
        $version = implode('.', [$this->major, $this->minor, $this->patch]);
        $version .= isset($this->preRelease) ? '-' . $this->preRelease : null;
        $version .= isset($this->build) ? '+' . $this->build : null;

        return $version;
    }

    /**
     * Set (override) the entire version value.
     *
     * @param string $version Version string
     *
     * @return self This Version object
     */
    public function setVersion(string $version) : self
    {
        $semverRegex = '/^v?(\d+)\.(\d+)\.(\d+)(?:-([0-9A-Z-.]+))?(?:\+([0-9A-Z-.]+)?)?$/i';

        if (! preg_match($semverRegex, $version, $matches)) {
            throw new InvalidVersionException('Invalid Semantic Version string provided');
        }

        $this->major = (int) $matches[1];
        $this->minor = (int) $matches[2];
        $this->patch = (int) $matches[3];
        $this->preRelease = @$matches[4] ?: null;
        $this->build = @$matches[5] ?: null;

        return $this;
    }

    /**
     * Increment the major version value by one.
     *
     * @return self This Version object
     */
    public function incrementMajor() : self
    {
        $this->setMajor($this->major + 1);

        return $this;
    }

    /**
     * Set the major version to a custom value.
     *
     * @param int $value Positive integer value
     *
     * @return self This Version object
     */
    public function setMajor(int $value) : self
    {
        $this->major = $value;
        $this->minor = 0;
        $this->patch = 0;
        $this->preRelease = null;
        $this->build = null;

        return $this;
    }

    /**
     * Increment the minor version value by one.
     *
     * @return self This Version object
     */
    public function incrementMinor() : self
    {
        $this->setMinor($this->minor + 1);

        return $this;
    }

    /**
     * Set the minor version to a custom value.
     *
     * @param int $value Positive integer value
     *
     * @return self This Version object
     */
    public function setMinor(int $value) : self
    {
        $this->minor = $value;
        $this->patch = 0;
        $this->preRelease = null;
        $this->build = null;

        return $this;
    }

    /**
     * Increment the patch version value by one.
     *
     * @return self This Version object
     */
    public function incrementPatch() : self
    {
        $this->setPatch($this->patch + 1);

        return $this;
    }

    /**
     * Set the patch version to a custom value.
     *
     * @param int $value Positive integer value
     *
     * @return self This Version object
     */
    public function setPatch(int $value) : self
    {
        $this->patch = $value;
        $this->preRelease = null;
        $this->build = null;

        return $this;
    }

    /**
     * Set the pre-release string to a custom value.
     *
     * @param string|null $value A new pre-release value
     *
     * @return self This Version object
     */
    public function setPreRelease($value) : self
    {
        $this->preRelease = $value;

        return $this;
    }

    /**
     * Set the build string to a custom value.
     *
     * @param string|null $value A new build value
     *
     * @return self This Version object
     */
    public function setBuild($value) : self
    {
        $this->build = $value;

        return $this;
    }

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

    /**
     * Get the version string prefixed with a custom string.
     *
     * @param string $prefix String to prepend to the version string
     *                       (default: 'v')
     *
     * @return string Prefixed version string
     */
    public function prefix($prefix = 'v') : string
    {
        return $prefix . $this->toString();
    }

    /**
     * Get the current version value as a string.
     *
     * @return string Current version string
     */
    private function toString() : string
    {
        return (string) $this;
    }
}

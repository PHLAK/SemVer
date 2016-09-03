<?php

namespace SemVer;

class SemVer
{
    /** @var int Major release number */
    protected $major;

    /** @var int Minor release number */
    protected $minor;

    /** @var int Patch release number */
    protected $patch;

    /** @var string Pre-release value */
    protected $preRelease;

    /** @var string Build release value */
    protected $build;

    /**
     * SemVer\SemVer constructor, runs on object creation
     *
     * @param string $version Semantic version string
     */
    public function __construct($version = 'v0.1.0')
    {
        $this->setVersion($version);
    }

    /**
     * Set (override) the entire semantic version value
     *
     * @param string $version Semantic version string
     */
    public function setVersion($version)
    {
        $semverRegex = '/v?(\d+)\.(\d+)\.(\d+)(?:-([0-9A-Z-.]+))?(?:\+([0-9A-Z-.]+)?)?/i';

        if (! preg_match($semverRegex, $version, $matches)) {
            throw new RuntimeException('Invalid version string supplied: ' . $version);
        }

        $this->major      = $matches[1];
        $this->minor      = $matches[2];
        $this->patch      = $matches[3];
        $this->preRelease = @$matches[4] ?: null;
        $this->build      = @$matches[5] ?: null;

        return $this;
    }

    /**
     * Get the current semantic version value
     *
     * @param  boolean $prefix If false, don't prefix the version string with
     *                         a 'v' (default: true).
     *
     * @return string          Current semantic version value
     */
    public function getVersion($prefix = true)
    {
        $version = $prefix ? 'v' : null;

        $version .= implode('.', [$this->major, $this->minor, $this->patch]);
        $version .= isset($this->preRelease) ? '-' . $this->preRelease : null;
        $version .= isset($this->build) ? '+' . $this->build : null;

        return $version;
    }

    /**
     * Get the current major version value
     *
     * @return int The major version value
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Increment the major version value by one
     *
     * @return object This SemVer\SemVer object
     */
    public function incrementMajor()
    {
        $this->setMajor($this->major + 1);
        return $this;
    }

    /**
     * Set the major version to a custom value
     *
     * @param  int    $value Positive integer value
     *
     * @return object        This SemVer\SemVer object
     */
    public function setMajor($value)
    {
        $this->major = $value;
        $this->minor = 0;
        $this->patch = 0;
        $this->preRelease   = null;
        return $this;
    }

    /**
     * Get the current minor version value
     *
     * @return int The minor version value
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     * Increment the minor version value by one
     *
     * @return object This SemVer\SemVer object
     */
    public function incrementMinor()
    {
        $this->setMinor($this->minor + 1);
        return $this;
    }

    /**
     * Set the minor version to a custom value
     *
     * @param  int     $value Positive integer value
     *
     * @return object         This SemVer\SemVer object
     */
    public function setMinor($value)
    {
        $this->minor = $value;
        $this->patch = 0;
        $this->preRelease   = null;
        return $this;
    }

    /**
     * Get the current patch version value
     *
     * @return int The patch version value
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     * Increment the patch version value by one
     *
     * @return object This SemVer\SemVer object
     */
    public function incrementPatch()
    {
        $this->setPatch($this->patch + 1);
        return $this;
    }

    /**
     * Set the patch version to a custom value
     *
     * @param  int    $value Positive integer value
     *
     * @return object        This SemVer\SemVer object
     */
    public function setPatch($value)
    {
        $this->patch = $value;
        $this->preRelease   = null;
        return $this;
    }

    /**
     * Get the current Pre-release string value
     *
     * @return string The pre-release string value
     */
    public function getPreRelease()
    {
        return $this->preRelease;
    }

    /**
     * Set the pre-release string to a custom value
     *
     * @param  string $value A new pre-release value
     *
     * @return object        This SemVer\SemVer object
     */
    public function setPreRelease($value)
    {
        $this->preRelease = $value;
        return $this;
    }

    /**
     * Get the current build string value
     *
     * @return string The build string value
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * Set the build string to a custom value
     *
     * @param  string $value A new build value
     *
     * @return object        This SemVer\SemVer object
     */
    public function setBuild($value)
    {
        $this->build = $value;
        return $this;
    }

    /**
     * Check if this SemVer version object is greater than another
     *
     * @param  SemVer $semver An instance of SemVer/SemVer
     *
     * @return bool           True if this SemVer object version is greater than
     *                        the comparing object, otherwise false
     */
    public function greaterThan(SemVer $semver)
    {
        if ($this->major > $semver->getMajor()) return true;

        if ($this->major == $semver->getMajor()
            && $this->minor > $semver->getMinor()) {
            return true;
        }

        if ($this->major == $semver->getMajor()
            && $this->minor == $semver->getMinor()
            && $this->patch > $semver->getPatch()) {
            return true;
        }

        return false;
    }

    /**
     * Alias for $this->greaterThan()
     */
    public function gt(SemVer $semver)
    {
        return $this->greaterThan($semver);
    }

    /**
     * Check if this SemVer version object is less than another
     *
     * @param  SemVer $semver An instance of SemVer/SemVer
     *
     * @return bool           True if this SemVer object version is less than
     *                        the comparing object, otherwise false
     */
    public function lessThan(SemVer $semver)
    {
        if ($this->major < $semver->getMajor()) return true;

        if ($this->major == $semver->getMajor()
            && $this->minor < $semver->getMinor()) {
            return true;
        }

        if ($this->major == $semver->getMajor()
            && $this->minor == $semver->getMinor()
            && $this->patch < $semver->getPatch()) {
            return true;
        }

        return false;
    }

    /**
     * Alias for $this->lessThan()
     */
    public function lt(SemVer $semver)
    {
        return $this->lessThan($semver);
    }

    /**
     * Check if this SemVer version object is equal to than another
     *
     * @param  SemVer $semver An instance of SemVer/SemVer
     *
     * @return bool           True if this SemVer object version is equal to the
     *                        comparing object, otherwise false
     */
    public function equalTo(SemVer $semver)
    {
        return $this == $semver;
    }

    /**
     * Alias for $this->equalTo()
     */
    public function eq(SemVer $semver)
    {
        return $this->equalTo($semver);
    }
}

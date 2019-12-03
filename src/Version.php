<?php

namespace PHLAK\SemVer;

use PHLAK\SemVer\Exceptions\InvalidVersionException;
use PHLAK\SemVer\Traits\Comparable;
use PHLAK\SemVer\Traits\Incrementable;

class Version
{
    use Comparable;
    use Incrementable;

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
     * Magic get method; provides access to version properties.
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
        $version .= isset($this->preRelease) ? '-' . $this->preRelease : '';
        $version .= isset($this->build) ? '+' . $this->build : '';

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
        $semverRegex = '/^v?(\d+)\.(\d+)\.(\d+)(?:-([0-9A-Za-z-.]+))?(?:\+([0-9A-Za-z-.]+)?)?$/';

        if (! preg_match($semverRegex, $version, $matches)) {
            throw new InvalidVersionException('Invalid Semantic Version string provided');
        }

        $this->major = (int) $matches[1];
        $this->minor = (int) $matches[2];
        $this->patch = (int) $matches[3];
        $this->preRelease = $matches[4] ?? null;
        $this->build = $matches[5] ?? null;

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
        $this->setMinor(0);

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
        $this->setPatch(0);

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
        $this->setPreRelease(null);
        $this->setBuild(null);

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
     * Get the version string prefixed with a custom string.
     *
     * @param string $prefix String to prepend to the version string
     *                       (default: 'v')
     *
     * @return string Prefixed version string
     */
    public function prefix(string $prefix = 'v') : string
    {
        return $prefix . (string) $this;
    }
}

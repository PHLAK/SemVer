<?php

namespace PHLAK\SemVer;

use JsonSerializable;
use PHLAK\SemVer\Exceptions\InvalidVersionException;
use PHLAK\SemVer\Traits\Comparable;
use PHLAK\SemVer\Traits\Incrementable;

/**
 * @property int $major Major release number
 * @property int $minor Minor release number
 * @property int $patch Patch release number
 * @property string|null $preRelease Pre-release value
 * @property string|null $build Build release value
 */
class Version implements JsonSerializable
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
     *
     * @throws \PHLAK\SemVer\Exceptions\InvalidVersionException
     */
    final public function __construct(string $version = '0.1.0')
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
    public function __toString(): string
    {
        $version = implode('.', [$this->major, $this->minor, $this->patch]);

        if (! empty($this->preRelease)) {
            $version .= '-' . $this->preRelease;
        }

        if (! empty($this->build)) {
            $version .= '+' . $this->build;
        }

        return $version;
    }

    /**
     * Attempt to parse an incomplete version string.
     *
     * Examples: 'v1', 'v1.2', 'v1-beta.4', 'v1.3+007'
     *
     * @param string $version Version string
     *
     * @throws \PHLAK\SemVer\Exceptions\InvalidVersionException
     *
     * @return static This Version object
     */
    public static function parse(string $version): static
    {
        $semverRegex = '/^v?(?<major>\d+)(?:\.(?<minor>\d+)(?:\.(?<patch>\d+))?)?(?:-(?<pre_release>[0-9A-Za-z-.]+))?(?:\+(?<build>[0-9A-Za-z-.]+)?)?$/';

        if (! preg_match($semverRegex, $version, $matches, PREG_UNMATCHED_AS_NULL)) {
            throw new InvalidVersionException('Invalid semantic version string provided');
        }

        $version = sprintf('%s.%s.%s', $matches['major'], $matches['minor'] ?? 0, $matches['patch'] ?? 0);

        if (! empty($matches['pre_release'])) {
            $version .= '-' . $matches['pre_release'];
        }

        if (! empty($matches['build'])) {
            $version .= '+' . $matches['build'];
        }

        return new static($version);
    }

    /** Serialize version to JSON. */
    public function jsonSerialize(): mixed
    {
        return (string) $this;
    }

    /**
     * Set (override) the entire version value.
     *
     * @param string $version Version string
     *
     * @throws \PHLAK\SemVer\Exceptions\InvalidVersionException
     *
     * @return static This Version object
     */
    public function setVersion(string $version): static
    {
        $semverRegex = '/^v?(?<major>\d+)\.(?<minor>\d+)\.(?<patch>\d+)(?:-(?<pre_release>[0-9A-Za-z-.]+))?(?:\+(?<build>[0-9A-Za-z-.]+)?)?$/';

        if (! preg_match($semverRegex, $version, $matches, PREG_UNMATCHED_AS_NULL)) {
            throw new InvalidVersionException('Invalid semantic version string provided');
        }

        $this->major = (int) $matches['major'];
        $this->minor = (int) $matches['minor'];
        $this->patch = (int) $matches['patch'];
        $this->preRelease = $matches['pre_release'] ?? null;
        $this->build = $matches['build'] ?? null;

        return $this;
    }

    /**
     * Set the major version to a custom value.
     *
     * @param int $value Positive integer value
     *
     * @return static This Version object
     */
    public function setMajor(int $value): static
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
     * @return static This Version object
     */
    public function setMinor(int $value): static
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
     * @return static This Version object
     */
    public function setPatch(int $value): static
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
     * @return static This Version object
     */
    public function setPreRelease($value): static
    {
        $this->preRelease = $value;

        return $this;
    }

    /**
     * Set the build string to a custom value.
     *
     * @param string|null $value A new build value
     *
     * @return static This Version object
     */
    public function setBuild($value): static
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
    public function prefix(string $prefix = 'v'): string
    {
        return $prefix . (string) $this;
    }

    /**
     * Determine if the version is a pre-release.
     *
     * @return bool Returns true if the version is a pre-release, false otherwise
     */
    public function isPreRelease(): bool
    {
        return ! empty($this->preRelease);
    }

    /**
     * Determine if the version has a build string.
     *
     * @return bool Returns true if the version has a build string, false otherwise
     */
    public function hasBuild(): bool
    {
        return ! empty($this->build);
    }
}

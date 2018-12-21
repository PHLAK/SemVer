<?php

namespace PHLAK\SemVer;

use function function_exists;
use function shell_exec;
use PHLAK\SemVer\Exceptions\GitTagException;
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

    /** @var string Build release value */
    protected $build;

    /**
     * Class constructor, runs on object creation.
     *
     * @param string $version Version string
     */
    public function __construct($version = '0.1.0')
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
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic toString method; allows object interaction as if it were a string.
     *
     * @param string $prefix Prefix the version string with a custom string
     *                       (default: 'v')
     *
     * @return string Current version string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Set (override) the entire version value.
     *
     * @param string $version Version string  bool $tagGit
     *
     * @return Version This Version object
     */
    public function setVersion($version, bool $tagGit = false)
    {
        $semverRegex = '/^v?(\d+)\.(\d+)\.(\d+)(?:-([0-9A-Z-.]+))?(?:\+([0-9A-Z-.]+)?)?$/i';

        if (!preg_match($semverRegex, $version, $matches)) {
            throw new InvalidVersionException('Invalid Semantic Version string provided');
        }

        $this->major = (int)$matches[1];
        $this->minor = (int)$matches[2];
        $this->patch = (int)$matches[3];
        $this->preRelease = @$matches[4] ?: null;
        $this->build = @$matches[5] ?: null;

        $tagGit ? $this->tagGit() : null;

        return $this;
    }

    /**
     * Increment the major version value by one.
     *
     * @param bool $tagGit
     *
     * @return Version This Version object
     */
    public function incrementMajor(bool $tagGit = false)
    {
        $this->setMajor($this->major + 1);

        $tagGit ?? $this->tagGit();

        return $this;
    }

    /**
     * Set the major version to a custom value.
     *
     * @param int $value Positive integer value  bool $tagGit
     *
     * @return Version This Version object
     */
    public function setMajor($value, bool $tagGit = false)
    {
        $this->major = $value;
        $this->minor = 0;
        $this->patch = 0;
        $this->preRelease = null;

        $tagGit ?? $this->tagGit();

        return $this;
    }

    /**
     * Increment the minor version value by one.
     *
     * @param bool $tagGit
     *
     * @return Version This Version object
     */
    public function incrementMinor(bool $tagGit = false)
    {
        $this->setMinor($this->minor + 1);

        $tagGit ?? $this->tagGit();

        return $this;
    }

    /**
     * Set the minor version to a custom value.
     *
     * @param int $value Positive integer value  bool $tagGit
     *
     * @return Version This Version object
     */
    public function setMinor($value, bool $tagGit = false)
    {
        $this->minor = $value;
        $this->patch = 0;
        $this->preRelease = null;

        $tagGit ?? $this->tagGit();

        return $this;
    }

    /**
     * Increment the patch version value by one.
     *
     * @param bool $tagGit
     *
     * @return Version This Version object
     */
    public function incrementPatch(bool $tagGit = false)
    {
        $this->setPatch($this->patch + 1);

        $tagGit ?? $this->tagGit();

        return $this;
    }

    /**
     * Set the patch version to a custom value.
     *
     * @param int $value Positive integer value  bool $tagGit
     *
     * @return Version This Version object
     */
    public function setPatch($value, bool $tagGit = false)
    {
        $this->patch = $value;
        $this->preRelease = null;

        $tagGit ?? $this->tagGit();

        return $this;
    }

    /**
     * Set the pre-release string to a custom value.
     *
     * @param string $value A new pre-release value  bool $tagGit
     *
     * @return Version This Version object
     */
    public function setPreRelease($value, bool $tagGit = false)
    {
        $this->preRelease = $value;

        $tagGit ?? $this->tagGit();

        return $this;
    }

    /**
     * Set the build string to a custom value.
     *
     * @param string $value A new build value  bool $tagGit
     *
     * @return Version This Version object
     */
    public function setBuild($value, bool $tagGit = false)
    {
        $this->build = $value;

        $tagGit ?? $this->tagGit();

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
    public function gt(Version $version)
    {
        if ($this->major > $version->major) {
            return true;
        }

        if ($this->major == $version->major
            && $this->minor > $version->minor
        ) {
            return true;
        }

        if ($this->major == $version->major
            && $this->minor == $version->minor
            && $this->patch > $version->patch
        ) {
            return true;
        }

        // TODO: Check pre-release tag

        return false;
    }

    /**
     * Check if this Version object is less than another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is less than the comparing
     *              object, otherwise false
     */
    public function lt(Version $version)
    {
        if ($this->major < $version->major) {
            return true;
        }

        if ($this->major == $version->major
            && $this->minor < $version->minor
        ) {
            return true;
        }

        if ($this->major == $version->major
            && $this->minor == $version->minor
            && $this->patch < $version->patch
        ) {
            return true;
        }

        // TODO: Check pre-release tag

        return false;
    }

    /**
     * Check if this Version object is equal to than another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is equal to the comparing
     *              object, otherwise false
     */
    public function eq(Version $version)
    {
        return $this == $version;
    }

    /**
     * Check if this Version object is not equal to another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is not equal to the comparing
     *              object, otherwise false
     */
    public function neq(Version $version)
    {
        return $this != $version;
    }

    /**
     * Check if this Version object is greater than or equal to another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is greater than or equal to the
     *              comparing object, otherwise false
     */
    public function gte(Version $version)
    {
        return $this->gt($version) || $this->eq($version);
    }

    /**
     * Check if this Version object is less than or equal to another.
     *
     * @param Version $version An instance of SemVer/Version
     *
     * @return bool True if this Version object is less than or equal to the
     *              comparing object, otherwise false
     */
    public function lte(Version $version)
    {
        return $this->lt($version) || $this->eq($version);
    }

    /**
     * Get the version string prefixed with a custom string.
     *
     * @param string $prefix String to prepend to the version string
     *                       (default: 'v')
     *
     * @return string Prefixed version string
     */
    public function prefix($prefix = 'v')
    {
        return $prefix . $this->toString();
    }

    /**
     * Tag the git branch with the current prefixed version
     *
     * @return bool
     * @throws GitTagException
     */
    protected function tagGit()
    {
        if (function_exists('shell_exec')) {
            if (!is_null(shell_exec('git tag ' . $this->prefix()))) {
                throw new GitTagException('Failed to set tag for current Git Branch');
            }
            return true;
        }
        throw new GitTagException('Unable to set Git Tag as shell_exec is disabled');
    }

    /**
     * Get the current version value as a string.
     *
     * @return string Current version string
     */
    private function toString()
    {
        $version = implode('.', [$this->major, $this->minor, $this->patch]);
        $version .= isset($this->preRelease) ? '-' . $this->preRelease : null;
        $version .= isset($this->build) ? '+' . $this->build : null;

        return $version;
    }


}

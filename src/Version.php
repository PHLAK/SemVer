<?php

namespace PHLAK\SemVer;

use Exception;
use PHLAK\SemVer\Exceptions\InvalidVersionException;
use Cz\Git\GitRepository;

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

    /** @var git repo instance */
    protected $repo;

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
    public function setVersion($version)
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

        return $this;
    }

    /**
     * Increment the major version value by one.
     *
     * @param bool $tagGit
     *
     * @return Version This Version object
     */
    public function incrementMajor()
    {
        $this->setMajor($this->major + 1);

        return $this;
    }

    /**
     * Set the major version to a custom value.
     *
     * @param int $value Positive integer value  bool $tagGit
     *
     * @return Version This Version object
     */
    public function setMajor($value)
    {
        $this->major = $value;
        $this->minor = 0;
        $this->patch = 0;
        $this->preRelease = null;

        return $this;
    }

    /**
     * Increment the minor version value by one.
     *
     * @param bool $tagGit
     *
     * @return Version This Version object
     */
    public function incrementMinor()
    {
        $this->setMinor($this->minor + 1);

        return $this;
    }

    /**
     * Set the minor version to a custom value.
     *
     * @param int $value Positive integer value  bool $tagGit
     *
     * @return Version This Version object
     */
    public function setMinor($value)
    {
        $this->minor = $value;
        $this->patch = 0;
        $this->preRelease = null;

        return $this;
    }

    /**
     * Increment the patch version value by one.
     *
     * @param bool $tagGit
     *
     * @return Version This Version object
     */
    public function incrementPatch()
    {
        $this->setPatch($this->patch + 1);

        return $this;
    }

    /**
     * Set the patch version to a custom value.
     *
     * @param int $value Positive integer value  bool $tagGit
     *
     * @return Version This Version object
     */
    public function setPatch($value)
    {
        $this->patch = $value;
        $this->preRelease = null;

        return $this;
    }

    /**
     * Set the pre-release string to a custom value.
     *
     * @param string $value A new pre-release value  bool $tagGit
     *
     * @return Version This Version object
     */
    public function setPreRelease($value)
    {
        $this->preRelease = $value;

        return $this;
    }

    /**
     * Set the build string to a custom value.
     *
     * @param string $value A new build value  bool $tagGit
     *
     * @return Version This Version object
     */
    public function setBuild($value)
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
     * Tag Git repo with version
     *
     * @author Joshua Young (jny986)
     * @param $gitPath
     * @param bool $prefix
     * @return bool
     * @throws \Cz\Git\GitException
     */
    public function gitTag($gitPath = false, $prefix = false)
    {
        $gitPath ?: $gitPath = __DIR__ . '/../../../../..';
        if (!isset($this->repo) || empty($this->repo)) {
            $this->initGitRepo($gitPath);
        }
        $version = $this->prefix($prefix ?: '');
        if (!in_array($version, $this->gitTags($gitPath))) {
            $result = $this->repo->createTag($version);
            if ($result instanceof Exception) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get all tags for the Git repo
     *
     * @author Joshua Young (jny986)
     * @param bool $gitPath
     * @return bool
     * @throws \Cz\Git\GitException
     */
    public function gitTags($gitPath = false)
    {
        if (!isset($this->repo) || empty($this->repo)) {
            $this->initGitRepo($gitPath);
        }
        $result = $this->repo->getTags();
        if ($result instanceof Exception) {
            return false;
        }
        return $result;
    }

    /**
     * Remove Git tag from repository
     *
     * @author Joshua Young (jny986)
     * @param bool $gitPath
     * @param bool $prefix
     * @return bool
     * @throws \Cz\Git\GitException
     */
    public function gitTagRemove($gitPath = false, $prefix = false)
    {
        if (!isset($this->repo) || empty($this->repo)) {
            $this->initGitRepo($gitPath);
        }
        $result = $this->repo->removeTag($this->prefix($prefix ?: ''));
        if ($result instanceof Exception) {
            return false;
        }
        return true;
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

    /**
     * Initiate Git repo Instance
     *
     * @author Joshua Young (jny986)
     * @param $gitPath
     * @return bool
     * @throws \Cz\Git\GitException
     */
    protected function initGitRepo($gitPath)
    {
        $gitPath ?: $gitPath = __DIR__ . '/../../../../..';
        $repo = new GitRepository($gitPath);
        if ($repo instanceof Exception) {
            return false;
        }
        $this->repo = $repo;
        return true;
    }


}

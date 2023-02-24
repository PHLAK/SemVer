<?php

namespace PHLAK\SemVer\Traits;

/**
 * Trait for incrementing a version object.
 */
trait Incrementable
{
    /**
     * Increment the major version value by one.
     *
     * @return self This Version object
     */
    public function incrementMajor(): self
    {
        $this->setMajor($this->major + 1);

        return $this;
    }

    /**
     * Increment the minor version value by one.
     *
     * @return self This Version object
     */
    public function incrementMinor(): self
    {
        $this->setMinor($this->minor + 1);

        return $this;
    }

    /**
     * Increment the patch version value by one.
     *
     * @return self This Version object
     */
    public function incrementPatch(): self
    {
        $this->setPatch($this->patch + 1);

        return $this;
    }

    /**
     * Increment the pre-release version value by one.
     *
     * @return self This Version object
     */
    public function incrementPreRelease(): self
    {
        if (empty($this->preRelease)) {
            $this->incrementPatch();
            $this->setPreRelease('1');

            return $this;
        }

        $identifiers = explode('.', $this->preRelease);

        if (! is_numeric(end($identifiers))) {
            $this->setPreRelease(implode('.', [$this->preRelease, '1']));

            return $this;
        }

        $identifiers[] = (string)((int)array_pop($identifiers) + 1);

        $this->setPreRelease(implode('.', $identifiers));

        return $this;
    }
}

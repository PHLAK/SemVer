<?php

namespace PHLAK\SemVer\Traits;

trait Incrementable
{
    /**
     * Increment the major version value by one.
     *
     * @return static This Version object
     */
    public function incrementMajor(): static
    {
        $this->setMajor($this->major + 1);

        return $this;
    }

    /**
     * Increment the minor version value by one.
     *
     * @return static This Version object
     */
    public function incrementMinor(): static
    {
        $this->setMinor($this->minor + 1);

        return $this;
    }

    /**
     * Increment the patch version value by one.
     *
     * @return static This Version object
     */
    public function incrementPatch(): static
    {
        $this->setPatch($this->patch + 1);

        return $this;
    }

    /**
     * Increment the pre-release version value by one.
     *
     * @return static This Version object
     */
    public function incrementPreRelease(): static
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

        array_push($identifiers, (string) ((int) array_pop($identifiers) + 1));

        $this->setPreRelease(implode('.', $identifiers));

        return $this;
    }
}

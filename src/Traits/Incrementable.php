<?php

namespace PHLAK\SemVer\Traits;

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
}

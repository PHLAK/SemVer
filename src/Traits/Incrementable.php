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

    /**
     * Increment the pre-release version value by one.
     *
     * @return self This Version object
     */
    public function incrementPreRelease(): self
    {
        if (is_null($this->preRelease)) {
            $this->incrementPatch();
            $this->setPreRelease('0');

            return $this;
        }

        $preRelease = explode('.', $this->preRelease);
        $lastElement = trim(end($preRelease));

        if (count($preRelease) > 1 && is_numeric($lastElement)) {
            $number = intval($lastElement) + 1;
            array_pop($preRelease);
            $preid = implode('.', $preRelease);
            $this->setPreRelease($preid . '.' . $number);
        } elseif (count($preRelease) === 1 && is_numeric($lastElement)) {
            $number = intval($lastElement) + 1;
            $this->setPreRelease($number);
        } else {
            $preid = implode('.', $preRelease);
            $this->setPreRelease($preid . '.0');
        }

        return $this;
    }
}

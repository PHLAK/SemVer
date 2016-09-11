<?php

class SemVerTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_be_initialized()
    {
        $this->assertInstanceOf('SemVer\SemVer', new SemVer\SemVer);
    }

    public function test_it_can_set_and_retrieve_a_version()
    {
        $semver = (new SemVer\SemVer)->setVersion('v1.3.37');

        $this->assertEquals('v1.3.37', $semver->getVersion());
    }

    public function test_it_can_increment_major()
    {
        $semver = (new SemVer\SemVer('v1.3.37'))->incrementMajor();

        $this->assertEquals('v2.0.0', $semver->getVersion());
    }

    public function test_it_can_set_major()
    {
        $semver = (new SemVer\SemVer('v1.3.37'))->setMajor(7);

        $this->assertEquals('v7.0.0', $semver->getVersion());
    }

    public function test_it_can_increment_minor()
    {
        $semver = (new SemVer\SemVer('v1.3.37'))->incrementMinor();

        $this->assertEquals('v1.4.0', $semver->getVersion());
    }

    public function test_it_can_set_minor()
    {
        $semver = (new SemVer\SemVer('v1.3.37'))->setMinor(5);

        $this->assertEquals('v1.5.0', $semver->getVersion());
    }

    public function test_it_can_increment_patch()
    {
        $semver = (new SemVer\SemVer('v1.3.37'))->incrementPatch();

        $this->assertEquals('v1.3.38', $semver->getVersion());
    }

    public function test_it_can_set_patch()
    {
        $semver = (new SemVer\SemVer('v1.3.37'))->setPatch(12);

        $this->assertEquals('v1.3.12', $semver->getVersion());
    }

    public function test_it_can_set_pre_release()
    {
        $semver = (new SemVer\SemVer)->setPreRelease('alpha.5');

        $this->assertEquals('v0.1.0-alpha.5', $semver->getVersion());
    }

    public function test_it_can_unset_pre_release()
    {
        $semver = (new SemVer\SemVer('v1.3.37-alpha.5'))->setPreRelease(null);

        $this->assertNull($semver->getPreRelease());
    }

    public function test_it_can_set_build()
    {
        $semver = (new SemVer\SemVer)->setBuild('007');

        $this->assertEquals('v0.1.0+007', $semver->getVersion());
    }

    public function test_it_can_unset_build()
    {
        $semver = (new SemVer\SemVer('v1.3.37+007'))->setBuild(null);

        $this->assertNull($semver->getBuild());
    }

    public function test_it_can_get_individual_properties()
    {
        $semver = new SemVer\SemVer('v1.3.37-alpha.5+007');

        $this->assertEquals(1, $semver->getMajor());
        $this->assertEquals(3, $semver->getMinor());
        $this->assertEquals(37, $semver->getPatch());
        $this->assertEquals('alpha.5', $semver->getPreRelease());
        $this->assertEquals('007', $semver->getBuild());
    }

    public function test_it_can_be_greater_than_another_semver_object()
    {
        $semver = new SemVer\SemVer('v1.3.37');

        $this->assertTrue($semver->gt(new SemVer\SemVer('v1.2.3')));
        $this->assertFalse($semver->gt(new SemVer\SemVer('v2.3.4')));
        $this->assertFalse($semver->gt(new SemVer\SemVer('v1.3.37')));
    }

    public function test_it_can_be_less_than_another_semver_object()
    {
        $semver = new SemVer\SemVer('v1.3.37');

        $this->assertTrue($semver->lt(new SemVer\SemVer('v2.3.4')));
        $this->assertFalse($semver->lt(new SemVer\SemVer('v1.2.3')));
        $this->assertFalse($semver->lt(new SemVer\SemVer('v1.3.37')));
    }

    public function test_it_can_be_equal_to_another_semver_object()
    {
        $semver = new SemVer\SemVer('v1.3.37');

        $this->assertTrue($semver->eq(new SemVer\SemVer('v1.3.37')));
        $this->assertFalse($semver->eq(new SemVer\SemVer('v1.2.3')));
    }

    public function test_it_can_be_not_equal_to_another_semver_object()
    {
        $semver = new SemVer\SemVer('v1.3.37');

        $this->assertTrue($semver->neq(new SemVer\SemVer('v1.2.3')));
        $this->assertFalse($semver->neq(new SemVer\SemVer('v1.3.37')));
    }

    public function test_it_can_be_greater_than_or_equal_to_another_semver_object()
    {
        $semver = new SemVer\SemVer('v.1.3.37');

        $this->assertTrue($semver->gte(new SemVer\SemVer('v1.2.3')));
        $this->assertTrue($semver->gte(new SemVer\SemVer('v1.3.37')));
        $this->assertFalse($semver->gte(new SemVer\SemVer('v2.3.4')));
    }

    public function test_it_can_be_less_than_or_equal_to_another_semver_object()
    {
        $semver = new SemVer\SemVer('v.1.3.37');

        $this->assertTrue($semver->lte(new SemVer\SemVer('v2.3.4')));
        $this->assertTrue($semver->lte(new SemVer\SemVer('v1.3.37')));
        $this->assertFalse($semver->lte(new SemVer\SemVer('v1.2.3')));
    }
}

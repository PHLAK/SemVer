<?php

class SemVerTest extends PHPUnit_Framework_TestCase {

    /** @test */
    public function it_can_be_initialized() {
        $this->assertInstanceOf('SemVer\SemVer', new SemVer\SemVer);
    }

    /** @test */
    public function it_can_set_and_retrieve_a_version() {
        $semver = (new SemVer\SemVer)->setVersion('v1.3.37');
        $this->assertEquals('v1.3.37', $semver->getVersion());
    }

    /** @test */
    public function it_can_increment_major() {
        $semver = (new SemVer\SemVer('v1.3.37'))->incrementMajor();
        $this->assertEquals('v2.0.0', $semver->getVersion());
    }

    /** @test */
    public function it_can_set_major() {
        $semver = (new SemVer\SemVer('v1.3.37'))->setMajor(7);
        $this->assertEquals('v7.0.0', $semver->getVersion());
    }

    /** @test */
    public function it_can_increment_minor() {
        $semver = (new SemVer\SemVer('v1.3.37'))->incrementMinor();
        $this->assertEquals('v1.4.0', $semver->getVersion());
    }

    /** @test */
    public function it_can_set_minor() {
        $semver = (new SemVer\SemVer('v1.3.37'))->setMinor(5);
        $this->assertEquals('v1.5.0', $semver->getVersion());
    }

    /** @test */
    public function it_can_increment_patch() {
        $semver = (new SemVer\SemVer('v1.3.37'))->incrementPatch();
        $this->assertEquals('v1.3.38', $semver->getVersion());
    }

    /** @test */
    public function it_can_set_patch() {
        $semver = (new SemVer\SemVer('v1.3.37'))->setPatch(12);
        $this->assertEquals('v1.3.12', $semver->getVersion());
    }

    /** @test */
    public function it_can_set_pre_release() {
        $semver = (new SemVer\SemVer)->setPreRelease('alpha.5');
        $this->assertEquals('v0.1.0-alpha.5', $semver->getVersion());
    }

    /** @test */
    public function it_can_unset_pre_release() {
        $semver = (new SemVer\SemVer('v1.3.37-alpha.5'))->setPreRelease(null);
        $this->assertNull($semver->getPreRelease());
    }

    /** @test */
    public function it_can_set_build() {
        $semver = (new SemVer\SemVer)->setBuild('007');
        $this->assertEquals('v0.1.0+007', $semver->getVersion());
    }

    /** @test */
    public function it_can_unset_build() {
        $semver = (new SemVer\SemVer('v1.3.37+007'))->setBuild(null);
        $this->assertNull($semver->getBuild());
    }

    /** @test */
    public function it_can_get_individual_properties() {
        $semver = new SemVer\SemVer('v1.3.37-alpha.5+007');
        $this->assertEquals(1, $semver->getMajor());
        $this->assertEquals(3, $semver->getMinor());
        $this->assertEquals(37, $semver->getPatch());
        $this->assertEquals('alpha.5', $semver->getPreRelease());
        $this->assertEquals('007', $semver->getBuild());
    }

}

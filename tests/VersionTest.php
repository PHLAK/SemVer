<?php

namespace PHLAK\SemVer\Tests;

use PHLAK\SemVer;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function setUp()
    {
        $this->version = new SemVer\Version('v1.3.37');
    }

    public function test_it_can_be_initialized()
    {
        $this->assertInstanceOf(SemVer\Version::class, $this->version);
    }

    /**
     * @expectedException PHLAK\SemVer\Exceptions\InvalidVersionException
     */
    public function test_it_throws_a_runtime_exception_for_an_invalid_version()
    {
        new SemVer\Version('not.a.version');
    }

    public function test_it_can_set_and_retrieve_a_version()
    {
        $this->version->setVersion('v2.4.48');

        $this->assertEquals('2.4.48', (string) $this->version);
    }

    public function test_it_can_return_a_prefixed_version_string()
    {
        $this->assertEquals('v1.3.37', $this->version->prefix());
        $this->assertEquals('x1.3.37', $this->version->prefix('x'));
    }

    public function test_it_can_be_cast_to_a_string()
    {
        $this->assertEquals('1.3.37', (string) $this->version);
    }

    public function test_it_can_get_individual_properties()
    {
        $version = new SemVer\Version('v1.3.37-alpha.5+007');

        $this->assertEquals(1, $version->major);
        $this->assertEquals(3, $version->minor);
        $this->assertEquals(37, $version->patch);
        $this->assertEquals('alpha.5', $version->preRelease);
        $this->assertEquals('007', $version->build);
    }

    public function test_it_can_increment_major()
    {
        $this->version->incrementMajor();

        $this->assertEquals('2.0.0', (string) $this->version);
    }

    public function test_it_can_set_major()
    {
        $this->version->setMajor(7);

        $this->assertEquals('7.0.0', (string) $this->version);
    }

    public function test_it_can_increment_minor()
    {
        $this->version->incrementMinor();

        $this->assertEquals('1.4.0', (string) $this->version);
    }

    public function test_it_can_set_minor()
    {
        $this->version->setMinor(5);

        $this->assertEquals('1.5.0', (string) $this->version);
    }

    public function test_it_can_increment_patch()
    {
        $this->version->incrementPatch();

        $this->assertEquals('1.3.38', (string) $this->version);
    }

    public function test_it_can_set_patch()
    {
        $this->version->setPatch(12);

        $this->assertEquals('1.3.12', (string) $this->version);
    }

    public function test_it_can_set_pre_release()
    {
        $this->version->setPreRelease('alpha.5');

        $this->assertEquals('1.3.37-alpha.5', (string) $this->version);
    }

    public function test_it_can_unset_pre_release()
    {
        $version = (new SemVer\Version('v1.3.37-alpha.5'))->setPreRelease(null);

        $this->assertNull($version->preRelease);
    }

    public function test_it_can_set_build()
    {
        $this->version->setBuild('007');

        $this->assertEquals('1.3.37+007', (string) $this->version);
    }

    public function test_it_can_unset_build()
    {
        $version = (new SemVer\Version('v1.3.37+007'))->setBuild(null);

        $this->assertNull($version->build);
    }

    public function test_it_can_be_greater_than_another_semver_object()
    {
        $this->assertTrue($this->version->gt(new SemVer\Version('v1.3.36')));
        $this->assertFalse($this->version->gt(new SemVer\Version('v1.3.38')));
        $this->assertFalse($this->version->gt(new SemVer\Version('v1.3.37')));
        $this->assertTrue($this->version->gt(new SemVer\Version('v1.3.35')));
    }

    public function test_it_can_be_greater_than_another_major_semver_object()
    {
        $major = $this->version->setMajor(1.3);
        $semver = new SemVer\Version('v1.3.38');
        $semverMajor = $semver->setMajor(1.2);

        $this->assertTrue($major->gt($semverMajor));
    }

    public function test_it_can_be_less_than_another_semver_object()
    {
        $this->assertTrue($this->version->lt(new SemVer\Version('v1.3.38')));
        $this->assertTrue($this->version->lt(new SemVer\Version('v1.4.0')));
        $this->assertFalse($this->version->lt(new SemVer\Version('v1.3.36')));
        $this->assertFalse($this->version->lt(new SemVer\Version('v1.3.37')));
    }

    public function test_it_can_be_equal_to_another_semver_object()
    {
        $this->assertTrue($this->version->eq(new SemVer\Version('v1.3.37')));
        $this->assertFalse($this->version->eq(new SemVer\Version('v1.2.3')));
    }

    public function test_it_can_be_not_equal_to_another_semver_object()
    {
        $this->assertTrue($this->version->neq(new SemVer\Version('v1.2.3')));
        $this->assertFalse($this->version->neq(new SemVer\Version('v1.3.37')));
    }

    public function test_it_can_be_greater_than_or_equal_to_another_semver_object()
    {
        $this->assertTrue($this->version->gte(new SemVer\Version('v1.2.3')));
        $this->assertTrue($this->version->gte(new SemVer\Version('v1.3.37')));
        $this->assertFalse($this->version->gte(new SemVer\Version('v2.3.4')));
    }

    public function test_it_can_be_less_than_or_equal_to_another_semver_object()
    {
        $this->assertTrue($this->version->lte(new SemVer\Version('v2.3.4')));
        $this->assertTrue($this->version->lte(new SemVer\Version('v1.3.37')));
        $this->assertFalse($this->version->lte(new SemVer\Version('v1.2.3')));
    }
}

<?php

namespace PHLAK\SemVer\Tests;

use JsonSerializable;
use PHLAK\SemVer;
use PHLAK\SemVer\Exceptions\InvalidVersionException;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class VersionTest extends TestCase
{
    /** @return array<array<int, mixed>> */
    public static function pre_release_comparison_provider(): array
    {
        return [
            ['v1.3.37', 'v1.3.37-alpha'],
            ['v1.3.37', 'v1.3.37-alpha.5+007'],
            ['v1.3.0', 'v1.3.0-beta'],
            ['v1.0.0', 'v1.0.0-rc1'],
            // Test cases from http://semver.org
            ['1.0.0', '1.0.0-rc.1'],
            ['1.0.0-rc.1', '1.0.0-beta.11'],
            ['1.0.0-beta.11', '1.0.0-beta.2'],
            ['1.0.0-beta.2', '1.0.0-beta'],
            ['1.0.0-beta', '1.0.0-alpha.beta'],
            ['1.0.0-alpha.beta', '1.0.0-alpha.1'],
            ['1.0.0-alpha.1', '1.0.0-alpha'],
        ];
    }

    public function test_it_can_be_initialized(): void
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertInstanceOf(SemVer\Version::class, $version);
    }

    public function test_it_can_be_initialized_with_the_helper_function(): void
    {
        $version = semver('v1.3.37');

        $this->assertInstanceOf(SemVer\Version::class, $version);
    }

    public function test_it_throws_a_runtime_exception_for_an_invalid_version(): void
    {
        $this->expectException(InvalidVersionException::class);

        new SemVer\Version('not.a.version');
    }

    public function test_it_can_parse_an_incomplete_version_string(): void
    {
        $this->assertEquals('1.0.0', (string) SemVer\Version::parse('v1'));
        $this->assertEquals('1.3.0', (string) SemVer\Version::parse('v1.3'));
        $this->assertEquals('1.3.37', (string) SemVer\Version::parse('v1.3.37'));
        $this->assertEquals('1.3.37-alpha.5', (string) SemVer\Version::parse('v1.3.37-alpha.5'));
        $this->assertEquals('1.3.37+007', (string) SemVer\Version::parse('v1.3.37+007'));
    }

    public function test_it_throws_a_runtime_exception_when_parsing_an_invalid_version(): void
    {
        $this->expectException(InvalidVersionException::class);

        SemVer\Version::parse('not.a.version');
    }

    public function test_it_can_set_and_retrieve_a_version(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setVersion('v2.4.48');

        $this->assertEquals('2.4.48', (string) $version);
    }

    public function test_it_can_return_a_prefixed_version_string(): void
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertEquals('v1.3.37', $version->prefix());
        $this->assertEquals('x1.3.37', $version->prefix('x'));
    }

    public function test_it_can_be_cast_to_a_string(): void
    {
        $this->assertEquals('1.3.37', (string) new SemVer\Version('v1.3.37'));
        $this->assertEquals('1.3.37-alpha.5', (string) new SemVer\Version('v1.3.37-alpha.5'));
        $this->assertEquals('1.3.37+007', (string) new SemVer\Version('v1.3.37+007'));
        $this->assertEquals('1.3.37-alpha.5+007', (string) new SemVer\Version('v1.3.37-alpha.5+007'));
    }

    public function test_it_can_get_individual_properties(): void
    {
        $version = new SemVer\Version('v1.3.37-alpha.5+007');

        $this->assertEquals(1, $version->major);
        $this->assertEquals(3, $version->minor);
        $this->assertEquals(37, $version->patch);
        $this->assertEquals('alpha.5', $version->preRelease);
        $this->assertEquals('007', $version->build);
    }

    public function test_it_can_increment_major(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->incrementMajor();

        $this->assertEquals('2.0.0', (string) $version);
    }

    public function test_it_can_set_major(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setMajor(7);

        $this->assertEquals('7.0.0', (string) $version);
    }

    public function test_it_can_increment_minor(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->incrementMinor();

        $this->assertEquals('1.4.0', (string) $version);
    }

    public function test_it_can_set_minor(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setMinor(5);

        $this->assertEquals('1.5.0', (string) $version);
    }

    public function test_it_can_increment_patch(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->incrementPatch();

        $this->assertEquals('1.3.38', (string) $version);
    }

    public function test_it_can_increment_prerelease(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setPreRelease('alpha.5');
        $version->incrementPreRelease();

        $this->assertEquals('1.3.37-alpha.6', (string) $version);
    }

    public function test_it_can_increment_prerelease_without_number(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setPreRelease('alpha');
        $version->incrementPreRelease();

        $this->assertEquals('1.3.37-alpha.1', (string) $version);
    }

    public function test_it_can_increment_prerelease_without_prefix_for_prerelease(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setPreRelease('5');
        $version->incrementPreRelease();

        $this->assertEquals('1.3.37-6', (string) $version);
    }

    public function test_it_can_increment_prerelease_containing_multiple_dots(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setPreRelease('alpha.a.b.5');
        $version->incrementPreRelease();

        $this->assertEquals('1.3.37-alpha.a.b.6', (string) $version);
    }

    public function test_it_can_increment_prerelease_containing_multiple_dots_and_without_number(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setPreRelease('alpha.a.b');
        $version->incrementPreRelease();

        $this->assertEquals('1.3.37-alpha.a.b.1', (string) $version);
    }

    public function test_it_can_increment_prerelease_and_patch_when_prerelease_is_null(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->incrementPreRelease();

        $this->assertEquals('1.3.38-1', (string) $version);
    }

    public function test_it_can_set_patch(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setPatch(12);

        $this->assertEquals('1.3.12', (string) $version);
    }

    public function test_it_can_set_pre_release(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setPreRelease('alpha.5');

        $this->assertEquals('1.3.37-alpha.5', (string) $version);
    }

    public function test_it_can_unset_pre_release(): void
    {
        $version = (new SemVer\Version('v1.3.37-alpha.5'))->setPreRelease(null);

        $this->assertNull($version->preRelease);
    }

    public function test_it_can_set_build(): void
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setBuild('007');

        $this->assertEquals('1.3.37+007', (string) $version);
    }

    public function test_it_can_unset_build(): void
    {
        $version = (new SemVer\Version('v1.3.37+007'))->setBuild(null);

        $this->assertNull($version->build);
    }

    public function test_it_can_be_greater_than_another_semver_object(): void
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->gt(new SemVer\Version('v0.5.0')));
        $this->assertTrue($version->gt(new SemVer\Version('v1.3.36')));
        $this->assertFalse($version->gt(new SemVer\Version('v1.3.38')));
        $this->assertFalse($version->gt(new SemVer\Version('v1.3.37')));
    }

    public function test_it_can_be_less_than_another_semver_object(): void
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->lt(new SemVer\Version('v1.3.38')));
        $this->assertTrue($version->lt(new SemVer\Version('v1.4.0')));
        $this->assertFalse($version->lt(new SemVer\Version('v1.3.36')));
        $this->assertFalse($version->lt(new SemVer\Version('v1.3.37')));
    }

    public function test_it_can_be_equal_to_another_semver_object(): void
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->eq(new SemVer\Version('v1.3.37')));
        $this->assertFalse($version->eq(new SemVer\Version('v1.2.3')));
    }

    public function test_it_can_be_not_equal_to_another_semver_object(): void
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->neq(new SemVer\Version('v1.2.3')));
        $this->assertFalse($version->neq(new SemVer\Version('v1.3.37')));
    }

    public function test_it_can_be_greater_than_or_equal_to_another_semver_object(): void
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->gte(new SemVer\Version('v1.2.3')));
        $this->assertTrue($version->gte(new SemVer\Version('v1.3.37')));
        $this->assertFalse($version->gte(new SemVer\Version('v2.3.4')));
    }

    public function test_it_can_be_less_than_or_equal_to_another_semver_object(): void
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->lte(new SemVer\Version('v2.3.4')));
        $this->assertTrue($version->lte(new SemVer\Version('v1.3.37')));
        $this->assertFalse($version->lte(new SemVer\Version('v1.2.3')));
    }

    public function test_setting_the_major_version_resets_appropriate_properties(): void
    {
        $version = new SemVer\Version('v1.3.37-alpha.5+007');
        $version->setMajor(2);

        $this->assertEquals(2, $version->major);
        $this->assertEquals(0, $version->minor);
        $this->assertEquals(0, $version->patch);
        $this->assertNull($version->preRelease);
        $this->assertNull($version->build);
    }

    public function test_setting_the_minor_version_resets_appropriate_properties(): void
    {
        $version = new SemVer\Version('v1.3.37-alpha.5+007');
        $version->setMinor(4);

        $this->assertEquals(1, $version->major);
        $this->assertEquals(4, $version->minor);
        $this->assertEquals(0, $version->patch);
        $this->assertNull($version->preRelease);
        $this->assertNull($version->build);
    }

    public function test_setting_the_patch_version_resets_appropriate_properties(): void
    {
        $version = new SemVer\Version('v1.3.37-alpha.5+007');
        $version->setPatch(38);

        $this->assertEquals(1, $version->major);
        $this->assertEquals(3, $version->minor);
        $this->assertEquals(38, $version->patch);
        $this->assertNull($version->preRelease);
        $this->assertNull($version->build);
    }

    public function test_it_compares_pre_release_tags(): void
    {
        $alpha = new SemVer\Version('v1.3.37-alpha');
        $beta = new SemVer\Version('v1.3.37-beta');

        $this->assertTrue($alpha->lt($beta));
        $this->assertFalse($alpha->gt($beta));
        $this->assertTrue($alpha->lte($beta));
        $this->assertFalse($alpha->gte($beta));
        $this->assertFalse($alpha->eq($beta));
    }

    public function test_it_ignores_the_build_version_when_comparing_versions(): void
    {
        $oldBuild = new SemVer\Version('v1.3.37-alpha.5+006');
        $newBuild = new SemVer\Version('v1.3.37-alpha.5+007');

        $this->assertTrue($oldBuild->eq($newBuild));
        $this->assertFalse($oldBuild->neq($newBuild));
        $this->assertFalse($oldBuild->gt($newBuild));
        $this->assertFalse($oldBuild->lt($newBuild));
        $this->assertTrue($oldBuild->gte($newBuild));
        $this->assertTrue($oldBuild->lte($newBuild));

        $this->assertTrue((new SemVer\Version('v1.3.37'))->eq(new SemVer\Version('v1.3.37+007')));
    }

    /** @dataProvider pre_release_comparison_provider */
    public function test_it_compares_pre_release_tags_vs_release(string $release, string $prerelease): void
    {
        $release = new SemVer\Version($release);
        $prerelease = new SemVer\Version($prerelease);

        $this->assertFalse($release->eq($prerelease));
        $this->assertFalse($release->lt($prerelease));
        $this->assertFalse($release->lte($prerelease));
        $this->assertTrue($release->gt($prerelease));
        $this->assertTrue($release->gte($prerelease));

        $this->assertFalse($prerelease->gt($release));
        $this->assertFalse($prerelease->eq($release));
        $this->assertFalse($prerelease->gte($release));
        $this->assertTrue($prerelease->lt($release));
        $this->assertTrue($prerelease->lte($release));
    }

    public function test_it_can_compare_two_versions(): void
    {
        $version1 = new SemVer\Version('v1.3.37');
        $version2 = new SemVer\Version('v3.2.1');

        // Major Comparisons
        $this->assertEquals(-1, SemVer\Version::compare(new SemVer\Version('v1.2.3'), new SemVer\Version('v3.2.1')));
        $this->assertEquals(0, SemVer\Version::compare(new SemVer\Version('v1.2.3'), new SemVer\Version('v1.2.3')));
        $this->assertEquals(1, SemVer\Version::compare(new SemVer\Version('v3.2.1'), new SemVer\Version('v1.2.3')));

        // Minor Comparisons
        $this->assertEquals(-1, SemVer\Version::compare(new SemVer\Version('v0.1.2'), new SemVer\Version('v0.2.1')));
        $this->assertEquals(0, SemVer\Version::compare(new SemVer\Version('v0.1.2'), new SemVer\Version('v0.1.2')));
        $this->assertEquals(1, SemVer\Version::compare(new SemVer\Version('v0.2.1'), new SemVer\Version('v0.1.2')));

        // Patch Comparisons
        $this->assertEquals(-1, SemVer\Version::compare(new SemVer\Version('v1.0.1'), new SemVer\Version('v1.0.2')));
        $this->assertEquals(0, SemVer\Version::compare(new SemVer\Version('v1.0.0'), new SemVer\Version('v1.0.0')));
        $this->assertEquals(1, SemVer\Version::compare(new SemVer\Version('v1.0.2'), new SemVer\Version('v1.0.1')));
    }

    public function test_it_is_correctly_json_serializable(): void
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertInstanceOf(JsonSerializable::class, $version);
        $this->assertEquals('1.3.37', $version->jsonSerialize());
    }
}

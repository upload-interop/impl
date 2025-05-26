<?php
declare(strict_types=1);

namespace UploadInterop\Impl;

use UploadInterop\Interface\UploadFilesParser;
use UploadInterop\Interface\UploadStruct;
use UploadInterop\Interface\UploadStructFactory;

class UploadFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return UploadFactory
     */
    public function newUploadFactory() : UploadStructFactory
    {
        return new UploadFactory();
    }

    public function testUploadsArrayFilesFromItem() : void
    {
        $tmp_name = tempnam(sys_get_temp_dir(), 'upload-interop');

        $actual = $this
            ->newUploadFactory()
            ->parseUploadFiles(
                files: [
                    'photo' => [
                        'tmp_name' => $tmp_name,
                        'error' => 0,
                        'name' => 'calvin.jpg',
                        'full_path' => '/Users/watterson/Pictures/calvin.jpg',
                        'size' => 12345,
                        'type' => 'image/jpeg',
                    ],
                ]);

        $this->assertCount(1, $actual);
        $this->assertInstanceOf(UploadStruct::class, $actual['photo']);
        $this->assertSame('calvin.jpg', $actual['photo']->name);

        /** @var array{profile: array{details: array{photo: UploadStruct}}} $actual */
        $actual = $this
            ->newUploadFactory()
            ->parseUploadFiles(
                files: [
                    'profile' => [
                        'details' => [
                            'photo' => [
                                'tmp_name' => "{$tmp_name}-0",
                                'error' => 0,
                                'name' => 'hobbes.jpg',
                                'full_path' => '/Users/watterson/Pictures/hobbes.jpg',
                                'size' => 23456,
                                'type' => 'image/jpeg',
                            ],
                        ],
                    ],
                ]);

        $this->assertCount(1, $actual);
        $this->assertSame('hobbes.jpg', $actual['profile']['details']['photo']->name);
    }

    public function testUploadsArrayFilesFromGroup() : void
    {
        $tmp_name = tempnam(sys_get_temp_dir(), 'upload-interop');

        /** @var array{team: array{people: array{photos: UploadStruct[]}}} $actual */
        $actual = $this
            ->newUploadFactory()
            ->parseUploadFiles(
                files: [
                    'team' => [
                        'people' => [
                            'photos' => [
                                'tmp_name' => [
                                    0 => "{$tmp_name}-0",
                                    1 => "{$tmp_name}-1",
                                    2 => "{$tmp_name}-2",
                                ],
                                'error' => [
                                    0 => 0,
                                    1 => 0,
                                    2 => 0,
                                ],
                                'name' => [
                                    0 => 'calvin.jpg',
                                    1 => 'hobbes.jpg',
                                    2 => 'susie.jpg',
                                ],
                                'full_path' => [
                                    0 => '/Users/watterson/Pictures/calvin.jpg',
                                    1 => '/Users/watterson/Pictures/hobbes.jpg',
                                    2 => '/Users/watterson/Pictures/susie.jpg',
                                ],
                                'size' => [
                                    0 => 12345,
                                    1 => 23456,
                                    2 => 45678,
                                ],
                                'type' => [
                                    0 => 'image/jpeg',
                                    1 => 'image/jpeg',
                                    2 => 'image/jpeg',
                                ],
                            ],
                        ],
                    ]
                ]);

        $this->assertCount(3, $actual['team']['people']['photos']);
        $this->assertSame('calvin.jpg', $actual['team']['people']['photos'][0]->name);
        $this->assertSame('hobbes.jpg', $actual['team']['people']['photos'][1]->name);
        $this->assertSame('susie.jpg', $actual['team']['people']['photos'][2]->name);
    }

    public function testUploadsArrayFilesFromGroupNested(): void
    {
        $tmp_name = tempnam(sys_get_temp_dir(), 'upload-interop');

        /** @var array{alter-egos: array<int, array{photo: UploadStruct[]}>} $actual */
        $actual = $this->newUploadFactory()
            ->parseUploadFiles(
                files: [
                    'alter-egos' => [
                        'tmp_name' => [
                            0 => [
                                'photo' => [
                                    0 => "{$tmp_name}-0",
                                    1 => "{$tmp_name}-1",
                                    2 => "{$tmp_name}-2",
                                ],
                            ],
                        ],
                        'error' => [
                            0 => [
                                'photo' => [
                                    0 => 0,
                                    1 => 0,
                                    2 => 0,
                                ],
                            ],
                        ],
                        'name' => [
                            0 => [
                                'photo' => [
                                    0 => 'spaceman-spiff.jpg',
                                    1 => 'stupendous-man.jpg',
                                    2 => 'captain-napalm.jpg',
                                ],
                            ],
                        ],
                        'full_path' => [
                            0 => [
                                'photo' => [
                                    0 => '/Users/watterson/Pictures/calvin.jpg',
                                    1 => '/Users/watterson/Pictures/hobbes.jpg',
                                    2 => '/Users/watterson/Pictures/susie.jpg',
                                ],
                            ],
                        ],
                        'size' => [
                            0 => [
                                'photo' => [
                                    0 => 12345,
                                    1 => 23456,
                                    2 => 34567,
                                ],
                            ],
                        ],
                        'type' => [
                            0 => [
                                'photo' => [
                                    0 => 'image/jpeg',
                                    1 => 'image/jpeg',
                                    2 => 'image/jpeg',
                                ],
                            ],
                        ],
                    ],
                ]);

        $this->assertCount(3, $actual['alter-egos'][0]['photo']);
        $this->assertSame('spaceman-spiff.jpg', $actual['alter-egos'][0]['photo'][0]->name);
        $this->assertSame('stupendous-man.jpg', $actual['alter-egos'][0]['photo'][1]->name);
        $this->assertSame('captain-napalm.jpg', $actual['alter-egos'][0]['photo'][2]->name);
    }
}

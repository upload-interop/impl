<?php
declare(strict_types=1);

namespace UploadInterop\Impl;

use UploadInterop\Impl\UploadFactoryMethods;
use UploadInterop\Interface\UploadStruct;
use UploadInterop\Interface\UploadStructFactory;
use UploadInterop\Interface\UploadFilesParser;

class UploadFactory implements UploadFilesParser, UploadStructFactory
{
    use UploadFactoryMethods;

    /**
     * @inheritdoc
     * @return Upload
     */
    public function newUpload(
        string $tmp_name,
        int $error,
        ?string $name = null,
        ?string $full_path = null,
        ?string $type = null,
        ?int $size = null,
    ) : UploadStruct
    {
        return new Upload(
            $tmp_name,
            $error,
            $name,
            $full_path,
            $type,
            $size,
        );
    }
}

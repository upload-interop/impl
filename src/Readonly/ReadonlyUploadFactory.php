<?php
declare(strict_types=1);

namespace UploadInterop\Impl\Readonly;

use UploadInterop\Impl\UploadFactoryMethods;
use UploadInterop\Interface\UploadStruct;
use UploadInterop\Interface\UploadStructFactory;
use UploadInterop\Interface\UploadFilesParser;

class ReadonlyUploadFactory implements UploadFilesParser, UploadStructFactory
{
    use UploadFactoryMethods;

    /**
     * @inheritdoc
     * @return ReadonlyUpload
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
        return new ReadonlyUpload(
            $tmp_name,
            $error,
            $name,
            $full_path,
            $type,
            $size,
        );
    }
}

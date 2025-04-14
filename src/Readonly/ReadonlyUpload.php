<?php
declare(strict_types=1);

namespace UploadInterop\Impl\Readonly;

use UploadInterop\Interface\UploadStruct;

class ReadonlyUpload implements UploadStruct
{
    public function __construct(
        readonly public string $tmp_name,
        readonly public int $error,
        readonly public ?string $name,
        readonly public ?string $full_path,
        readonly public ?string $type,
        readonly public ?int $size,
    ) {
    }
}

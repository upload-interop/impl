<?php
declare(strict_types=1);

namespace UploadInterop\Impl;

use UploadInterop\Interface\UploadStruct;
use UploadInterop\Interface\UploadStructFactory;
use UploadInterop\Interface\UploadTypeAliases;

/**
 * @phpstan-import-type files_array from UploadTypeAliases
 * @phpstan-import-type files_group_array from UploadTypeAliases
 * @phpstan-import-type files_item_array from UploadTypeAliases
 * @phpstan-import-type uploads_array from UploadTypeAliases
 */
class UploadFactory implements UploadStructFactory
{
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

    /**
     * @param files_array|files_group_array|files_item_array $files
     * @return uploads_array
     */
    public function newUploadsFromFiles(array $files) : array
    {
        $uploads = [];

        /** @var files_item_array|files_group_array|files_array $value */
        foreach ($files as $field => $value) {
            $uploads[$field] = $this->parseFiles($value);
        }

        /** @var uploads_array $uploads */
        return $uploads;
    }

    /**
     * @param files_array|files_group_array|files_item_array $value
     * @return UploadStruct|uploads_array
     */
    protected function parseFiles(array $value) : UploadStruct|array
    {
        if (is_string($value['tmp_name'] ?? null)) {
            /** @var files_item_array $value */
            return $this->newUpload(...$value);
        }

        if (is_array($value['tmp_name'] ?? null)) {
            /** @var files_group_array $value */
            return $this->parseFilesGroup($value);
        }

        /** @var files_array $value */
        return $this->newUploadsFromFiles($value);
    }

    /**
     * @param files_group_array $group
     * @return uploads_array
     */
    protected function parseFilesGroup(array $group) : array
    {
        $files = [];

        foreach ($group['tmp_name'] as $key => $ignore) {
            $files[$key]['tmp_name'] = $group['tmp_name'][$key];
            $files[$key]['error'] = $group['error'][$key];
            $files[$key]['name'] = $group['name'][$key] ?? null;
            $files[$key]['full_path'] = $group['full_path'][$key] ?? null;
            $files[$key]['type'] = $group['type'][$key] ?? null;
            $files[$key]['size'] = $group['size'][$key] ?? null;
        }

        /** @var files_array $files */
        return $this->newUploadsFromFiles($files);
    }
}

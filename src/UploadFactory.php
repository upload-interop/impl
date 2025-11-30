<?php
declare(strict_types=1);

namespace UploadInterop\Impl;

use UploadInterop\Interface\UploadStruct;
use UploadInterop\Interface\UploadStructFactory;
use UploadInterop\Interface\UploadTypeAliases;

/**
 * @phpstan-import-type upload_files_array from UploadTypeAliases
 * @phpstan-import-type upload_files_group_array from UploadTypeAliases
 * @phpstan-import-type upload_files_item_array from UploadTypeAliases
 * @phpstan-import-type upload_structs_array from UploadTypeAliases
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
     * @inheritdoc
     */
    public function newUploadsFromFiles(array $files) : array
    {
        $uploads = [];

        /** @var upload_files_item_array|upload_files_group_array|upload_files_array $value */
        foreach ($files as $field => $value) {
            $uploads[$field] = $this->parseFilesValue($value);
        }

        /** @var upload_structs_array $uploads */
        return $uploads;
    }

    /**
     * @param upload_files_array|upload_files_group_array|upload_files_item_array $value
     * @return UploadStruct|upload_structs_array
     */
    protected function parseFilesValue(array $value) : UploadStruct|array
    {
        if (is_string($value['tmp_name'] ?? null)) {
            /** @var upload_files_item_array $value */
            return $this->newUpload(...$value);
        }

        if (is_array($value['tmp_name'] ?? null)) {
            /** @var upload_files_group_array $value */
            return $this->parseFilesGroup($value);
        }

        /** @var upload_files_array $value */
        return $this->newUploadsFromFiles($value);
    }

    /**
     * @param upload_files_group_array $group
     * @return upload_structs_array
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

        /** @var upload_files_array $files */
        return $this->newUploadsFromFiles($files);
    }
}

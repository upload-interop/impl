<?php
declare(strict_types=1);

namespace UploadInterop\Impl;

use UploadInterop\Interface\UploadStruct;
use UploadInterop\Interface\UploadTypeAliases;

/**
 * @phpstan-import-type files_array from UploadTypeAliases
 * @phpstan-import-type files_group_array from UploadTypeAliases
 * @phpstan-import-type files_item_array from UploadTypeAliases
 * @phpstan-import-type uploads_array from UploadTypeAliases
 */
trait UploadFactoryMethods
{
    abstract public function newUpload(
        string $tmp_name,
        int $error,
        ?string $name = null,
        ?string $full_path = null,
        ?string $type = null,
        ?int $size = null,
    ) : UploadStruct;

    /**
     * @param files_array|files_group_array|files_item_array $files
     * @return uploads_array
     */
    public function parseUploadFiles(array $files) : array
    {
        $uploads = [];

        /** @var files_item_array|files_group_array|files_array $value */
        foreach ($files as $field => $value) {
            $uploads[$field] = $this->parseUploadFilesValue($value);
        }

        /** @var uploads_array $uploads */
        return $uploads;
    }

    /**
     * @param files_array|files_group_array|files_item_array $value
     * @return UploadStruct|uploads_array
     */
    protected function parseUploadFilesValue(array $value) : UploadStruct|array
    {
        if (is_string($value['tmp_name'] ?? null)) {
            /** @var files_item_array $value */
            return $this->newUpload(...$value);
        }

        if (is_array($value['tmp_name'] ?? null)) {
            /** @var files_group_array $value */
            return $this->parseUploadFilesGroup($value);
        }

        /** @var files_array $value */
        return $this->parseUploadFiles($value);
    }

    /**
     * @param files_group_array $group
     * @return uploads_array
     */
    protected function parseUploadFilesGroup(array $group) : array
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
        return $this->parseUploadFiles($files);
    }
}

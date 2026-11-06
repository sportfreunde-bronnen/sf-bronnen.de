<?php

/**
 * Class DownloadsPage
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class DownloadsPage extends Page
{
    /**
     * Converts the files selected by selector to file collection
     */
    public function convertFilesToCollection($selectedFiles)
    {
        $filenames = $selectedFiles->split(',');
        if(count($filenames) < 2) {
            $filenames = array_pad($filenames, 2, '');
        }
        return call_user_func_array(array($this->files(), 'find'), $filenames);
    }

    /**
     * Converts the file size (in bytes) into a human readable string, e.g. "340 KB" or "1,25 MB"
     *
     * @param int $fileSizeInBytes
     * @return string
     */
    public function convertFileSize($fileSizeInBytes = 0)
    {
        if ($fileSizeInBytes < 1024 * 1024) {
            return sprintf('%s KB', number_format(max(1, round($fileSizeInBytes / 1024)), 0, ',', '.'));
        }

        return sprintf('%s MB', number_format($fileSizeInBytes / 1024 / 1024, 2, ',', '.'));
    }

    /**
     * Returns the icon (Font Awesome) and the type modifier (css) which represent the file type of the given extension
     *
     * @param string $extension
     * @return array{icon: string, type: string}
     */
    public function fileTypeInfo($extension = '')
    {
        $types = [
            'pdf' => ['pdf', 'fa-file-pdf-o'],
            'doc' => ['word', 'fa-file-word-o'],
            'docx' => ['word', 'fa-file-word-o'],
            'odt' => ['word', 'fa-file-word-o'],
            'rtf' => ['word', 'fa-file-word-o'],
            'xls' => ['excel', 'fa-file-excel-o'],
            'xlsx' => ['excel', 'fa-file-excel-o'],
            'ods' => ['excel', 'fa-file-excel-o'],
            'csv' => ['excel', 'fa-file-excel-o'],
            'ppt' => ['powerpoint', 'fa-file-powerpoint-o'],
            'pptx' => ['powerpoint', 'fa-file-powerpoint-o'],
            'odp' => ['powerpoint', 'fa-file-powerpoint-o'],
            'zip' => ['archive', 'fa-file-archive-o'],
            'rar' => ['archive', 'fa-file-archive-o'],
            '7z' => ['archive', 'fa-file-archive-o'],
            'jpg' => ['image', 'fa-file-image-o'],
            'jpeg' => ['image', 'fa-file-image-o'],
            'png' => ['image', 'fa-file-image-o'],
            'gif' => ['image', 'fa-file-image-o'],
            'webp' => ['image', 'fa-file-image-o'],
            'svg' => ['image', 'fa-file-image-o'],
            'txt' => ['text', 'fa-file-text-o'],
        ];

        [$type, $icon] = $types[strtolower((string)$extension)] ?? ['file', 'fa-file-o'];

        return ['icon' => $icon, 'type' => $type];
    }
}
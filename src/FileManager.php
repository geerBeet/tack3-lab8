<?php

namespace Ilya\Lab8;

class FileManager
{
    private string $filename;
    private string $data;
    public function __construct(string $filename, string $data)
    {
        $this->filename = $filename;
        $this->data = $data;
    }

    public function readFile(string $filename): ?string
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            error_log("Ошибка: файл '$filename' не существует или недоступен для чтения.");
            return null;
        }

        $contents = file_get_contents($filename);
        return $contents;
    }

    public function writeFile(string $filename, string $data): bool
    {
        $dir = dirname($filename);
        if (!is_dir($dir) && !mkdir($dir, 0777, true)) {
            error_log("Ошибка: Не удалось создать директорию '$dir'");
            return false;
        }

        $bytesWritten = file_put_contents($filename, $data, FILE_APPEND);
        if ($bytesWritten === false) {
            error_log("Ошибка: Не удалось записать данные в файл '$filename'");
            return false;
        }
        return true;
    }

    public function deleteFile(string $filename): bool
    {
        if (!file_exists($filename)) {
            return false;
        }
        if (!is_writable($filename)) {
            error_log("Ошибка: Нет прав на удаление файла '$filename'");
            return false;
        }

        try {
            $result = unlink($filename);
            return $result;
        } catch (Exception $e) {
            error_log("Ошибка при удалении файла '$filename': " . $e->getMessage());
            return false;
        }
    }
    public function getFilesList(string $directory): array
    {
        if (!is_dir($directory)) {
            throw new \RuntimeException("Ошибка: указанный путь '$directory' не является директорией.");
        }
        if (!is_readable($directory)) {
            throw new \RuntimeException("Ошибка: нет прав на чтение директории '$directory'");
        }

        $files = scandir($directory);
        $files = array_diff($files, ['.', '..']);
        return $files;
    }
}

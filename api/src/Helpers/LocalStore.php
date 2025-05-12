<?php

namespace Src\Helpers;

class LocalStore
{
    protected static string $basePath = __DIR__ . '/../../store/local/';

    /**
     * Salva o conteúdo em um arquivo com o nome especificado.
     *
     * @param string $filename Nome do arquivo (sem path)
     * @param string $content Conteúdo a ser salvo
     * @return bool Sucesso ou falha na escrita
     */
    public static function save(string $filename, string $content): bool
    {
        self::ensureDirectoryExists();

        $path = self::$basePath . self::sanitizeFilename($filename);
        return file_put_contents($path, $content) !== false;
    }

    /**
     * Lê o conteúdo de um arquivo.
     *
     * @param string $filename Nome do arquivo
     * @return string|null Conteúdo ou null se não existir
     */
    public static function read(string $filename): ?string
    {
        $path = self::$basePath . self::sanitizeFilename($filename);
        return file_exists($path) ? file_get_contents($path) : null;
    }

    /**
     * Verifica se o arquivo existe.
     *
     * @param string $filename
     * @return bool
     */
    public static function exists(string $filename): bool
    {
        $path = self::$basePath . self::sanitizeFilename($filename);
        return file_exists($path);
    }

    /**
     * Remove o arquivo.
     *
     * @param string $filename
     * @return bool
     */
    public static function delete(string $filename): bool
    {
        $path = self::$basePath . self::sanitizeFilename($filename);
        return file_exists($path) ? unlink($path) : false;
    }

    /**
     * Garante que o diretório base exista.
     */
    protected static function ensureDirectoryExists(): void
    {
        if (!is_dir(self::$basePath)) {
            mkdir(self::$basePath, 0777, true);
        }
    }

    /**
     * Sanitiza o nome do arquivo para evitar diretórios ou nomes maliciosos.
     *
     * @param string $filename
     * @return string
     */
    protected static function sanitizeFilename(string $filename): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $filename);
    }
}

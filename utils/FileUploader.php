<?php

require_once __DIR__ . '/../includes/config.php';

class FileUploader {
    private array $allowedMimeTypes;
    private array $allowedExtensions;
    private int $maxSize;
    private string $uploadDir;
    private array $errors = [];

    public function __construct(array $allowedMimeTypes, array $allowedExtensions, string $uploadDir, int $maxSize = MAX_FILE_SIZE) {
        $this->allowedMimeTypes = $allowedMimeTypes;
        $this->allowedExtensions = $allowedExtensions;
        $this->maxSize = $maxSize;
        $this->uploadDir = $uploadDir;
    }

    /**
     * Validate and upload a file
     * @param array 
     * @return array|false
     */
    public function upload(array $file) {
        $this->errors = [];


        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = 'File upload error: ' . $this->getUploadErrorMessage($file['error'] ?? UPLOAD_ERR_NO_FILE);
            return false;
        }

        
        if ($file['size'] > $this->maxSize) {
            $this->errors[] = 'File size exceeds maximum allowed size of ' . ($this->maxSize / 1024 / 1024) . 'MB';
            return false;
        }

        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            $this->errors[] = 'Invalid file type. Allowed types: ' . implode(', ', $this->allowedExtensions);
            return false;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            $this->errors[] = 'Invalid file extension. Allowed: ' . implode(', ', $this->allowedExtensions);
            return false;
        }

   
        $safeName = $this->generateSafeFilename($file['name'], $extension);
        $targetPath = $this->uploadDir . '/' . $safeName;

        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

      
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            $this->errors[] = 'Failed to save uploaded file';
            return false;
        }

       
        $fileType = 'image';
        if ($mimeType === 'application/pdf') {
            $fileType = 'pdf';
        }

        $relativePath = str_replace(BASE_PATH, '', $targetPath);
        $relativePath = str_replace('\\', '/', $relativePath);
        if (strpos($relativePath, '/') !== 0) {
            $relativePath = '/' . $relativePath;
        }

        return [
            'path' => $relativePath,
            'type' => $fileType
        ];
    }

    /**
     * Delete a file
     * @param string 
     * @return bool
     */
    public static function deleteFile(string $filePath): bool {
 
        if (strpos($filePath, '/') === 0) {
            $absolutePath = BASE_PATH . $filePath;
        } elseif (strpos($filePath, '\\') === 0 || preg_match('/^[A-Z]:/', $filePath)) {
        
            $absolutePath = $filePath;
        } else {
          
            $absolutePath = BASE_PATH . '/' . $filePath;
        }

        
        $absolutePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $absolutePath);

        if (file_exists($absolutePath) && is_file($absolutePath)) {
            return @unlink($absolutePath);
        }
        return false;
    }

    /**
     * Get upload error messages
     * @return array
     */
    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * Generate a safe filename
     * @param string 
     * @param string 
     * @return string
     */
    private function generateSafeFilename(string $originalName, string $extension): string {
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        $name = substr($name, 0, 50); // Limit length
        $timestamp = time();
        $random = substr(md5(uniqid(rand(), true)), 0, 8);
        return $name . '_' . $timestamp . '_' . $random . '.' . $extension;
    }

    /**
     * Get human-readable upload error message
     * @param int 
     * @return string
     */
    private function getUploadErrorMessage(int $errorCode): string {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'File is too large';
            case UPLOAD_ERR_PARTIAL:
                return 'File was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }
}
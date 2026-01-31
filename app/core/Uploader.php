<?php


class Uploader
{
 
    private array $allowedImages = ['jpg', 'jpeg', 'png', 'webp'];

   
    private array $allowedPdfs = ['pdf'];

    
    private int $maxSize = 5 * 1024 * 1024;

  
    private string $publicPath;

    public function __construct()
    {
     
        $this->publicPath = dirname(__DIR__, 2) . '/public';
    }

    /**
     * Upload a file and save it in the correct folder based on type.
     *
     * @param array $file The file array from $_FILES['input_name']
     * @return string The relative path (e.g. "uploads/images/abc123.jpg") to store in DB
     * @throws Exception If the upload fails (with a clear error message)
     */
    public function upload(array $file): string
    {
       
        if (!isset($file['error'])) {
            throw new Exception('Invalid file upload.');
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception($this->getUploadErrorMessage($file['error']));
        }

       
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception('No file was uploaded.');
        }

        
        $originalName = $file['name'] ?? '';
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $allowedExtensions = array_merge($this->allowedImages, $this->allowedPdfs);
        if (!in_array($extension, $allowedExtensions)) {
            $allowed = implode(', ', $allowedExtensions);
            throw new Exception('File type not allowed. Allowed: ' . $allowed);
        }

        
        if ($file['size'] > $this->maxSize) {
            $maxMB = $this->maxSize / 1024 / 1024;
            throw new Exception('File is too large. Maximum size is ' . $maxMB . ' MB.');
        }

       
        if (in_array($extension, $this->allowedImages)) {
            $subfolder = 'images';
        } else {
            $subfolder = 'pdfs';
        }

        $uploadDir = $this->publicPath . '/uploads/' . $subfolder;

        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        
        $uniqueName = uniqid() . '.' . $extension;

        $targetPath = $uploadDir . '/' . $uniqueName;

        
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Failed to save the uploaded file.');
        }

        
        return 'uploads/' . $subfolder . '/' . $uniqueName;
    }

    /**
     * Convert PHP upload error codes into readable messages.
     *
     * @param int $code The error code from $_FILES['x']['error']
     * @return string A clear error message
     */
    private function getUploadErrorMessage(int $code): string
    {
        $messages = [
            UPLOAD_ERR_INI_SIZE   => 'File is too large (server limit).',
            UPLOAD_ERR_FORM_SIZE  => 'File is too large.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server temporary folder is missing.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION  => 'Upload was blocked by server.',
        ];
        return $messages[$code] ?? 'Unknown upload error.';
    }
}

<?php
//Google Drive is no longer used because it require a Shared Storage which is not free
namespace App\Services;

use Google\Client;
use Google\Service\Drive;

class GoogleDriveService
{
  private function getClient()
  {
    $client = new Client();
    $client->setAuthConfig(storage_path('app/google/gen-lang-client-0490281410-2cde3b947cb9.json'));
    $client->addScope(Drive::DRIVE);
    return $client;
  }

  public function upload($filePath, $fileName)
  {
    if (!file_exists($filePath)) {
      throw new \Exception("File not found at path: " . $filePath);
    }

    $client = $this->getClient();
    $service = new Drive($client);

    $folderId = config('filesystems.disks.google.folder_id');

    $fileMetadata = new Drive\DriveFile([
      'name' => $fileName,
      'parents' => [$folderId]
    ]);

    $file = $service->files->create(
      $fileMetadata,
      [
        'data' => file_get_contents($filePath),
        'mimeType' => mime_content_type($filePath),
        'uploadType' => 'multipart'
      ]
    );

    return $file;
  }
}

<?php
// cloudinary is no longer used, failed to implment it and i found out that it is not a good option

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CloudinaryService
{
  public function uploadPdf($path)
  {
    $upload = Cloudinary::upload(
      $path,
      [
        'resource_type' => 'raw',   // Required for PDFs
        'folder' => 'resumes'
      ]
    );

    return $upload->getSecurePath(); // you can save this in DB
  }
}

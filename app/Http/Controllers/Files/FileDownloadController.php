<?php

namespace App\Http\Controllers\Files;

use App\File;
use App\Sale;
use Illuminate\Http\Request;
use Chumper\Zipper\Zipper;
use App\Http\Controllers\Controller;

class FileDownloadController extends Controller
{
  protected $zipper;

  public function __concstruct(Zipper $zipper)
  {
    $this->zipper = $zipper;
  }

  public function show(File $file, Sale $sale)
  {
    if (!$file->visible()) {
      return abort(403);
    }

    if (!$file->matchesSale($sale)) {
      return abort(403);
    }

    $this->createZipForFileInPath($file, $path = $this->generateTemporaryPath($file));

    return response()
        ->download($path)
        ->deleteFileAfterSend(true);
  }

  protected function createZipForFileInPath(File $file, $path)
  {
      $this->zipper->make($path)->add($file->getUploadList())->close();
  }

  protected function generateTemporaryPath(File $file)
  {
      return public_path('tmp/' . str_slug($file->title) . '.zip');
  }
}

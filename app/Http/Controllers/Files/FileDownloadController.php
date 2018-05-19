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


  }
}

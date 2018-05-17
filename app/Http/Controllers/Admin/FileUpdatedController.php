<?php

namespace App\Http\Controllers\Admin;

use Mail;
use App\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Files\FileUpdatesApproved;
use App\Mail\Files\FileUpdatesRejected;

class FileUpdatedController extends Controller
{
    public function index()
    {
      $files = File::whereHas('approvals')->oldest()->get();

      return view('admin.files.updated.index', [
        'files' => $files
      ]);
    }

    public function update(File $file)
    {
      $file->mergeApprovalProperties();

      $file->approveAllUploads();

      $file->deleteAllApprovals();

      Mail::to($file->user)->send(new FileUpdatesApproved($file));

      return back()->withSuccess("{$file->title} has been approved");
    }

    public function destroy(File $file)
    {
      $file->deleteAllApprovals();

      $this->deleteUnapprovedUploads();

      Mail::to($file->user)->send(new FileUpdatesRejected($file));

      return back()->withSuccess("{$file->title} has been deleted");
    }
}

<?php

namespace App\Http\Controllers\Account;

use App\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\StoreFileRequest;
use App\Http\Requests\File\UpdateFileRequest;

class FileController extends Controller
{
    public function index()
    {
      $files = auth()->user()->files()->latest()->finished()->get();

      return view('account.files.index', compact('files'));
    }

    public function create(File $file)
    {
      if (!$file->exists) {
        $file = $this->createAndReturnSkeletonFile();

        return redirect()->route('account.files.create', $file);
      }

      $this->authorize('touch', $file);

      return view('account.files.create', [
            'file' => $file
        ]);
    }

    public function store(File $file, StoreFileRequest $request)
    {
      $this->authorize('touch', $file);

       $file->fill($request->only(['title', 'overview', 'overview_short', 'price']));
       $file->finished = true;
       $file->save();

       return redirect()->route('account.files.index')
            ->withSuccess('Thanks, submitted for review.');
     }

     public function edit(File $file)
     {
       return view('account.files.edit', [
         'file' => $file,
         'approval' => $file->approvals->first()
       ]);
     }

     public function update(File $file, UpdateFileRequest $request)
     {
       $this->authorize('touch', $file);

       $approvalProperties = $request->only(File::APPROVAL_PROPERTIES);

       if ($file->needsApproval($approvalProperties)) {
         $file->createApproval($approvalProperties);

         return back()->withSuccess('Thanks! We will review your changes soon.');
       }

       $file->update([
         'live' => $request->get('live', false),
         'price' => $request->get('price')
       ]);

       return back()->withSuccess('File updated');
     }

    protected function createAndReturnSkeletonFile()
    {
        return auth()->user()->files()->create([
            'title' => 'Untitled',
            'overview' => 'None',
            'overview_short' => 'None',
            'price' => 0,
            'finished' => false,
        ]);
    }
}

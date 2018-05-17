@component('files.partials._file', compact('file'))
  @slot('links')
    <div class="level">
      <div class="level-left">
        <p class="level-item">
          <a href="{{ route('admin.files.show', $file) }}">Preview Changes</a>
        </p>

        <p class="level-item">
          <a href="#" onclick="event.preventDefault(); document.getElementById('approve-{{ $file->id }}').submit();">Approve</a>
        </p>

        <form id="approve-{{ $file->id }}" action="{{ route('admin.files.updated.update', $file) }}"
          method="post" class="is-hidden">
          {{ csrf_field() }}
          {{ method_field('PATCH') }}
        </form>

        <p class="level-item">
          <a href="#" onclick="event.preventDefault(); document.getElementById('reject-{{ $file->id }}').submit();">Reject</a>
        </p>

        <form id="reject-{{ $file->id }}" action="{{ route('admin.files.updated.destroy', $file) }}"
          method="post" class="is-hidden">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
        </form>
      </div>
    </div>
  @endslot
@endcomponent

@extends('lecturer.main')
@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $proposal->title }}</h1>
    <h5>oleh <b>{{ $proposal->author->student->name }}</b></h5>
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-body">
                <b>Asbtrak</b> <br>
                {{ $proposal->abstract_indonesian }} <br><br>

                <b>Abstract</b> <br>
                {{ $proposal->abstract_english }} <br>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Log Revisi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('proposal.submitrevision', ['proposal_id' => $proposal->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <textarea class="form-control" name="message" rows="3" placeholder="Pesan Anda" required></textarea> <br>
                    <input type="file" name="file"> <br>
                    <button type="submit" class="btn btn-primary mt-3">Kirim</button>
                </form>
                <hr>
                @forelse ($revisions as $item)
                <div class="card shadow mb-3 {{ ($item->from_id == Auth::user()->id) ? 'border-left-primary' : 'ml-4' }}">
                    <div class="card-header">
                        @if ($item->user->role == 1)
                            <b>{{ $item->user->student->name }}</b> <br>
                        @elseif ($item->user->role == 2)
                            <b>{{ $item->user->lecturer->name }}</b> <br>
                        @endif
                        {{ date('D, d M Y H:i', strtotime($item->created_at)) }}
                    </div>
                    <div class="card-body">
                        {{ $item->message}} <br>
                        <a href="#" class="btn btn-sm btn-primary">File</a>
                    </div>
                </div>
                @empty
                    <span class="align-middle">Anda belum melakukan upload proposal</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

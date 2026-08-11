a@extends('admin.layouts.app')

@section('title', 'Kelola Ulasan')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-star me-2"></i>Kelola Ulasan</h5>
            <div class="text-muted small">Review dan testimoni dari pengunjung</div>
        </div>
    </div>
    <div class="card-body px-4 pb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 rounded-start-3">PENGUNJUNG</th>
                        <th class="border-0">CABIN</th>
                        <th class="border-0">RATING DAN ULASAN</th>
                        <th class="border-0">TANGGAL</th>
                        <th class="border-0 rounded-end-3 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ulasans as $u)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $u->user->name ?? 'Guest' }}</div>
                        </td>
                        <td>{{ $u->cabin->name_cabin ?? '-' }}</td>
                        <td>
                            <div class="text-warning mb-1">
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= ($u->rating ?? 0))
                                        <i class="bi bi-star-fill"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="small text-muted">{{ $u->komentar ?? '-' }}</div>
                        </td>
                        <td>{{ $u->created_at ? $u->created_at->format('d-m-y') : '-' }}</td>
                        <td class="text-center">
                            <form action="{{ route('admin.ulasan.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada ulasan dari pengunjung.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

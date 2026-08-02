@extends('admin_wahana.layouts.app')

@section('title', 'Kelola Wahana')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-flag-fill me-2 text-success"></i>Kelola Wahana</h4>
    <a href="{{ route('admin_wahana.kelola.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Wahana
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#d1fae5; color:#065f46;">
                    <tr>
                        <th class="ps-4" width="50">No</th>
                        <th>Foto</th>
                        <th>Nama Wahana</th>
                        <th>Durasi</th>
                        <th>Harga Tiket</th>
                        <th>Deskripsi</th>
                        <th class="pe-4" width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wahanas as $index => $wahana)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td>
                            @if($wahana->foto)
                                <img src="{{ asset('storage/' . $wahana->foto) }}" alt="Wahana"
                                    class="rounded-3 shadow-sm" style="width: 80px; height: 50px; object-fit: cover;">
                            @else
                                <span class="badge bg-secondary">No Photo</span>
                            @endif
                        </td>
                        <td><div class="fw-bold">{{ $wahana->nama }}</div></td>
                        <td><span class="badge bg-light text-dark border">{{ $wahana->durasi ?? '-' }}</span></td>
                        <td><div class="text-success fw-bold">Rp {{ number_format($wahana->harga, 0, ',', '.') }}</div></td>
                        <td>
                            <small class="text-muted" style="-webkit-line-clamp:2; display:-webkit-box; -webkit-box-orient:vertical; overflow:hidden;">
                                {{ $wahana->deskripsi ?? '-' }}
                            </small>
                        </td>
                        <td class="pe-4">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin_wahana.kelola.edit', $wahana->id) }}"
                                    class="btn btn-sm btn-info text-white rounded-circle" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin_wahana.kelola.destroy', $wahana->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus wahana ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger rounded-circle" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-flag fs-1 d-block mb-2"></i>
                            Belum ada data wahana.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Manage Gift Items')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gift Items</h1>
        <a href="{{ route('admin.items.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Item
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Gift Items</h6>
            <span class="badge bg-primary">{{ $items->count() }} items</span>
        </div>
        <div class="card-body">
            @if($items->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No gift items found</h5>
                    <p class="text-muted">Add your first gift item to get started.</p>
                    <a href="{{ route('admin.items.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add First Item
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50">ID</th>
                                <th width="80">Image</th>
                                <th>Name</th>
                                <th width="120">Price</th>
                                <th width="100">Status</th>
                                <th width="150">Created</th>
                                <th width="120" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td class="fw-bold">#{{ $item->id }}</td>
                                <td>
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" 
                                             alt="{{ $item->name }}" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-gift text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $item->name }}</div>
                                    @if($item->description)
                                        <small class="text-muted d-block mt-1" style="font-size: 0.85rem;">
                                            {{ Str::limit($item->description, 60) }}
                                        </small>
                                    @endif
                                </td>
                                <td class="fw-bold text-primary">${{ number_format($item->price, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $item->created_at->format('M d, Y') }}
                                    </small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.items.edit', $item) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger delete-item-btn"
                                                data-id="{{ $item->id }}"
                                                data-name="{{ $item->name }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete item: <strong id="itemName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Item</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    .img-thumbnail {
        border: 1px solid #dee2e6;
        padding: 2px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Delete confirmation modal
    document.querySelectorAll('.delete-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            const itemName = this.getAttribute('data-name');
            
            document.getElementById('itemName').textContent = itemName;
            document.getElementById('deleteForm').action = `/admin/items/${itemId}`;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush
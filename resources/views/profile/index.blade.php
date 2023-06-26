@extends('layouts.app')

@section('content')
    <h1>User List</h1>

    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->admin ? 'Administrator' : 'User' }}</td>
                <td>
                    @if (auth()->user()->admin && $user->id !== auth()->user()->id)
                        <form action="{{ route('users.updateRole', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <select name="admin">
                                <option value="0" {{ !$user->admin ? 'selected' : '' }}>User</option>
                                <option value="1" {{ $user->admin ? 'selected' : '' }}>Administrator</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Update Role</button>
                        </form>
                        <form action="{{ route('profile.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-user" data-user-name="{{ $user->name }}" data-delete-url="{{ route('profile.destroy', $user->id) }}">Delete User</button>

                        </form>
                    @else
                        You can't change your own role
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <span id="deleteUserName"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <form id="deleteUserForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const confirmDeleteModal = document.getElementById('confirmDeleteModal');
            const deleteForm = document.getElementById('deleteUserForm');

            const showModal = function(userName, deleteUrl) {
                // Set the user name in the confirmation modal
                document.getElementById('deleteUserName').textContent = userName;

                // Set the delete action URL in the form
                deleteForm.setAttribute('action', deleteUrl);

                // Show the confirmation modal
                $(confirmDeleteModal).modal('show');
            };

            const deleteButton = confirmDeleteModal.querySelector('.btn-danger');
            deleteButton.addEventListener('click', function() {
                deleteForm.submit();
            });

            // Attach click event listeners to delete buttons
            const deleteButtons = document.querySelectorAll('.delete-user');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    const userName = button.getAttribute('data-user-name');
                    const deleteUrl = button.getAttribute('data-delete-url');
                    showModal(userName, deleteUrl);
                });
            });
        });
    </script>


@endsection

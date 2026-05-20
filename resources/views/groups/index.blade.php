@extends('admin.layouts.admin_layout')

@section('pageTitle', 'Loan Groups Management')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">{{ __('common.nav.loan_groups') }}</a></li>
  <li class="breadcrumb-item active">{{ __('common.general.list') }}</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ __('common.nav.loan_groups') }}</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" id="addGroupBtn">
              <i class="fas fa-plus"></i> Add New Group
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="groupsTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Group ID</th>
                <th>Group Name</th>
                <th>Date Issued</th>
                <th>Members</th>
                <th>Added By</th>
                <th>Actions</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Group Modal -->
  <div class="modal fade" id="groupModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add New Group</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="groupForm">
          <div class="modal-body">
            <input type="hidden" id="group_id" name="group_id">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="group_name" class="form-label">Group Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="group_name" name="group_name" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="date_issue" class="form-label">Date Issued <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="date_issue" name="date_issue" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Group</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Members Modal -->
  <div class="modal fade" id="membersModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Group Members</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <button type="button" class="btn btn-success btn-sm" id="addMemberBtn">
              <i class="fas fa-plus"></i> Add Member
            </button>
          </div>
          <table id="membersTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Customer Name</th>
                <th>IC Number</th>
                <th>Phone</th>
                <th>Loan Amount</th>
                <th>Actions</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#groupsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('groups.data') }}',
        columns: [{
            data: 'group_id',
            name: 'group_id'
          },
          {
            data: 'group_name',
            name: 'group_name'
          },
          {
            data: 'date_issue',
            name: 'date_issue'
          },
          {
            data: 'member_count',
            name: 'member_count',
            orderable: false,
            searchable: false
          },
          {
            data: 'added_by',
            name: 'added_by'
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ],
        responsive: true,
        autoWidth: false,
      });

      // Add Group Button
      $('#addGroupBtn').click(function() {
        $('#modalTitle').text('Add New Group');
        $('#groupForm')[0].reset();
        $('#group_id').val('');
        $('#groupModal').modal('show');
      });

      // Group Form Submission
      $('#groupForm').submit(function(e) {
        e.preventDefault();

        var groupId = $('#group_id').val();
        var url = groupId ? `/groups/${groupId}` : '{{ route('groups.store') }}';
        var method = groupId ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          type: method,
          data: $(this).serialize(),
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              $('#groupModal').modal('hide');
              table.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            var errors = xhr.responseJSON.errors;
            if (errors) {
              $.each(errors, function(key, value) {
                toastr.error(value[0]);
              });
            } else {
              toastr.error('An error occurred');
            }
          }
        });
      });

      // Edit Button
      $(document).on('click', '.edit-btn', function() {
        var groupId = $(this).data('id');

        $.get(`/groups/${groupId}`, function(data) {
          $('#modalTitle').text('Edit Group');
          $('#group_id').val(data.group_id);
          $('#group_name').val(data.group_name);
          $('#date_issue').val(data.date_issue);
          $('#groupModal').modal('show');
        });
      });

      // Members Button
      $(document).on('click', '.members-btn', function() {
        var groupId = $(this).data('id');
        window.currentGroupId = groupId;
        loadMembers(groupId);
        $('#membersModal').modal('show');
      });

      // Delete Button
      $(document).on('click', '.delete-btn', function() {
        var groupId = $(this).data('id');

        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/groups/${groupId}`,
              type: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  Swal.fire('Deleted!', response.message, 'success');
                }
              },
              error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON.message, 'error');
              }
            });
          }
        });
      });

      function loadMembers(groupId) {
        if ($.fn.DataTable.isDataTable('#membersTable')) {
          $('#membersTable').DataTable().destroy();
        }

        $('#membersTable').DataTable({
          processing: true,
          serverSide: true,
          ajax: `/groups/${groupId}/members`,
          columns: [{
              data: 'customer_name',
              name: 'customer_name'
            },
            {
              data: 'ic_no',
              name: 'ic_no'
            },
            {
              data: 'phone',
              name: 'phone'
            },
            {
              data: 'loan_amount',
              name: 'loan_amount'
            },
            {
              data: 'action',
              name: 'action',
              orderable: false,
              searchable: false
            }
          ],
          responsive: true,
          autoWidth: false,
        });
      }
    });
  </script>
@endpush

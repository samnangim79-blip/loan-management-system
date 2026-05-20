@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.account_management.title'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">{{ __('common.nav.accounts') }}</a></li>
  <li class="breadcrumb-item active">{{ __('common.general.list') }}</li>
@endsection

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ __('common.account_management.account_list') }}</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-primary btn-sm" id="addAccountBtn">
          <i class="fas fa-plus"></i> {{ __('common.account_management.new_account') }}
        </button>
      </div>
    </div>
    <div class="card-body">
      <table id="accountsTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>{{ __('common.account_management.account_no') }}</th>
            <th>{{ __('common.account_management.account_name') }}</th>
            <th>{{ __('common.customer_management.customer') }}</th>
            <th>{{ __('common.account_management.type') }}</th>
            <th>{{ __('common.account_management.balance') }}</th>
            <th>{{ __('common.general.status') }}</th>
            <th>{{ __('common.account_management.opened_date') }}</th>
            <th>{{ __('common.general.action') }}</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Account Modal -->
  <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content d-flex flex-column" style="height: 100vh;">
        <div class="modal-header flex-shrink-0">
          <h5 class="modal-title" id="accountModalLabel">{{ __('common.account_management.add_account') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="accountForm" class="d-flex flex-column flex-grow-1 overflow-hidden">
          @csrf
          <div class="modal-body flex-grow-1 overflow-auto">
            <input type="hidden" id="account_id" name="account_id">
            <input type="hidden" id="form_method" name="_method" value="POST">

            <div class="row">
              <!-- Left Column: Form Fields -->
              <div class="col-lg-8">
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('common.account_management.account_information') }}</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="acct_no" class="form-label">{{ __('common.account_management.account_no') }}</label>
                        <input type="text" class="form-control" id="acct_no" name="acct_no" disabled>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="acct_name" class="form-label">{{ __('common.account_management.account_name') }}
                          <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="acct_name" name="acct_name" required>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="cid" class="form-label">{{ __('common.customer_management.cid') }}</label>
                        <div class="input-group">
                          <input type="text" class="form-control" id="cid" name="cid" placeholder="Enter CID">
                          <button class="btn btn-primary" type="button" id="searchCustomerBtn">
                            <i class="fas fa-search"></i>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="cust_id" class="form-label">{{ __('common.account_management.customer') }} <span
                            class="text-danger">*</span></label>
                        <select class="form-select" id="cust_id" name="cust_id" required>
                          <option value="">{{ __('common.account_management.select_customer') }}</option>
                        </select>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">{{ __('common.account_management.category') }} <span
                            class="text-danger">*</span></label>
                        <select class="form-select" id="category" name="category" required>
                          <option value="">{{ __('common.account_management.select_category') }}</option>
                          <option value="Loan">Loan</option>
                          <option value="Saving">Saving</option>
                          <option value="Fixed Deposit">Fixed Deposit</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="resident" class="form-label">{{ __('common.account_management.resident') }}</label>
                        <select class="form-select" id="resident" name="resident">
                          <option value="">{{ __('common.account_management.select_resident') }}</option>
                          <option value="Yes">Yes</option>
                          <option value="No">No</option>
                        </select>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="currency_id" class="form-label">{{ __('common.account_management.currency') }}
                          <span class="text-danger">*</span></label>
                        <select class="form-select" id="currency_id" name="currency_id" required>
                          <option value="">{{ __('common.account_management.select_currency') }}</option>
                          <option value="USD">USD</option>
                          <option value="KHR">KHR</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="acct_type_id" class="form-label">{{ __('common.account_management.account_type') }}
                          <span class="text-danger">*</span></label>
                        <select class="form-select" id="acct_type_id" name="acct_type_id" required>
                          <option value="">{{ __('common.account_management.select_type') }}</option>
                        </select>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="joint_flag"
                          class="form-label">{{ __('common.account_management.joint_account') }}</label>
                        <select class="form-select" id="joint_flag" name="joint_flag">
                          <option value="0">No</option>
                          <option value="1">Yes</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="extra_rate"
                          class="form-label">{{ __('common.account_management.extra_rate') }}</label>
                        <input type="number" class="form-control" id="extra_rate" name="extra_rate" step="0.01"
                          value="0">
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="mandatory"
                          class="form-label">{{ __('common.account_management.mandatory_field') }}</label>
                        <textarea class="form-control" id="mandatory" name="mandatory" rows="2"></textarea>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="account_status" class="form-label">{{ __('common.general.status') }}</label>
                        <select class="form-select" id="account_status" name="account_status">
                          <option value="1">Active</option>
                          <option value="2">Dormant</option>
                          <option value="3">Suspended</option>
                          <option value="4">Closed</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Column: Photo Upload & Remark -->
              <div class="col-lg-4">
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('common.account_management.photo') }}</h5>
                  </div>
                  <div class="card-body">
                    <div id="accountPhotoUploader"></div>
                  </div>
                </div>

                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('common.account_management.remark') }}</h5>
                  </div>
                  <div class="card-body">
                    <textarea class="form-control" id="remark" name="remark" rows="5"
                      placeholder="{{ __('common.account_management.enter_remark') }}"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer flex-shrink-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times"></i> {{ __('common.general.cancel') }}
            </button>
            <button type="submit" class="btn btn-primary" id="saveAccountBtn">
              <i class="fas fa-save"></i> {{ __('common.account_management.save_account') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Account Modal -->
  <div class="modal fade" id="viewAccountModal" tabindex="-1" aria-labelledby="viewAccountModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewAccountModalLabel">{{ __('common.account_management.account_details') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">{{ __('common.account_management.account_number') }}:</label>
                <p id="view_acct_no" class="mb-0"></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">{{ __('common.account_management.account_name') }}:</label>
                <p id="view_acct_name" class="mb-0"></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">{{ __('common.account_management.customer') }}:</label>
                <p id="view_customer_name" class="mb-0"></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">{{ __('common.account_management.account_type') }}:</label>
                <p id="view_account_type" class="mb-0"></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">{{ __('common.account_management.balance') }}:</label>
                <p id="view_balance" class="mb-0"></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">{{ __('common.general.status') }}:</label>
                <p id="view_status" class="mb-0"></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">{{ __('common.account_management.opened_date') }}:</label>
                <p id="view_opened_date" class="mb-0"></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">{{ __('common.account_management.joint_flag') }}:</label>
                <p id="view_joint_flag" class="mb-0"></p>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> {{ __('common.account_management.close') }}
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Transaction Modal -->
  <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="transactionModalLabel">{{ __('common.account_management.account_transaction') }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="transactionForm">
          @csrf
          <div class="modal-body">
            <input type="hidden" id="transaction_acct_id" name="account_id">

            <div class="mb-3">
              <label for="transaction_type" class="form-label">{{ __('common.account_management.transaction_type') }}
                <span class="text-danger">*</span></label>
              <select class="form-select" id="transaction_type" name="transaction_type" required>
                <option value="">{{ __('common.account_management.select_transaction_type') }}</option>
                <option value="deposit">{{ __('common.account_management.deposit') }}</option>
                <option value="withdraw">{{ __('common.account_management.withdraw') }}</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="amount" class="form-label">{{ __('common.account_management.amount') }} <span
                  class="text-danger">*</span></label>
              <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0"
                required>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">{{ __('common.account_management.description') }}</label>
              <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times"></i> {{ __('common.general.cancel') }}
            </button>
            <button type="submit" class="btn btn-success" id="processTransactionBtn">
              <i class="fas fa-exchange-alt"></i> {{ __('common.account_management.process_transaction') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <!-- Include Panha Media Upload Preview Library -->
  <script src="{{ asset('assets/backend/js/panha-media-upload-preview.js') }}"></script>

  <script>
    let accountsTable;
    let accountPhotoUploader;
    let deletedPhotoIds = [];

    // Translation variables (global scope)
    const translations = {
      addAccount: '{{ __('common.account_management.add_account') }}',
      editAccount: '{{ __('common.account_management.edit_account') }}',
      selectCustomer: '{{ __('common.account_management.select_customer') }}',
      selectType: '{{ __('common.account_management.select_type') }}',
      yes: '{{ __('common.account_management.yes') }}',
      no: '{{ __('common.account_management.no') }}',
      errorOccurred: '{{ __('common.account_management.error_occurred') }}',
      areYouSure: '{{ __('common.account_management.are_you_sure') }}',
      cannotRevert: '{{ __('common.account_management.cannot_revert') }}',
      yesDelete: '{{ __('common.account_management.yes_delete') }}',
      errorDeleting: '{{ __('common.account_management.error_deleting') }}',
      active: '{{ __('common.account_management.active') }}',
      dormant: '{{ __('common.account_management.dormant') }}',
      suspended: '{{ __('common.account_management.suspended') }}',
      closed: '{{ __('common.account_management.closed') }}',
      unknown: '{{ __('common.account_management.unknown') }}'
    };

    $(document).ready(function() {
      // Initialize Account Photo Uploader
      accountPhotoUploader = new PanhaMediaUploadPreview('#accountPhotoUploader', {
        maxFiles: 5,
        maxFileSize: 5 * 1024 * 1024, // 5MB
        allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
        fieldName: 'account_photos[]',
        showFileInfo: true,
        enableDragDrop: true,
        texts: {
          title: 'Account Photos',
          uploadText: 'Drop photos here',
          uploadSubtext: 'Max 5 files, 5MB each (JPG, PNG, WEBP)',
          addMoreText: '{{ __('common.actions.add_more') }}',
          clearAllText: '{{ __('common.actions.clear_all') }}',
          confirmClearText: 'Are you sure you want to remove all files?',
          fileNotImageError: 'File "{filename}" is not allowed.',
          fileTooLargeError: 'File size exceeds 5MB limit'
        },
        callbacks: {
          onFileRemove: function(removedFile) {
            if (removedFile && removedFile.id && removedFile.isExisting) {
              deletedPhotoIds.push(removedFile.id);
              console.log('Marked photo for deletion:', removedFile.id);
            }
          }
        }
      });

      accountsTable = $('#accountsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('accounts.data') }}",
        columns: [{
            data: 'acct_no',
            name: 'acct_no'
          },
          {
            data: 'acct_name',
            name: 'acct_name'
          },
          {
            data: 'customer_name',
            name: 'customer_name'
          },
          {
            data: 'account_type_name',
            name: 'account_type_name'
          },
          {
            data: 'balance',
            name: 'balance'
          },
          {
            data: 'status_badge',
            name: 'status_badge'
          },
          {
            data: 'opened_date',
            name: 'opened_date'
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ]
      });

      // Load customers and account types for form
      loadCustomers();
      loadAccountTypes();

      // Update CID when customer is selected
      $('#cust_id').on('change', function() {
        const custId = $(this).val();
        if (custId) {
          $('#cid').val(custId);
        } else {
          $('#cid').val('');
        }
      });

      // Search customer by CID
      $('#searchCustomerBtn').on('click', function() {
        const cidValue = $('#cid').val().trim();

        if (!cidValue) {
          toastr.warning('Please enter a CID to search');
          return;
        }

        // Search for customer by CID
        $.ajax({
          url: '/api/customers/search-by-cid',
          method: 'GET',
          data: { cid: cidValue },
          success: function(response) {
            if (response.success && response.data) {
              const customer = response.data;

              // Update CID field with the found customer's ID
              $('#cid').val(customer.cust_id);

              // Check if customer option exists in dropdown
              const customerOption = $('#cust_id option[value="' + customer.cust_id + '"]');

              if (customerOption.length === 0) {
                // Customer option doesn't exist, add it to the dropdown
                const customerName = customer.name_en + ' (' + (customer.id_no || customer.cust_id) + ')';
                const newOption = new Option(customerName, customer.cust_id, true, true);
                $('#cust_id').append(newOption);
              } else {
                // Customer option exists, just select it
                $('#cust_id').val(customer.cust_id);
              }

              // Trigger change event
              $('#cust_id').trigger('change');

              // Show success message with customer name
              toastr.success('Customer found: ' + customer.name_en);
            } else {
              toastr.error('Customer not found with CID: ' + cidValue);
              $('#cid').val('');
            }
          },
          error: function(xhr) {
            let errorMessage = 'Error searching for customer';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }
            toastr.error(errorMessage);
            $('#cid').val('');
          }
        });
      });

      // Allow Enter key to trigger search
      $('#cid').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
          e.preventDefault();
          $('#searchCustomerBtn').click();
        }
      });

      // Add Account Button
      $('#addAccountBtn').click(function() {
        resetForm();
        $('#accountModalLabel').text(translations.addAccount);
        $('#form_method').val('POST');
        $('#account_status').val('1'); // Set default status to Active
        $('#accountModal').modal('show');
      });

      // Account Form Submit
      $('#accountForm').submit(function(e) {
        e.preventDefault();

        // Create FormData from form
        const formData = new FormData(this);

        // Get all uploaded photo files and append them to FormData
        const allFiles = accountPhotoUploader.getFiles();
        console.log('All files in uploader:', allFiles);

        // Filter to get only new files (not existing ones loaded from server)
        const newFiles = allFiles.filter(item => item.file !== null && !item.isExisting);
        console.log('New files to upload:', newFiles);

        if (newFiles && newFiles.length > 0) {
          // Remove any existing account_photos entries
          formData.delete('account_photos[]');
          formData.delete('account_photos');

          // Add each file with array notation
          newFiles.forEach((item, index) => {
            console.log(`Adding file ${index}:`, item.file.name, item.file.size);
            formData.append('account_photos[]', item.file);
          });
        }

        let url = "{{ route('accounts.store') }}";
        let method = 'POST';
        const accountId = $('#account_id').val();

        if (accountId) {
          url = "{{ route('accounts.update', ':id') }}".replace(':id', accountId);
          method = 'PUT';
          formData.append('_method', 'PUT');

          // Add deleted photo IDs for update
          if (deletedPhotoIds.length > 0) {
            console.log('Deleted photo IDs:', deletedPhotoIds);
            deletedPhotoIds.forEach((id, index) => {
              formData.append(`deleted_photo_ids[${index}]`, id);
            });
          }
        }

        // Debug: Log FormData contents
        console.log('FormData contents:');
        for (let pair of formData.entries()) {
          console.log(pair[0] + ':', pair[1]);
        }

        $.ajax({
          url: url,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
              $('#accountModal').modal('hide');
              accountsTable.ajax.reload();
            }
          },
          error: function(xhr) {
            console.error('AJAX Error:', xhr);
            if (xhr.status === 422) {
              let errors = xhr.responseJSON.errors;
              $.each(errors, function(key, value) {
                toastr.error(value[0]);
              });
            } else {
              let errorMessage = translations.errorOccurred;
              if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
              }
              toastr.error(errorMessage);
            }
          }
        });
      });

      // Transaction Form Submit
      $('#transactionForm').submit(function(e) {
        e.preventDefault();

        let formData = $(this).serialize();
        let accountId = $('#transaction_acct_id').val();
        let transactionType = $('#transaction_type').val();
        let url = "{{ route('accounts.deposit', ':id') }}".replace(':id', accountId);

        if (transactionType === 'withdraw') {
          url = "{{ route('accounts.withdraw', ':id') }}".replace(':id', accountId);
        }

        $.ajax({
          url: url,
          type: 'POST',
          data: formData,
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
              $('#transactionModal').modal('hide');
              accountsTable.ajax.reload();
            }
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              let errors = xhr.responseJSON.errors;
              $.each(errors, function(key, value) {
                toastr.error(value[0]);
              });
            } else {
              toastr.error(translations.errorOccurred);
            }
          }
        });
      });

      // Handle action buttons (delegated events)
      $(document).on('click', '.view-account', function() {
        let accountId = $(this).data('id');
        viewAccount(accountId);
      });

      $(document).on('click', '.edit-account', function() {
        let accountId = $(this).data('id');
        editAccount(accountId);
      });

      $(document).on('click', '.transaction-btn', function() {
        let accountId = $(this).data('id');
        openTransactionModal(accountId);
      });

      $(document).on('click', '.delete-account', function() {
        let accountId = $(this).data('id');
        deleteAccount(accountId);
      });
    });

    function loadCustomers() {
      $.get("{{ route('api.customers') }}", function(data) {
        let options = `<option value="">${translations.selectCustomer}</option>`;
        $.each(data, function(index, customer) {
          options += `<option value="${customer.cust_id}">${customer.name_en} (${customer.id_no})</option>`;
        });
        $('#cust_id').html(options);
      });
    }

    function loadAccountTypes() {
      $.get("{{ route('api.account-types') }}", function(data) {
        let options = `<option value="">${translations.selectType}</option>`;
        $.each(data, function(index, type) {
          options += `<option value="${type.acct_type_id}">${type.acct_type}</option>`;
        });
        $('#acct_type_id').html(options);
      });
    }

    function resetForm() {
      $('#accountForm')[0].reset();
      $('#account_id').val('');
      $('#form_method').val('POST');
      accountPhotoUploader.clear();
      deletedPhotoIds = [];
    }

    function viewAccount(accountId) {
      $.get("{{ route('accounts.show', ':id') }}".replace(':id', accountId), function(data) {
        if (data.success) {
          let account = data.data;
          $('#view_acct_no').text(account.acct_no);
          $('#view_acct_name').text(account.acct_name);
          $('#view_customer_name').text(account.customer ? account.customer.name_en : 'N/A');
          $('#view_account_type').text(account.account_type ? account.account_type.acct_type : 'N/A');
          $('#view_balance').text('$' + parseFloat(account.balance || 0).toFixed(2));
          $('#view_status').html(getStatusBadge(account.account_status));
          $('#view_opened_date').text(account.opened_date);
          $('#view_joint_flag').text(account.joint_flag == 1 ? translations.yes : translations.no);
          $('#viewAccountModal').modal('show');
        }
      });
    }

    function editAccount(accountId) {
      $.get("{{ route('accounts.show', ':id') }}".replace(':id', accountId), function(data) {
        if (data.success) {
          let account = data.data;

          // Reset form first
          resetForm();

          // Set account ID and modal title
          $('#account_id').val(account.acct_id);
          $('#accountModalLabel').text(translations.editAccount);

          // Populate form fields
          $('#acct_no').val(account.acct_no);
          $('#acct_name').val(account.acct_name);
          $('#cid').val(account.cust_id);
          $('#cust_id').val(account.cust_id);
          $('#category').val(account.category);
          $('#resident').val(account.resident);
          $('#currency_id').val(account.currency_id);
          $('#acct_type_id').val(account.acct_type_id);
          $('#joint_flag').val(account.joint_flag || 0);
          $('#extra_rate').val(account.extra_rate || 0);
          $('#mandatory').val(account.mandatory);
          $('#account_status').val(account.account_status || 1);
          $('#remark').val(account.remark);
          $('#form_method').val('PUT');

          // Load existing photos if available
          if (account.photos && account.photos.length > 0) {
            const existingPhotos = account.photos.map(photo => ({
              url: photo.url,
              name: photo.name,
              size: photo.size || 0,
              id: photo.id,
              type: photo.type
            }));
            accountPhotoUploader.loadExistingFiles(existingPhotos);
          }

          // Show modal
          $('#accountModal').modal('show');
        }
      });
    }

    function openTransactionModal(accountId) {
      $('#transaction_acct_id').val(accountId);
      $('#transactionForm')[0].reset();
      $('#transaction_acct_id').val(accountId);
      $('#transactionModal').modal('show');
    }

    function deleteAccount(accountId) {
      Swal.fire({
        title: translations.areYouSure,
        text: translations.cannotRevert,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: translations.yesDelete
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('accounts.destroy', ':id') }}".replace(':id', accountId),
            type: 'DELETE',
            data: {
              _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
              if (response.success) {
                toastr.success(response.message);
                accountsTable.ajax.reload();
              }
            },
            error: function(xhr) {
              toastr.error(translations.errorDeleting);
            }
          });
        }
      });
    }

    function getStatusBadge(status) {
      const statusMap = {
        1: `<span class="badge bg-success">${translations.active}</span>`,
        2: `<span class="badge bg-warning">${translations.dormant}</span>`,
        3: `<span class="badge bg-danger">${translations.suspended}</span>`,
        4: `<span class="badge bg-secondary">${translations.closed}</span>`
      };
      return statusMap[status] || `<span class="badge bg-primary">${translations.unknown}</span>`;
    }
  </script>
@endpush

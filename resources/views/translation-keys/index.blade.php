@extends('admin.layouts.admin_layout')

@section('pageTitle', 'Translation Keys Management')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('translation-keys.index') }}">Translation Keys</a></li>
  <li class="breadcrumb-item active">List</li>
@endsection

@section('content')
  <!-- Statistics Cards -->
  <div class="row mb-4" id="statisticsCards">
    <div class="col-md-3">
      <div class="card bg-primary text-white">
        <div class="card-body">
          <h5 class="card-title">Total Keys</h5>
          <h2 class="mb-0" id="totalKeys">-</h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-success text-white">
        <div class="card-body">
          <h5 class="card-title">Active Keys</h5>
          <h2 class="mb-0" id="activeKeys">-</h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-info text-white">
        <div class="card-body">
          <h5 class="card-title">Auto-Translated</h5>
          <h2 class="mb-0" id="autoTranslatedKeys">-</h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-white">
        <div class="card-body">
          <h5 class="card-title">Missing Translations</h5>
          <h2 class="mb-0" id="missingTranslations">-</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-language me-2"></i>Translation Keys</h3>
          <div class="card-tools">
            <div class="btn-group me-2">
              <button type="button" class="btn btn-secondary btn-sm" id="scanLangBtn">
                <i class="fas fa-search"></i> Scan Lang
              </button>
              <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#loadLangModal">
                <i class="fas fa-download"></i> Load Lang
              </button>
              <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#syncModal">
                <i class="fas fa-sync"></i> Sync
              </button>
            </div>
            <div class="btn-group">
              <button type="button" class="btn btn-success btn-sm" id="importBtn">
                <i class="fas fa-file-import"></i> Import
              </button>
              <button type="button" class="btn btn-info btn-sm" id="exportBtn">
                <i class="fas fa-file-export"></i> Export
              </button>
              <button type="button" class="btn btn-warning btn-sm" id="bulkTranslateBtn">
                <i class="fas fa-robot"></i> Bulk Auto-Translate
              </button>
              <button type="button" class="btn btn-primary btn-sm" id="addKeyBtn">
                <i class="fas fa-plus"></i> Add Key
              </button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <!-- Filter Section -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="groupFilter" class="form-label">Filter by Group</label>
              <select class="form-select form-select-sm" id="groupFilter">
                <option value="">All Groups</option>
              </select>
            </div>
          </div>

          <table id="keysTable" class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th width="5%">ID</th>
                <th width="20%">Key Name</th>
                <th width="10%">Group</th>
                <th width="35%">Translations</th>
                <th width="10%">Status</th>
                <th width="20%">Actions</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Modal -->
  <div class="modal fade" id="keyModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Translation Key</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="keyForm" class="d-flex flex-column h-100">
          <div class="modal-body flex-grow-1 overflow-auto">
            <input type="hidden" id="key_id" name="key_id">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="key_name" class="form-label">Key Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="key_name" name="key_name" required
                    placeholder="e.g., common.general.save">
                  <small class="text-muted">Use dot notation: group.category.key</small>
                </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="group" class="form-label">Group <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="group" name="group" required
                    placeholder="e.g., common">
                </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="is_active" class="form-label">Status</label>
                  <select class="form-select" id="is_active" name="is_active" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" id="description" name="description" rows="2"
                placeholder="Describe where and how this key is used"></textarea>
            </div>

            <hr>

            <div class="row">
              <div class="col-md-12">
                <div class="mb-3">
                  <label for="en" class="form-label">
                    English (EN) <span class="text-danger">*</span>
                    <span class="badge bg-success">Primary</span>
                  </label>
                  <textarea class="form-control" id="en" name="en" rows="2" required
                    placeholder="English translation"></textarea>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="kh" class="form-label">
                    Khmer (KH) 🇰🇭
                  </label>
                  <textarea class="form-control" id="kh" name="kh" rows="2"
                    placeholder="Khmer translation (leave empty for auto-translate)"></textarea>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="zh" class="form-label">
                    Chinese (ZH) 🇨🇳
                  </label>
                  <textarea class="form-control" id="zh" name="zh" rows="2"
                    placeholder="Chinese translation (leave empty for auto-translate)"></textarea>
                </div>
              </div>
            </div>

            <div class="alert alert-info">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="auto_translate" name="auto_translate"
                  value="1">
                <label class="form-check-label" for="auto_translate">
                  <i class="fas fa-robot"></i> Automatically translate empty fields using Google Translate
                </label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Translation Key</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Translation Key Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="viewContent"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Import Modal -->
  <div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Import from Language Files</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="importForm">
          <div class="modal-body">
            <div class="mb-3">
              <label for="import_group" class="form-label">Group <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="import_group" name="group" required
                placeholder="e.g., common">
              <small class="text-muted">Must match the PHP file name (without .php)</small>
            </div>
            <div class="mb-3">
              <label for="import_locale" class="form-label">Locale <span class="text-danger">*</span></label>
              <select class="form-select" id="import_locale" name="locale" required>
                <option value="en">English (en)</option>
                <option value="kh">Khmer (kh)</option>
                <option value="zh">Chinese (zh)</option>
              </select>
            </div>
            <div class="alert alert-warning">
              <i class="fas fa-info-circle"></i> This will import translations from
              <code>lang/{locale}/{group}.php</code>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Import</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Export Modal -->
  <div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Export to PHP File</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="exportForm">
          <div class="modal-body">
            <div class="mb-3">
              <label for="export_group" class="form-label">Group <span class="text-danger">*</span></label>
              <select class="form-select" id="export_group" name="group" required></select>
            </div>
            <div class="mb-3">
              <label for="export_locale" class="form-label">Locale <span class="text-danger">*</span></label>
              <select class="form-select" id="export_locale" name="locale" required>
                <option value="en">English (en)</option>
                <option value="kh">Khmer (kh)</option>
                <option value="zh">Chinese (zh)</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-info">Export</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Load from Lang Files Modal -->
  <div class="modal fade" id="loadLangModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-download"></i> Load from Lang Files</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="loadLangForm">
          <div class="modal-body">
            <div class="alert alert-info">
              <i class="fas fa-info-circle"></i>
              This will scan <code>resources/lang/</code> directory and load translation keys into the database.
            </div>

            <div class="mb-3">
              <label class="form-label">Select Locales</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="en" id="locale_en" name="locales[]"
                  checked>
                <label class="form-check-label" for="locale_en">English (en)</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="kh" id="locale_kh" name="locales[]"
                  checked>
                <label class="form-check-label" for="locale_kh">Khmer (kh)</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="zh" id="locale_zh" name="locales[]"
                  checked>
                <label class="form-check-label" for="locale_zh">Chinese (zh)</label>
              </div>
            </div>

            <div class="mb-3">
              <label for="load_groups" class="form-label">Select Groups (Optional)</label>
              <select class="form-select" id="load_groups" name="groups[]" multiple size="5">
                <option value="">Loading available groups...</option>
              </select>
              <small class="text-muted">Leave empty to load all groups</small>
            </div>

            <div class="mb-3">
              <label class="form-label">Existing Keys Handling</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="merge_mode" id="merge_skip" value="skip"
                  checked>
                <label class="form-check-label" for="merge_skip">
                  Skip existing keys (keep database values)
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="merge_mode" id="merge_update" value="update">
                <label class="form-check-label" for="merge_update">
                  Update existing keys (overwrite with file values)
                </label>
              </div>
            </div>

            <div id="loadProgress" class="d-none">
              <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                  style="width: 0%"></div>
              </div>
              <div class="text-center mt-2">
                <small class="text-muted">Loading keys...</small>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-info">
              <i class="fas fa-download"></i> Load Keys
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Sync with Lang Files Modal -->
  <div class="modal fade" id="syncModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-sync"></i> Sync with Lang Files</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="syncForm">
          <div class="modal-body">
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle"></i>
              <strong>Warning:</strong> Sync operations will modify files/database based on direction chosen.
            </div>

            <div class="mb-3">
              <label class="form-label">Sync Direction <span class="text-danger">*</span></label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="direction" id="sync_import" value="import"
                  checked>
                <label class="form-check-label" for="sync_import">
                  <strong>Import:</strong> Load keys from files → database
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="direction" id="sync_export" value="export">
                <label class="form-check-label" for="sync_export">
                  <strong>Export:</strong> Write database keys → files
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="direction" id="sync_both" value="both">
                <label class="form-check-label" for="sync_both">
                  <strong>Both:</strong> Two-way sync (merge changes)
                </label>
              </div>
            </div>

            <div id="syncProgress" class="d-none">
              <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                  style="width: 0%"></div>
              </div>
              <div class="text-center mt-2">
                <small class="text-muted">Syncing...</small>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-dark">
              <i class="fas fa-sync"></i> Start Sync
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bulk Translate Modal -->
  <div class="modal fade" id="bulkTranslateModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Bulk Auto-Translate</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="bulkTranslateForm">
          <div class="modal-body">
            <div class="mb-3">
              <label for="bulk_group" class="form-label">Group (Optional)</label>
              <select class="form-select" id="bulk_group" name="group">
                <option value="">All Groups</option>
              </select>
              <small class="text-muted">Select a specific group or leave empty to translate all keys</small>
            </div>
            <div class="alert alert-info">
              <i class="fas fa-info-circle"></i> This will auto-translate all keys with missing translations.
              Only keys that have empty translations will be processed.
            </div>
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle"></i>
              <strong>Note:</strong> This process may take several minutes depending on the number of keys.
              Please do not close this window.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning">
              <i class="fas fa-robot"></i> Start Auto-Translation
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      let table;

      // Load statistics
      function loadStatistics() {
        $.get("{{ route('translation-keys.statistics') }}")
          .done(function(response) {
            if (response.success) {
              $('#totalKeys').text(response.data.total);
              $('#activeKeys').text(response.data.active);
              $('#autoTranslatedKeys').text(response.data.auto_translated);

              const missing = response.data.missing_translations;
              const totalMissing = missing.en + missing.kh + missing.zh;
              $('#missingTranslations').text(totalMissing);

              // Populate group filters
              const groups = response.data.groups;
              const groupSelects = ['#groupFilter', '#export_group', '#bulk_group'];
              groupSelects.forEach(selector => {
                const $select = $(selector);
                const currentVal = $select.val();
                $select.empty();
                if (selector === '#groupFilter') {
                  $select.append('<option value="">All Groups</option>');
                }
                groups.forEach(group => {
                  $select.append(`<option value="${group}">${group}</option>`);
                });
                if (currentVal) {
                  $select.val(currentVal);
                }
              });
            }
          });
      }

      loadStatistics();

      // Initialize DataTable
      table = $('#keysTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('translation-keys.data') }}",
          data: function(d) {
            d.group = $('#groupFilter').val();
          }
        },
        columns: [{
            data: 'key_id',
            name: 'key_id'
          },
          {
            data: 'key_name',
            name: 'key_name'
          },
          {
            data: 'group',
            name: 'group'
          },
          {
            data: 'translations',
            name: 'translations',
            orderable: false,
            searchable: false
          },
          {
            data: 'status',
            name: 'is_active'
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ],
        order: [
          [1, 'asc']
        ]
      });

      // Filter by group
      $('#groupFilter').change(function() {
        table.ajax.reload();
      });

      // Add Key
      $('#addKeyBtn').click(function() {
        $('#keyForm')[0].reset();
        $('#key_id').val('');
        $('#modalTitle').text('Add Translation Key');
        $('#keyModal').modal('show');
      });

      // View Key
      $('#keysTable').on('click', '.view-btn', function() {
        const id = $(this).data('id');
        $.get("{{ route('translation-keys.show', ':id') }}".replace(':id', id))
          .done(function(response) {
            if (response.success) {
              const key = response.data;
              let html = `
                <table class="table table-bordered">
                  <tr><th width="30%">Key ID</th><td>${key.key_id}</td></tr>
                  <tr><th>Key Name</th><td><code>${key.key_name}</code></td></tr>
                  <tr><th>Group</th><td><span class="badge bg-secondary">${key.group}</span></td></tr>
                  <tr><th>Description</th><td>${key.description || '-'}</td></tr>
                  <tr><th>English (EN)</th><td>${key.en || '-'}</td></tr>
                  <tr><th>Khmer (KH)</th><td>${key.kh || '-'}</td></tr>
                  <tr><th>Chinese (ZH)</th><td>${key.zh || '-'}</td></tr>
                  <tr><th>Status</th><td>
                    <span class="badge bg-${key.is_active ? 'success' : 'danger'}">
                      ${key.is_active ? 'Active' : 'Inactive'}
                    </span>
                    ${key.auto_translated ? '<span class="badge bg-info ms-1">Auto-translated</span>' : ''}
                  </td></tr>
                  <tr><th>Created</th><td>${key.created_date || '-'}</td></tr>
                  <tr><th>Modified</th><td>${key.modify_date || '-'}</td></tr>
                </table>
              `;
              $('#viewContent').html(html);
              $('#viewModal').modal('show');
            }
          })
          .fail(function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Failed to load details');
          });
      });

      // Edit Key
      $('#keysTable').on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.get("{{ route('translation-keys.show', ':id') }}".replace(':id', id))
          .done(function(response) {
            if (response.success) {
              const key = response.data;
              $('#key_id').val(key.key_id);
              $('#key_name').val(key.key_name);
              $('#group').val(key.group);
              $('#description').val(key.description);
              $('#en').val(key.en);
              $('#kh').val(key.kh);
              $('#zh').val(key.zh);
              $('#is_active').val(key.is_active ? 1 : 0);
              $('#modalTitle').text('Edit Translation Key');
              $('#keyModal').modal('show');
            }
          })
          .fail(function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Failed to load key');
          });
      });

      // Save Key
      $('#keyForm').submit(function(e) {
        e.preventDefault();
        const id = $('#key_id').val();
        const url = id ?
          "{{ route('translation-keys.update', ':id') }}".replace(':id', id) :
          "{{ route('translation-keys.store') }}";
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          })
          .done(function(response) {
            if (response.success) {
              toastr.success(response.message);
              $('#keyModal').modal('hide');
              table.ajax.reload();
              loadStatistics();
            }
          })
          .fail(function(xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              const errorMessage = Object.values(errors).flat().join('<br>');
              toastr.error(errorMessage);
            } else {
              toastr.error(xhr.responseJSON?.message || 'An error occurred');
            }
          });
      });

      // Auto-translate single key
      $('#keysTable').on('click', '.translate-btn', function() {
        const id = $(this).data('id');
        const btn = $(this);
        btn.prop('disabled', true);

        Swal.fire({
          title: 'Auto-Translate?',
          text: "This will automatically translate missing languages using Google Translate.",
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, translate it!',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('translation-keys.auto-translate', ':id') }}".replace(':id', id),
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
              })
              .done(function(response) {
                if (response.success) {
                  toastr.success(response.message);
                  table.ajax.reload();
                  loadStatistics();
                } else {
                  toastr.warning(response.message);
                }
              })
              .fail(function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Translation failed');
              })
              .always(function() {
                btn.prop('disabled', false);
              });
          } else {
            btn.prop('disabled', false);
          }
        });
      });

      // Delete Key
      $('#keysTable').on('click', '.delete-btn', function() {
        const id = $(this).data('id');

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
                url: "{{ route('translation-keys.destroy', ':id') }}".replace(':id', id),
                method: 'DELETE',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
              })
              .done(function(response) {
                if (response.success) {
                  Swal.fire('Deleted!', response.message, 'success');
                  table.ajax.reload();
                  loadStatistics();
                }
              })
              .fail(function(xhr) {
                Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete', 'error');
              });
          }
        });
      });

      // Import
      $('#importBtn').click(function() {
        $('#importModal').modal('show');
      });

      $('#importForm').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('translation-keys.import') }}",
            method: 'POST',
            data: $(this).serialize(),
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          })
          .done(function(response) {
            if (response.success) {
              toastr.success(response.message);
              $('#importModal').modal('hide');
              table.ajax.reload();
              loadStatistics();
            }
          })
          .fail(function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Import failed');
          });
      });

      // Export
      $('#exportBtn').click(function() {
        $('#exportModal').modal('show');
      });

      $('#exportForm').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('translation-keys.export') }}",
            method: 'POST',
            data: $(this).serialize(),
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          })
          .done(function(response) {
            if (response.success) {
              // Create download
              const blob = new Blob([response.content], {
                type: 'text/plain'
              });
              const url = window.URL.createObjectURL(blob);
              const a = document.createElement('a');
              a.href = url;
              a.download = response.filename;
              document.body.appendChild(a);
              a.click();
              window.URL.revokeObjectURL(url);
              document.body.removeChild(a);

              toastr.success('File exported successfully');
              $('#exportModal').modal('hide');
            }
          })
          .fail(function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Export failed');
          });
      });

      // Bulk Translate
      $('#bulkTranslateBtn').click(function() {
        $('#bulkTranslateModal').modal('show');
      });

      $('#bulkTranslateForm').submit(function(e) {
        e.preventDefault();

        Swal.fire({
          title: 'Starting Bulk Translation',
          text: 'This may take several minutes. Please wait...',
          icon: 'info',
          allowOutsideClick: false,
          showConfirmButton: false,
          willOpen: () => {
            Swal.showLoading();
          }
        });

        $.ajax({
            url: "{{ route('translation-keys.bulk-auto-translate') }}",
            method: 'POST',
            data: $(this).serialize(),
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            timeout: 300000 // 5 minutes timeout
          })
          .done(function(response) {
            Swal.close();
            if (response.success) {
              Swal.fire('Success!', response.message, 'success');
              $('#bulkTranslateModal').modal('hide');
              table.ajax.reload();
              loadStatistics();
            }
          })
          .fail(function(xhr) {
            Swal.close();
            Swal.fire('Error!', xhr.responseJSON?.message || 'Bulk translation failed', 'error');
          });
      });

      // Scan Lang Files button
      $('#scanLangBtn').click(function() {
        const btn = $(this);
        btn.prop('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin"></i> Scanning...');

        $.ajax({
            url: "{{ route('translation-keys.scan') }}",
            method: 'GET',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          })
          .done(function(response) {
            if (response.success) {
              const data = response.data;
              let html = '<div class="alert alert-info">';
              html += '<h6><i class="fas fa-folder-open"></i> Available Lang Files:</h6>';
              html += '<strong>Locales:</strong> ' + data.locales.join(', ') + '<br>';
              html += '<strong>Groups:</strong> ' + data.groups.join(', ') + '<br>';
              html += '<strong>Total Files:</strong> ' + data.total_files;
              html += '</div>';

              Swal.fire({
                title: 'Scan Results',
                html: html,
                icon: 'success',
                width: '600px'
              });

              // Update load modal groups dropdown
              const groupsSelect = $('#load_groups');
              groupsSelect.empty();
              data.groups.forEach(function(group) {
                groupsSelect.append(new Option(group, group));
              });
            }
          })
          .fail(function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Scan failed');
          })
          .always(function() {
            btn.prop('disabled', false);
            btn.html('<i class="fas fa-search"></i> Scan Lang');
          });
      });

      // Load modal opened - scan for groups
      $('#loadLangModal').on('show.bs.modal', function() {
        $.get("{{ route('translation-keys.scan') }}")
          .done(function(response) {
            if (response.success) {
              const groupsSelect = $('#load_groups');
              groupsSelect.empty();
              response.data.groups.forEach(function(group) {
                groupsSelect.append(new Option(group, group));
              });
            }
          });
      });

      // Load from Lang Files form
      $('#loadLangForm').submit(function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {
          locales: [],
          groups: $('#load_groups').val() || [],
          merge_mode: $('input[name="merge_mode"]:checked').val()
        };

        // Get checked locales
        $('input[name="locales[]"]:checked').each(function() {
          data.locales.push($(this).val());
        });

        if (data.locales.length === 0) {
          toastr.warning('Please select at least one locale');
          return;
        }

        $('#loadProgress').removeClass('d-none');
        $('#loadProgress .progress-bar').css('width', '30%');

        Swal.fire({
          title: 'Loading Keys...',
          text: 'This may take a moment. Please wait...',
          icon: 'info',
          allowOutsideClick: false,
          showConfirmButton: false,
          willOpen: () => {
            Swal.showLoading();
          }
        });

        $.ajax({
            url: "{{ route('translation-keys.load-from-lang') }}",
            method: 'POST',
            data: data,
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            timeout: 300000
          })
          .done(function(response) {
            Swal.close();
            $('#loadProgress').addClass('d-none');

            if (response.success) {
              const data = response.data;
              let html = '<div class="alert alert-success">';
              html += '<strong>Keys Loaded Successfully!</strong><br>';
              html += 'Created: ' + data.created + '<br>';
              html += 'Updated: ' + data.updated + '<br>';
              html += 'Skipped: ' + data.skipped + '<br>';
              html += 'Total: ' + data.total;
              html += '</div>';

              Swal.fire({
                title: 'Load Complete',
                html: html,
                icon: 'success',
                width: '500px'
              });

              $('#loadLangModal').modal('hide');
              table.ajax.reload();
              loadStatistics();
            }
          })
          .fail(function(xhr) {
            Swal.close();
            $('#loadProgress').addClass('d-none');
            Swal.fire('Error!', xhr.responseJSON?.message || 'Load failed', 'error');
          });
      });

      // Sync with Lang Files form
      $('#syncForm').submit(function(e) {
        e.preventDefault();

        const direction = $('input[name="direction"]:checked').val();

        Swal.fire({
          title: 'Are you sure?',
          html: 'This will sync translation keys with lang files.<br>Direction: <strong>' + direction
            .toUpperCase() + '</strong>',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, sync it!',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            $('#syncProgress').removeClass('d-none');
            $('#syncProgress .progress-bar').css('width', '50%');

            Swal.fire({
              title: 'Syncing...',
              text: 'Please wait while we sync the files...',
              icon: 'info',
              allowOutsideClick: false,
              showConfirmButton: false,
              willOpen: () => {
                Swal.showLoading();
              }
            });

            $.ajax({
                url: "{{ route('translation-keys.sync') }}",
                method: 'POST',
                data: {
                  direction: direction
                },
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                timeout: 300000
              })
              .done(function(response) {
                Swal.close();
                $('#syncProgress').addClass('d-none');

                if (response.success) {
                  const data = response.data;
                  let html = '<div class="alert alert-success">';
                  html += '<strong>Sync Complete!</strong><br>';

                  if (data.imported !== undefined) {
                    html += 'Imported: ' + data.imported + ' keys<br>';
                  }
                  if (data.exported !== undefined) {
                    html += 'Exported: ' + data.exported + ' keys<br>';
                  }
                  if (data.files_created !== undefined) {
                    html += 'Files Created: ' + data.files_created + '<br>';
                  }
                  if (data.files_updated !== undefined) {
                    html += 'Files Updated: ' + data.files_updated;
                  }

                  html += '</div>';

                  Swal.fire({
                    title: 'Sync Successful',
                    html: html,
                    icon: 'success',
                    width: '500px'
                  });

                  $('#syncModal').modal('hide');
                  table.ajax.reload();
                  loadStatistics();
                }
              })
              .fail(function(xhr) {
                Swal.close();
                $('#syncProgress').addClass('d-none');
                Swal.fire('Error!', xhr.responseJSON?.message || 'Sync failed', 'error');
              });
          }
        });
      });
    });
  </script>
@endpush

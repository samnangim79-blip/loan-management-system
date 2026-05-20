@extends('admin.layouts.admin_layout')

@section('pageTitle', 'Chart of Accounts Tree View')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('gl.index') }}">{{ __('common.nav.chart_of_accounts') }}</a></li>
  <li class="breadcrumb-item active">Tree View</li>
@endsection

@push('styles')
  <!-- jsTree CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.16/themes/default/style.min.css" />

  <style>
    /* Card styling */
    .card {
      border: 1px solid #dee2e6;
      border-radius: 0.25rem;
    }

    .card-header {
      background-color: #fff;
      border-bottom: 1px solid #dee2e6;
      padding: 1rem 1.25rem;
    }

    .card-header .card-title {
      color: #495057;
      font-weight: 600;
      font-size: 1.1rem;
      margin: 0;
    }

    .card-body {
      background-color: #fff;
      padding: 1.25rem;
    }

    /* Tree container */
    #treeContainer {
      background-color: #fff;
      padding: 15px;
      min-height: 400px;
    }

    .loading {
      text-align: center;
      padding: 60px 20px;
    }

    .loading i {
      color: #6c757d;
      margin-bottom: 10px;
    }

    .loading p {
      font-size: 1em;
      color: #6c757d;
      margin: 0;
    }

    /* Search box styling */
    .search-box {
      margin-bottom: 15px;
      position: relative;
    }

    .search-box input {
      width: 100%;
      padding: 8px 35px 8px 12px;
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
      font-size: 14px;
    }

    .search-box input:focus {
      outline: none;
      border-color: #80bdff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .search-box::after {
      content: "\f002";
      font-family: "Font Awesome 5 Free";
      font-weight: 900;
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
      pointer-events: none;
    }

    /* Custom jsTree styling */
    .jstree-default .jstree-node {
      margin-left: 24px;
    }

    .jstree-default .jstree-anchor {
      font-size: 14px;
      padding: 5px 8px;
      border-radius: 3px;
      line-height: 1.5;
    }

    .jstree-default .jstree-anchor:hover {
      background-color: #f8f9fa;
    }

    .jstree-default .jstree-clicked {
      background-color: #007bff !important;
      color: white !important;
    }

    .jstree-default .jstree-clicked .gl-code {
      background-color: rgba(255, 255, 255, 0.9);
      color: #007bff;
    }

    /* Level-specific colors - simple and clean */
    .jstree-node[data-level="1"]>.jstree-anchor {
      font-weight: 600;
      font-size: 15px;
      color: #212529;
    }

    .jstree-node[data-level="1"]>.jstree-anchor .gl-code {
      background-color: #007bff;
      color: white;
    }

    .jstree-node[data-level="2"]>.jstree-anchor {
      font-weight: 500;
      color: #28a745;
    }

    .jstree-node[data-level="2"]>.jstree-anchor .gl-code {
      background-color: #28a745;
      color: white;
    }

    .jstree-node[data-level="3"]>.jstree-anchor {
      color: #fd7e14;
    }

    .jstree-node[data-level="3"]>.jstree-anchor .gl-code {
      background-color: #fd7e14;
      color: white;
    }

    .jstree-node[data-level="4"]>.jstree-anchor {
      color: #17a2b8;
    }

    .jstree-node[data-level="4"]>.jstree-anchor .gl-code {
      background-color: #17a2b8;
      color: white;
    }

    .jstree-node[data-level="gl"]>.jstree-anchor {
      color: #6c757d;
      font-size: 13px;
    }

    .jstree-node[data-level="gl"]>.jstree-anchor .gl-code {
      background-color: #6c757d;
      color: white;
    }

    /* GL Code badge styling */
    .gl-code {
      font-family: 'Courier New', monospace;
      font-weight: 600;
      margin-right: 8px;
      padding: 2px 8px;
      border-radius: 3px;
      display: inline-block;
      font-size: 12px;
    }

    /* Button styling */
    .card-tools .btn {
      margin-left: 5px;
    }
  </style>
@endpush

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-sitemap me-2"></i>Chart of Accounts - Tree View</h3>
          <div class="card-tools">
            <a href="{{ route('gl.index') }}" class="btn btn-secondary btn-sm">
              <i class="fas fa-list"></i> List View
            </a>
            <button type="button" class="btn btn-primary btn-sm" id="expandAllBtn">
              <i class="fas fa-expand"></i> Expand All
            </button>
            <button type="button" class="btn btn-secondary btn-sm" id="collapseAllBtn">
              <i class="fas fa-compress"></i> Collapse All
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="search-box">
            <input type="text" id="searchTree" class="form-control" placeholder="Search chart of accounts...">
          </div>
          <div id="treeContainer">
            <div class="loading">
              <i class="fas fa-spinner fa-spin fa-2x"></i>
              <p>Loading chart of accounts...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <!-- jsTree JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.16/jstree.min.js"></script>

  <script>
    $(document).ready(function() {
      let treeInstance;

      loadTreeView();

      // Expand all button
      $('#expandAllBtn').click(function() {
        if (treeInstance) {
          $('#treeContainer').jstree('open_all');
        }
      });

      // Collapse all button
      $('#collapseAllBtn').click(function() {
        if (treeInstance) {
          $('#treeContainer').jstree('close_all');
        }
      });

      // Search functionality
      let searchTimeout = false;
      $('#searchTree').keyup(function() {
        if (searchTimeout) {
          clearTimeout(searchTimeout);
        }
        searchTimeout = setTimeout(function() {
          const searchString = $('#searchTree').val();
          $('#treeContainer').jstree('search', searchString);
        }, 250);
      });

      function loadTreeView() {
        $.ajax({
            url: '{{ route('gl.tree') }}',
            method: 'GET',
            headers: {
              'Accept': 'application/json'
            }
          })
          .done(function(data) {
            renderTree(data);
          })
          .fail(function() {
            $('#treeContainer').html(
              '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Failed to load chart of accounts tree.</div>'
            );
          });
      }

      function renderTree(data) {
        if (!data || data.length === 0) {
          $('#treeContainer').html('<div class="alert alert-info">No accounts found in the chart of accounts.</div>');
          return;
        }

        // Convert data to jsTree format
        const treeData = [];

        data.forEach(function(l1) {
          const l1Node = {
            id: 'l1_' + l1.l1_id,
            text: `<span class="gl-code">${l1.l1_no || ''}</span>${l1.l1_desc || 'N/A'}${l1.l1_desc_kh ? ' / ' + l1.l1_desc_kh : ''}`,
            state: {
              opened: true
            },
            children: [],
            li_attr: {
              'data-level': '1'
            }
          };

          if (l1.level2s && l1.level2s.length > 0) {
            l1.level2s.forEach(function(l2) {
              const l2Node = {
                id: 'l2_' + l2.l2_id,
                text: `<span class="gl-code">${l2.l2_no || ''}</span>${l2.l2_desc || 'N/A'}${l2.l2_desc_kh ? ' / ' + l2.l2_desc_kh : ''}`,
                state: {
                  opened: false
                },
                children: [],
                li_attr: {
                  'data-level': '2'
                }
              };

              if (l2.level3s && l2.level3s.length > 0) {
                l2.level3s.forEach(function(l3) {
                  const l3Node = {
                    id: 'l3_' + l3.l3_id,
                    text: `<span class="gl-code">${l3.l3_no || ''}</span>${l3.l3_desc || 'N/A'}${l3.l3_desc_kh ? ' / ' + l3.l3_desc_kh : ''}`,
                    state: {
                      opened: false
                    },
                    children: [],
                    li_attr: {
                      'data-level': '3'
                    }
                  };

                  if (l3.level4s && l3.level4s.length > 0) {
                    l3.level4s.forEach(function(l4) {
                      const l4Node = {
                        id: 'l4_' + l4.l4_id,
                        text: `<span class="gl-code">${l4.l4_no || ''}</span>${l4.l4_desc || 'N/A'}${l4.l4_desc_kh ? ' / ' + l4.l4_desc_kh : ''}`,
                        state: {
                          opened: false
                        },
                        children: [],
                        li_attr: {
                          'data-level': '4'
                        }
                      };

                      if (l4.gls && l4.gls.length > 0) {
                        l4.gls.forEach(function(gl) {
                          const glNode = {
                            id: 'gl_' + gl.gl_id,
                            text: `<span class="gl-code">${gl.gl_code || ''}</span>${gl.gl_acct_name || 'N/A'}${gl.gl_acct_name_kh ? ' / ' + gl.gl_acct_name_kh : ''}`,
                            icon: 'fas fa-file-invoice',
                            li_attr: {
                              'data-level': 'gl',
                              'data-gl-id': gl.gl_id,
                              'data-gl-code': gl.gl_code
                            }
                          };
                          l4Node.children.push(glNode);
                        });
                      }

                      l3Node.children.push(l4Node);
                    });
                  }

                  l2Node.children.push(l3Node);
                });
              }

              l1Node.children.push(l2Node);
            });
          }

          treeData.push(l1Node);
        });

        // Initialize jsTree
        $('#treeContainer').jstree('destroy'); // Destroy existing instance if any

        treeInstance = $('#treeContainer').jstree({
          'core': {
            'data': treeData,
            'themes': {
              'name': 'default',
              'responsive': true,
              'dots': true,
              'icons': true
            }
          },
          'plugins': ['search', 'types'],
          'search': {
            'show_only_matches': true,
            'show_only_matches_children': true
          },
          'types': {
            'default': {
              'icon': 'fas fa-folder'
            }
          }
        });

        // Event handlers
        $('#treeContainer').on('select_node.jstree', function(e, data) {
          console.log('Selected:', data.node);
          // You can add click handlers here
        });

        $('#treeContainer').on('ready.jstree', function() {
          // Tree is ready
          console.log('Tree loaded successfully');
        });
      }
    });
  </script>
@endpush

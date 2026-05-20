<!-- Stats Cards -->
<div class="row mb-4">
  <div class="col-12 col-sm-6 col-xl-3 mb-4">
    <div class="pt-stat-card pt-stat-card-blue rounded-3 p-4 text-white shadow pt-animate-slide-in">
      <div class="">
        <div>
          <h2 class="h3 mb-0 fw-bold">580.43K</h2>
          <p class="mb-0 mt-1 small opacity-75">{{ __('common.pagination.total_sales') }}</p>
        </div>
        <div class="">
          <i class=""></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3 mb-4">
    <div class="pt-stat-card pt-stat-card-pink rounded-3 p-4 text-white shadow pt-animate-slide-in"
      style="animation-delay: 0.1s;">
      <div class="">
        <div>
          <h2 class="h3 mb-0 fw-bold">478</h2>
          <p class="mb-0 mt-1 small opacity-75">{{ __('common.pagination.total_orders') }}</p>
        </div>
        <div class="">
          <i class=""></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3 mb-4">
    <div class="pt-stat-card pt-stat-card-orange rounded-3 p-4 text-white shadow pt-animate-slide-in"
      style="animation-delay: 0.2s;">
      <div class="">
        <div>
          <h2 class="h3 mb-0 fw-bold">140</h2>
          <p class="mb-0 mt-1 small opacity-75">{{ __('common.pagination.total_products') }}</p>
        </div>
        <div class="">
          <i class=""></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3 mb-4">
    <div class="pt-stat-card pt-stat-card-green rounded-3 p-4 text-white shadow pt-animate-slide-in"
      style="animation-delay: 0.3s;">
      <div class="">
        <div>
          <h2 class="h3 mb-0 fw-bold">45</h2>
          <p class="mb-0 mt-1 small opacity-75">{{ __('common.nav.total_customers') }}</p>
        </div>
        <div class="">
          <i class=""></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Data Table Card -->
<div class="card shadow-sm border-0 rounded-3 overflow-hidden">
  <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold">
      <i class="fas fa-shopping-bag me-2 text-primary"></i>Latest
      Orders
    </h5>
    <a href="#" class="btn btn-primary">
      <i class="fas fa-eye me-1"></i>View All
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table table-striped">
      <table class="table table-hover align-middle mb-0">
        <thead class="table table-striped">
          <tr>
            <th class="border-0 px-4 py-3 text-uppercase small fw-semibold text-muted">Order
              ID</th>
            <th class="border-0 py-3 text-uppercase small fw-semibold text-muted">{{ __('common.pagination.customer') }}</th>
            <th class="border-0 py-3 text-uppercase small fw-semibold text-muted">{{ __('common.general.status') }}</th>
            <th class="border-0 py-3 text-uppercase small fw-semibold text-muted">{{ __('common.pagination.total') }}</th>
            <th class="border-0 py-3 text-uppercase small fw-semibold text-muted">{{ __('common.general.date') }}</th>
            <th class="border-0 py-3 text-uppercase small fw-semibold text-muted text-end pe-4">{{ __('common.general.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="px-4 py-3">
              <span class="">#2346</span>
            </td>
            <td class="py-3">
              <div class="d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name=Demo+Admin&background=6366f1&color=fff&size=36"
                  class="rounded-circle me-2" width="36" height="36" alt="{{ __('common.general.avatar') }}">
                <div>
                  <div class="">{{ __('common.general.demo_admin') }}</div>
                  <small class="text-muted">demo@admin.com</small>
                </div>
              </div>
            </td>
            <td class="py-3">
              <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                <i class="fas fa-clock me-1"></i>Pending
              </span>
            </td>
            <td class="py-3 fw-semibold text-success">$42.05</td>
            <td class="py-3 text-muted">
              <i class="far fa-calendar-alt me-1"></i>Nov 25, 2025
            </td>
            <td class="py-3 text-end pe-4">
              <div class="">
                <button class="btn btn-primary" title="{{ __('common.general.view') }}"><i class=""></i></button>
                <button class="btn btn-primary" title="{{ __('common.general.edit') }}"><i class=""></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="px-4 py-3">
              <span class="">#2345</span>
            </td>
            <td class="py-3">
              <div class="d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name=Angelica+Dodson&background=ec4899&color=fff&size=36"
                  class="rounded-circle me-2" width="36" height="36" alt="{{ __('common.general.avatar') }}">
                <div>
                  <div class="">{{ __('common.general.angelica_dodson') }}</div>
                  <small class="text-muted">angelica@email.com</small>
                </div>
              </div>
            </td>
            <td class="py-3">
              <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                <i class="fas fa-clock me-1"></i>Pending
              </span>
            </td>
            <td class="py-3 fw-semibold text-success">$9.99</td>
            <td class="py-3 text-muted">
              <i class="far fa-calendar-alt me-1"></i>Nov 24, 2025
            </td>
            <td class="py-3 text-end pe-4">
              <div class="">
                <button class="btn btn-primary" title="{{ __('common.general.view') }}"><i class=""></i></button>
                <button class="btn btn-primary" title="{{ __('common.general.edit') }}"><i class=""></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="px-4 py-3">
              <span class="">#2344</span>
            </td>
            <td class="py-3">
              <div class="d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name=John+Smith&background=10b981&color=fff&size=36"
                  class="rounded-circle me-2" width="36" height="36" alt="{{ __('common.general.avatar') }}">
                <div>
                  <div class="">{{ __('common.general.john_smith') }}</div>
                  <small class="text-muted">john@email.com</small>
                </div>
              </div>
            </td>
            <td class="py-3">
              <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                <i class="fas fa-check-circle me-1"></i>Completed
              </span>
            </td>
            <td class="py-3 fw-semibold text-success">$125.50</td>
            <td class="py-3 text-muted">
              <i class="far fa-calendar-alt me-1"></i>Nov 23, 2025
            </td>
            <td class="py-3 text-end pe-4">
              <div class="">
                <button class="btn btn-primary" title="{{ __('common.general.view') }}"><i class=""></i></button>
                <button class="btn btn-primary" title="{{ __('common.general.edit') }}"><i class=""></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="px-4 py-3">
              <span class="">#2343</span>
            </td>
            <td class="py-3">
              <div class="d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=f59e0b&color=fff&size=36"
                  class="rounded-circle me-2" width="36" height="36" alt="{{ __('common.general.avatar') }}">
                <div>
                  <div class="">{{ __('common.general.sarah_johnson') }}</div>
                  <small class="text-muted">sarah@email.com</small>
                </div>
              </div>
            </td>
            <td class="py-3">
              <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                <i class="fas fa-check-circle me-1"></i>Completed
              </span>
            </td>
            <td class="py-3 fw-semibold text-success">$89.99</td>
            <td class="py-3 text-muted">
              <i class="far fa-calendar-alt me-1"></i>Nov 22, 2025
            </td>
            <td class="py-3 text-end pe-4">
              <div class="">
                <button class="btn btn-primary" title="{{ __('common.general.view') }}"><i class=""></i></button>
                <button class="btn btn-primary" title="{{ __('common.general.edit') }}"><i class=""></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="px-4 py-3">
              <span class="">#2342</span>
            </td>
            <td class="py-3">
              <div class="d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name=Michael+Brown&background=8b5cf6&color=fff&size=36"
                  class="rounded-circle me-2" width="36" height="36" alt="{{ __('common.general.avatar') }}">
                <div>
                  <div class="">{{ __('common.general.michael_brown') }}</div>
                  <small class="text-muted">michael@email.com</small>
                </div>
              </div>
            </td>
            <td class="py-3">
              <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                <i class="fas fa-clock me-1"></i>Pending
              </span>
            </td>
            <td class="py-3 fw-semibold text-success">$156.75</td>
            <td class="py-3 text-muted">
              <i class="far fa-calendar-alt me-1"></i>Nov 21, 2025
            </td>
            <td class="py-3 text-end pe-4">
              <div class="">
                <button class="btn btn-primary" title="{{ __('common.general.view') }}"><i class=""></i></button>
                <button class="btn btn-primary" title="{{ __('common.general.edit') }}"><i class=""></i></button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
    <small class="text-muted">Showing 5 of 24 orders</small>
    <nav>
      <ul class="pagination pagination-sm mb-0">
        <li class=""><a class="" href="#">{{ __('common.general.previous') }}</a></li>
        <li class=""><a class="" href="#">1</a></li>
        <li class=""><a class="" href="#">2</a></li>
        <li class=""><a class="" href="#">3</a></li>
        <li class=""><a class="" href="#">{{ __('common.general.next') }}</a></li>
      </ul>
    </nav>
  </div>
</div>

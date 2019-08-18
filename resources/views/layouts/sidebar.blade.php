<?php
/**
 * Sidebar Generator for Administrator
 */
$currentRoute = Route::currentRouteName();
$sidebar      = new \Revlv\Sidebar\SidebarGenerator($currentRoute);
?>


<div class="p-sidebar">
  <div class="c-branding">
    <div class="c-branding__logo">
      <button class="c-button c-button--circle js-hide-sidebar-button">
        <i class="nc-icon-mini ui-1_simple-remove"></i>
      </button>
      <img src="/img/logo.png" alt="">
    </div>
  </div>
  <div class="c-navlinks c-navlinks--vertical">
    @foreach($sidebar->getSidebar() as $group => $route)
        @if(explode('.', $currentRoute)[0] == $route->subname)
            <div class="c-navlinks__item c-navlinks__item--active">
              <a href="{{route($route->route)}}" class="c-navlinks__link">
                <span class="c-navlinks__icon"><i class="{{$route->icon}}"></i></span>
                <span class="c-navlinks__label">{{$route->name}}</span>
              </a>
                @if(explode('.', $currentRoute)[0] == $route->subname)

                    @foreach($route->navigation as $nav)
                        <div class="c-navlinks__child">
                          <div class="c-navlinks__item">
                            <a href="{{route($nav->route)}}" class="c-navlinks__link">{{$nav->name}}</a>
                          </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @else
            <div class="c-navlinks__item ">
              <a href="{{route($route->route)}}" class="c-navlinks__link">
                <span class="c-navlinks__icon"><i class="{{$route->icon}}"></i></span>
                <span class="c-navlinks__label">{{$route->name}}</span>
              </a>
            </div>
        @endif
    @endforeach
    @if(\Sentinel::getUser()->user_type == 'supplier')
    <?php 
      $user       =   \Sentinel::getUser();
          $suppliers  =   json_decode($user->suppliers);

          $resource   =   Revlv\Settings\Suppliers\SupplierEloquent::select([
              'supplier_attachments.name',
              'supplier_attachments.type',
              'supplier_attachments.issued_date',
              'supplier_attachments.validity_date',
              'supplier_attachments.ref_number',
              'supplier_attachments.place',
          ]);
          $resource   =   $resource->leftJoin('supplier_attachments', 'supplier_attachments.supplier_id', 'suppliers.id');
          $resource   =   $resource->whereIn('suppliers.id', $suppliers);
          $resource   =   $resource->orderBy('supplier_attachments.issued_date', 'asc');
          $resource   =   $resource->groupBy([
              'supplier_attachments.name',
              'supplier_attachments.type',
              'supplier_attachments.issued_date',
              'supplier_attachments.validity_date',
              'supplier_attachments.ref_number',
              'supplier_attachments.place',
          ]);
          $resource   =   $resource->get();
    ?>
        <div class="c-navlinks__item {{ (request()->route()->getName() == 'eligibilities.index') ? 'c-navlinks__item--active' : ''}}">
          <a href="{{route('eligibilities.index')}}" class="c-navlinks__link">
            <span class="c-navlinks__icon"><i class="nc-icon-mini files_drawer"></i></span>
            <span class="c-navlinks__label">
              Eligibilities 
            </span>
            {{-- <span class="c-badge u-pos-right">{{count($resource)}}</span> --}}
          </a>
        </div>
    @endif
  </div>
</div>
 
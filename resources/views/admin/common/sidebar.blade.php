<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <?php
        $check =  DB::table('manage_role')
                   ->where('user_types_id',Auth()->user()->role_id)
                   //->where('dashboard_view',1)
                   ->first();
        ?>
        <li class="header">{{ trans('labels.navigation') }}</li>
        @if(Auth::guard('saleadvisor')->check())
          @if(Auth::guard('saleadvisor')->user()->role_id == '17')
          <li class="treeview {{ Request::is('admin/sale_advisors/dashboard') ? 'active' : '' }}">
            <a href="{{ URL::to('admin/sale_advisors/dashboard')}}">
              <i class="fa fa-dashboard"></i> <span>{{ trans('labels.header_dashboard') }}</span>
            </a>
          </li>
                  <li class="treeview {{ Request::is('admin/sale_advisors/dashboard') ? 'active' : '' }}">
                      <a href="{{ URL::to('admin/sale_advisors/dashboard')}}">
                          <i class="fa fa-dashboard"></i> <span>{{ trans('labels.header_dashboard') }}</span>
                      </a>
                  </li>
                  <li class="treeview {{ Request::is('admin/sale_advisors/dashboard') ? 'active' : '' }}">
                    <a href="{{ URL::to('admin/sale_advisors/basic-info')}}">
                  <i class="fa fa-user"></i> <span>Basic Info</span>
            </a>
          </li>
          @else
          <li class="treeview {{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ URL::to('admin/dashboard/this_month')}}">
              <i class="fa fa-dashboard"></i> <span>{{ trans('labels.header_dashboard') }}</span>
            </a>
          </li>
          @endif
        @else
          <li class="treeview {{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ URL::to('admin/dashboard/this_month')}}">
              <i class="fa fa-dashboard"></i> <span>{{ trans('labels.header_dashboard') }}</span>
            </a>
          </li>
        @endif

      <?php

        if($check->language_view == 1){
      ?>

        <li class="treeview {{ Request::is('admin/languages/display') ? 'active' : '' }} {{ Request::is('admin/languages/add') ? 'active' : '' }} {{ Request::is('admin/languages/edit/*') ? 'active' : '' }} ">
          <a href="{{ URL::to('admin/languages/display')}}">
            <i class="fa fa-language" aria-hidden="true"></i> <span> {{ trans('labels.languages') }} </span>
          </a>
        </li>

      <?php } ?>
      <?php
        if($check->view_media == 1){
      ?>
      <li class="treeview {{ Request::is('admin/media/add') ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-picture-o"></i> <span>{{ trans('labels.media') }}</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview {{ Request::is('admin/media/add') ? 'active' : '' }} ">
              <a href="{{url('admin/media/add')}}">

                  <i class="fa fa-circle-o" aria-hidden="true"></i> <span> {{ trans('labels.media') }} </span>
              </a>
          </li>

          <li class="treeview {{ Request::is('admin/media/display') ? 'active' : '' }} {{ Request::is('admin/addimages') ? 'active' : '' }} {{ Request::is('admin/uploadimage/*') ? 'active' : '' }} ">
              <a href="{{url('admin/media/display')}}">

                  <i class="fa fa-circle-o" aria-hidden="true"></i> <span> {{ trans('labels.Media Setings') }} </span>
              </a>
          </li>

          <li class="treeview {{ Request::is('/file-manager/ckeditor') ? 'active' : '' }} ">
              <a href="{{ url('/file-manager/ckeditor') }}" target="_blank">
                  <i class="fa fa-circle-o" aria-hidden="true"></i> <span> File Manager </span>
              </a>
          </li>
          <li class="{{ Request::is('admin/agents') ? 'active' : '' }} {{ Request::is('admin/add-agents') ? 'active' : '' }} {{ Request::is('admin/edit-agent/*') ? 'active' : '' }}">
                  <a href="{{ URL::to('admin/agents')}}">
                      <i class="fa fa-circle-o"></i>
                      Agents
                  </a>
              </li>
        </ul>
      </li>

      <?php } ?>
      <?php
        if($check->make_view == 1){
      ?>
        <li class="treeview {{ Request::is('admin/make/display') ? 'active' : '' }} {{ Request::is('admin/make/add') ? 'active' : '' }} {{ Request::is('admin/make/edit/*') ? 'active' : '' }} ">
          <a href="{{ URL::to('admin/make/display')}}">
            <i class="fa fa-industry" aria-hidden="true"></i> <span>{{ trans('labels.link_make') }}</span>
          </a>
        </li>
      <?php } ?>
      <?php
        if($check->model_view == 1){
      ?>
        <li class="treeview {{ Request::is('admin/model/display') ? 'active' : '' }} {{ Request::is('admin/model/add') ? 'active' : '' }} {{ Request::is('admin/model/edit/*') ? 'active' : '' }} ">
          <a href="{{ URL::to('admin/model/display')}}">
            <i class="fa fa-database" aria-hidden="true"></i> <span>{{ trans('labels.link_model') }}</span>
          </a>
        </li>
      <?php } ?>
      <?php
         if($check->model_view == 1){
          ?>
            <li class="treeview {{ Request::is('admin/variant/display') ? 'active' : '' }} {{ Request::is('admin/variant/add') ? 'active' : '' }} {{ Request::is('admin/variant/edit/*') ? 'active' : '' }} ">
              <a href="{{ URL::to('admin/variant/display')}}">
                <i class="fa fa-database" aria-hidden="true"></i> <span>{{ trans('labels.link_variant') }}</span>
              </a>
            </li>
          <?php } ?>
          <?php
        if($check->state_view == 1){
      ?>
        <li class="treeview {{ Request::is('admin/state/display') ? 'active' : '' }} {{ Request::is('admin/state/add') ? 'active' : '' }} {{ Request::is('admin/state/edit/*') ? 'active' : '' }} ">
          <a href="{{ URL::to('admin/state/display')}}">
            <i class="fa fa-flag" aria-hidden="true"></i> <span>{{ trans('labels.link_state') }}</span>
          </a>
        </li>
      <?php } ?>
      <?php
        if($check->cities_view == 1){
      ?>
        <li class="treeview {{ Request::is('admin/cities/display') ? 'active' : '' }} {{ Request::is('admin/cities/add') ? 'active' : '' }} {{ Request::is('admin/cities/edit/*') ? 'active' : '' }} ">
          <a href="{{ URL::to('admin/cities/display')}}">
            <i class="fa fa-building-o" aria-hidden="true"></i> <span>{{ trans('labels.link_cities') }}</span>
          </a>
        </li>
      <?php } ?>
      <?php
        if($check->type_view == 1){
      ?>
        <li class="treeview {{ Request::is('admin/type/display') ? 'active' : '' }} {{ Request::is('admin/type/add') ? 'active' : '' }} {{ Request::is('admin/type/edit/*') ? 'active' : '' }} ">
          <a href="{{ URL::to('admin/type/display')}}">
            <i class="fa fa-file-archive-o" aria-hidden="true"></i> <span>{{ trans('labels.link_type') }}</span>
          </a>
        </li>
      <?php } ?>
      <?php
        if($check->car_view == 1 || $check->manage_merchants_view == 1){
      ?>
        <li class="treeview {{ Request::is('admin/car/display') ? 'active' : '' }} {{ Request::is('admin/car/add') ? 'active' : '' }} {{ Request::is('admin/car/edit/*') ? 'active' : '' }} {{ Request::is('admin/car/bulk-airtime/edit/*') ? 'active' : '' }} {{ Request::is('admin/itemattribute/display') ? 'active' : '' }} {{ Request::is('admin/itemtype/display') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-car"></i> <span>{{ trans('labels.linkItem') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/car/display') ? 'active' : '' }} {{ Request::is('admin/car/add') ? 'active' : '' }} {{ Request::is('admin/car/edit/*') ? 'active' : '' }}">
              <a href="{{ URL::to('admin/car/display')}}">
                <i class="fa fa-circle-o"></i> {{ trans('labels.linkSubItem') }} (Default)
              </a>
            </li>
            @php
              $item_type = DB::table('item_type')->get();
            @endphp
            @if($item_type)
              @foreach($item_type as $item_types)
              <li class="">
                <a href="{{ URL::to('admin/car/display/'.$item_types->id)}}">
                  <i class="fa fa-circle-o"></i> {{$item_types->name}}
                </a>
              </li>
              @endforeach
            @endif
            <?php
              if($check->manage_admins_view == 1){
            ?>
              <li class="{{ Request::is('admin/itemtype/display') ? 'active' : '' }} {{ Request::is('admin/itemtype/add') ? 'active' : '' }} {{ Request::is('admin/itemtype/edit/*') ? 'active' : '' }}">
                <a href="{{ URL::to('admin/itemtype/display')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.item_type') }}</a>
              </li>
              <li class="{{ Request::is('admin/itemattribute/display') ? 'active' : '' }}">
                <a href="{{ URL::to('admin/itemattribute/display')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.ItemAttribute_pageTitle') }}</a>
              </li>
              @if($check->manage_merchants_view == 1)
              <li class="{{ Request::is('admin/car/bulk-airtime/edit') ? 'active' : '' }}">
                <a href="{{ URL::to('admin/car/bulk-airtime/edit')}}">
                  <i class="fa fa-circle-o"></i> {{ trans('labels.link_bulk_edit_car_airtime') }}
                </a>
              </li>
              @endif
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php if($check->manage_merchants_view == 1){ ?>
        <!-- <li class="treeview {{ Request::is('admin/merchants') ? 'active' : '' }} {{ Request::is('admin/addmerchants') ? 'active' : '' }} {{ Request::is('admin/editmerchant/*') ? 'active' : '' }} {{ Request::is('admin/merchants/branch/display/*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-car"></i> <span>{{ trans('labels.link_merchants') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/merchants') ? 'active' : '' }} {{ Request::is('admin/addmerchants') ? 'active' : '' }} {{ Request::is('admin/editmerchant/*') ? 'active' : '' }}">
              <a href="{{ URL::to('admin/merchants')}}">
                  <i class="fa fa-users"></i>
                  {{ trans('labels.Organisation') }}
              </a>
            </li>
            <li class="{{ Request::is('admin/merchants/branch/display/*') ? 'active' : '' }}">
              <a href="{{ URL::to('admin/merchants/branch/display/*')}}">
                  <i class="fa fa-users"></i>
                  {{ trans('labels.SaleAdvisor') }}
              </a>
            </li>
          </ul>
        </li> -->
      
      <li class="{{ Request::is('admin/merchants') ? 'active' : '' }} {{ Request::is('admin/addmerchants') ? 'active' : '' }} {{ Request::is('admin/editmerchant/*') ? 'active' : '' }}">
        <a href="{{ URL::to('admin/merchants')}}">
            <i class="fa fa-users"></i>
            {{ trans('labels.Organisation') }}
        </a>
      </li>
      <li class="{{ Request::is('admin/saleAdvisor/*') ? 'active' : '' }}">
        <a href="{{ URL::to('admin/saleAdvisor/*')}}">
            <i class="fa fa-users"></i>
            {{ trans('labels.SaleAdvisor') }}
        </a>
      </li>

      <?php } ?>

      <?php
        if($check->manufacturer_view == 1){
      ?>
        <li class="treeview {{ Request::is('admin/manufacturers/display') ? 'active' : '' }} {{ Request::is('admin/manufacturers/add') ? 'active' : '' }} {{ Request::is('admin/manufacturers/edit/*') ? 'active' : '' }} ">
          <a href="{{ URL::to('admin/manufacturers/display')}}">
            <i class="fa fa-industry" aria-hidden="true"></i> <span>{{ trans('labels.link_manufacturer') }}</span>
          </a>
        </li>
      <?php } ?>
      <?php
        if($check->products_view == 1 or $check->categories_view == 1 ){
      ?>
        <li class="treeview {{ Request::is('admin/products/display') ? 'active' : '' }} {{ Request::is('admin/products/add') ? 'active' : '' }} {{ Request::is('admin/products/edit/*') ? 'active' : '' }} {{ Request::is('admin/editattributes/*') ? 'active' : '' }} {{ Request::is('admin/products/attributes/display') ? 'active' : '' }}  {{ Request::is('admin/products/attributes/add') ? 'active' : '' }} {{ Request::is('admin/products/attributes/add/*') ? 'active' : '' }} {{ Request::is('admin/addinventory/*') ? 'active' : '' }} {{ Request::is('admin/addproductimages/*') ? 'active' : '' }} {{ Request::is('admin/categories/display') ? 'active' : '' }} {{ Request::is('admin/categories/add') ? 'active' : '' }} {{ Request::is('admin/categories/edit/*') ? 'active' : '' }} {{ Request::is('admin/categories/filter') ? 'active' : '' }} {{ Request::is('admin/products/inventory/display') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-database"></i> <span>{{ trans('labels.link_products') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">

            @if ($check->categories_view == 1)
              <li class="{{ Request::is('admin/categories/display') ? 'active' : '' }} {{ Request::is('admin/categories/add') ? 'active' : '' }} {{ Request::is('admin/categories/edit/*') ? 'active' : '' }} {{ Request::is('admin/categories/filter') ? 'active' : '' }}"><a href="{{ URL::to('admin/categories/display')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_main_categories') }}</a></li>
            @endif

            @if ($check->products_view == 1)
              <li class="{{ Request::is('admin/products/display') ? 'active' : '' }} {{ Request::is('admin/products/add') ? 'active' : '' }} {{ Request::is('admin/products/edit/*') ? 'active' : '' }} {{ Request::is('admin/products/attributes/add/*') ? 'active' : '' }} {{ Request::is('admin/addinventory/*') ? 'active' : '' }} {{ Request::is('admin/addproductimages/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/products/display')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_all_products') }}</a></li>
            @endif
            @if ($check->products_view == 1)
              <li class="{{ Request::is('admin/products/attributes/display') ? 'active' : '' }}  {{ Request::is('admin/products/attributes/add') ? 'active' : '' }}  {{ Request::is('admin/products/attributes/*') ? 'active' : '' }}" ><a href="{{ URL::to('admin/products/attributes/display' )}}"><i class="fa fa-circle-o"></i> {{ trans('labels.products_attributes') }}</a></li>
              <li class="{{ Request::is('admin/products/inventory/display') ? 'active' : '' }}"><a href="{{ URL::to('admin/products/inventory/display')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.inventory') }}</a></li>
            @endif
          </ul>
        </li>
      <?php } ?>
      <?php

        if($check->news_view == 1){
      ?>
        <li class="treeview {{ Request::is('admin/newscategories/display') ? 'active' : '' }} {{ Request::is('admin/newscategories/add') ? 'active' : '' }} {{ Request::is('admin/newscategories/edit/*') ? 'active' : '' }} {{ Request::is('admin/news/display') ? 'active' : '' }}  {{ Request::is('admin/news/add') ? 'active' : '' }}  {{ Request::is('admin/news/edit/*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-database" aria-hidden="true"></i>
<span>      {{ trans('labels.link_news') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
          	<li class="{{ Request::is('admin/newscategories/display') ? 'active' : '' }} {{ Request::is('admin/newscategories/add') ? 'active' : '' }} {{ Request::is('admin/newscategories/edit/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/newscategories/display')}}"><i class="fa fa-circle-o"></i>{{ trans('labels.link_news_categories') }}</a></li>
            <li class="{{ Request::is('admin/news/display') ? 'active' : '' }}  {{ Request::is('admin/news/add') ? 'active' : '' }}  {{ Request::is('admin/news/edit/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/news/display')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_sub_news') }}</a></li>
          </ul>
        </li>
      <?php } ?>
      <?php
        if($check->customers_view == 1){
      ?>
        <li class="treeview {{ Request::is('admin/customers/display') ? 'active' : '' }}  {{ Request::is('admin/customers/add') ? 'active' : '' }}  {{ Request::is('admin/customers/edit/*') ? 'active' : '' }} {{ Request::is('admin/customers/address/display/*') ? 'active' : '' }} {{ Request::is('admin/customers/filter') ? 'active' : '' }} ">
          <a href="{{ URL::to('admin/customers/display')}}">
            <i class="fa fa-users" aria-hidden="true"></i> <span>{{ trans('labels.link_customers') }}</span>
          </a>
        </li>
      <?php } ?>

      @if(Auth::guard('saleadvisor')->check())

      @else
      <?php
        if($check->view_promotion == 1){
      ?>
        <li class="treeview {{ Request::is('admin/promotions') ? 'active' : '' }}  {{ Request::is('admin/promotion/add') ? 'active' : '' }}  {{ Request::is('admin/promotion/edit/*') ? 'active' : '' }}  ">
          <a href="{{ URL::to('admin/promotions')}}">
            <i class="fa fa-users" aria-hidden="true"></i> <span>{{ trans('labels.link_promotion') }}</span>
          </a>
        </li>
      <?php } ?>
      @endif

      @if(Auth::guard('saleadvisor')->check())
        <?php
          if($check->view_campaign == 1){
        ?>
          <li class="treeview {{ Request::is('admin/sale_advisors/campaigns') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/addcampaigns') ? 'active' : '' }}  {{ Request::is('admin/sale_advisors/editcampaigns/*') ? 'active' : '' }} ">
            <a href="{{ URL::to('admin/sale_advisors/campaigns')}}">
              <i class="fa fa-users" aria-hidden="true"></i> <span>{{ trans('labels.link_campaign') }}</span>
            </a>
          </li>
        <?php } ?>
      @else
        <?php
          if($check->view_campaign == 1){
        ?>
          <li class="treeview {{ Request::is('admin/campaigns') ? 'active' : '' }} {{ Request::is('admin/addcampaigns') ? 'active' : '' }}  {{ Request::is('admin/editcampaigns/*') ? 'active' : '' }} ">
            <a href="{{ URL::to('admin/campaigns')}}">
              <i class="fa fa-users" aria-hidden="true"></i> <span>{{ trans('labels.link_campaign') }}</span>
            </a>
          </li>
        <?php } ?>
      @endif

        <?php 
          // if(Auth()->user()->role_id != \App\Models\Core\User::ROLE_MERCHANT || auth()->user()->report_view == 1 || Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
            if($check->view_report_organisation == 1 || $check->view_report_sa == 1 || $check->view_report_item == 1 || $check->view_report_promotion == 1 || $check->view_report_campaign == 1 || $check->view_report_campaign_response == 1){
        ?>
        <li class="treeview {{ Request::is('admin/organisationreport') ? 'active' : '' }} {{ Request::is('admin/salesreport') ? 'active' : '' }} {{ Request::is('admin/promotionreport') ? 'active' : '' }} {{ Request::is('admin/itemreport') ? 'active' : '' }} {{ Request::is('admin/filterorganisationreport') ? 'active' : '' }} {{ Request::is('admin/filtersalesreport') ? 'active' : '' }} {{ Request::is('admin/filteritemreport') ? 'active' : '' }} {{ Request::is('admin/filterpromotionreport') ? 'active' : '' }} {{ Request::is('admin/campaignreport') ? 'active' : '' }} {{ Request::is('admin/filtercampaignreport') ? 'active' : '' }} {{ Request::is('admin/campaignfullreport') ? 'active' : '' }} {{ Request::is('admin/filtercampaignfullreport') ? 'active' : '' }} {{ Request::is('admin/campaignsresponse') ? 'active' : '' }} {{ Request::is('admin/filtercampaignsresponse') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/campaignreport') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/filtercampaignreport') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/campaignfullreport') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/filtercampaignfullreport') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/campaignsresponse') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/filtercampaignsresponse') ? 'active' : '' }}{{ Request::is('admin/sale_advisors/salesreport') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/filtersalesreport') ? 'active' : '' }}{{ Request::is('admin/sale_advisors/itemreport') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/filteritemreport') ? 'active' : '' }}{{ Request::is('admin/sale_advisors/promotionreport') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/filterpromotionreport') ? 'active' : '' }}">
            <a href="#">
              <i class="fa fa-money" aria-hidden="true"></i>
              <span>{{ trans('labels.linkReport') }}</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php
                if($check->view_report_organisation == 1){
              ?>
              @if(Auth::guard('saleadvisor')->check())
                
                @else
                <li class="{{ Request::is('admin/organisationreport') ? 'active' : '' }} {{ Request::is('admin/filterorganisationreport') ? 'active' : '' }} "><a href="{{ URL::to('admin/organisationreport')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkSubOrg') }}</a></li>
                @endif
              
              <?php 
                }
                if($check->view_report_sa == 1){
              ?>
                @if(Auth::guard('saleadvisor')->check())
                <li class="{{ Request::is('admin/sale_advisors/salesreport') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/filtersalesreport') ? 'active' : '' }}"><a href="{{ URL::to('admin/sale_advisors/salesreport')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkSubSales') }}</a></li>
                @else
                <li class="{{ Request::is('admin/salesreport') ? 'active' : '' }} {{ Request::is('admin/filtersalesreport') ? 'active' : '' }}"><a href="{{ URL::to('admin/salesreport')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkSubSales') }}</a></li>
                @endif
                
              <?php
                }
                if($check->view_report_item == 1){
              ?>
              @if(Auth::guard('saleadvisor')->check())
                <li class="{{ Request::is('admin/sale_advisors/itemreport') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/filteritemreport') ? 'active' : '' }}"><a href="{{ URL::to('admin/sale_advisors/itemreport')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkSubItem') }}</a></li>
                @else
                <li class="{{ Request::is('admin/itemreport') ? 'active' : '' }} {{ Request::is('admin/filteritemreport') ? 'active' : '' }}"><a href="{{ URL::to('admin/itemreport')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkSubItem') }}</a></li>
                @endif
              
              <?php
                }
                if($check->view_report_promotion == 1){
              ?>
              @if(Auth::guard('saleadvisor')->check())
              <li class="{{ Request::is('admin/sale_advisors/promotionreport') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/filterpromotionreport') ? 'active' : '' }}"><a href="{{ URL::to('admin/sale_advisors/promotionreport')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkSubPromo') }}</a></li>
                @else
                <li class="{{ Request::is('admin/promotionreport') ? 'active' : '' }} {{ Request::is('admin/filterpromotionreport') ? 'active' : '' }}"><a href="{{ URL::to('admin/promotionreport')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkSubPromo') }}</a></li>
                @endif
              
              <?php 
                }
              ?>
              <?php
                if($check->view_report_campaign == 1){
              ?>
              @if(Auth::guard('saleadvisor')->check())
                <li class="{{ Request::is('admin/sale_advisors/campaignreport') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/filtercampaignreport') ? 'active' : '' }}"><a href="{{ URL::to('admin/sale_advisors/campaignreport')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkSubCampaign') }}</a></li>
              @else
                <li class="{{ Request::is('admin/campaignreport') ? 'active' : '' }} {{ Request::is('admin/filtercampaignreport') ? 'active' : '' }}"><a href="{{ URL::to('admin/campaignreport')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkSubCampaign') }}</a></li>
              @endif
              
              <?php
                }
                if($check->view_report_campaign_response == 1){
              ?>
              @if(Auth::guard('saleadvisor')->check())
                <li class="{{ Request::is('admin/sale_advisors/campaignsresponse') ? 'active' : '' }} {{ Request::is('admin/sale_advisors/filtercampaignsresponse') ? 'active' : '' }}"><a href="{{ URL::to('admin/sale_advisors/campaignsresponse')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkSubCampaignResponse') }}</a></li>
              @else
                <li class="{{ Request::is('admin/campaignsresponse') ? 'active' : '' }} {{ Request::is('admin/filtercampaignsresponse') ? 'active' : '' }}"><a href="{{ URL::to('admin/campaignsresponse')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkSubCampaignResponse') }}</a></li>
              @endif
              
              <?php 
                }
              ?>
            </ul>
          </li>
          <?php 
          }
          ?>

      <?php
          if($check->tax_location_view == 1){
        ?>
          <li class="treeview {{ Request::is('admin/countries/display') ? 'active' : '' }} {{ Request::is('admin/countries/add') ? 'active' : '' }} {{ Request::is('admin/countries/edit/*') ? 'active' : '' }} {{ Request::is('admin/zones/display') ? 'active' : '' }} {{ Request::is('admin/zones/add') ? 'active' : '' }} {{ Request::is('admin/zones/eidt/*') ? 'active' : '' }} {{ Request::is('admin/tax/taxclass/display') ? 'active' : '' }} {{ Request::is('admin/tax/taxclass/add') ? 'active' : '' }} {{ Request::is('admin/tax/taxclass/edit/*') ? 'active' : '' }} {{ Request::is('admin/tax/taxrates/display') ? 'active' : '' }} {{ Request::is('admin/tax/taxrates/add') ? 'active' : '' }} {{ Request::is('admin/tax/taxrates/edit/*') ? 'active' : '' }}">
            <a href="#">
              <i class="fa fa-money" aria-hidden="true"></i>
              <span>{{ trans('labels.link_tax_location') }}</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li class="{{ Request::is('admin/countries/display') ? 'active' : '' }} {{ Request::is('admin/countries/add') ? 'active' : '' }} {{ Request::is('admin/countries/edit/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/countries/display')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_countries') }}</a></li>
              <li class="{{ Request::is('admin/zones/display') ? 'active' : '' }} {{ Request::is('admin/zones/add') ? 'active' : '' }} {{ Request::is('admin/zones/eidt/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/zones/display')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_zones') }}</a></li>
              <li class="{{ Request::is('admin/tax/taxclass/display') ? 'active' : '' }} {{ Request::is('admin/tax/taxclass/add') ? 'active' : '' }} {{ Request::is('admin/tax/taxclass/edit/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/tax/taxclass/display')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_tax_class') }}</a></li>
              <li class="{{ Request::is('admin/tax/taxrates/display') ? 'active' : '' }} {{ Request::is('admin/tax/taxrates/add') ? 'active' : '' }} {{ Request::is('admin/tax/taxrates/edit/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/tax/taxrates/display')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_tax_rates') }}</a></li>
              </ul>
          </li>
        <?php } ?>
        <?php
          if($check->coupons_view ==1){
        ?>
        <li class="treeview {{ Request::is('admin/coupons/display') ? 'active' : '' }} {{ Request::is('admin/editcoupons/*') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/coupons/display')}}" ><i class="fa fa-tablet" aria-hidden="true"></i> <span>{{ trans('labels.link_coupons') }}</span></a>
        </li>
      <?php } ?>
      @if($check->notifications_view == 1)
      <li class="treeview {{ Request::is('admin/devices/display') ? 'active' : '' }} {{ Request::is('admin/devices/viewdevices/*') ? 'active' : '' }} {{ Request::is('admin/devices/notifications') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/devices/display')}} ">
            <i class="fa fa-bell-o" aria-hidden="true"></i>
<span>{{ trans('labels.link_notifications') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/devices/display') ? 'active' : '' }} {{ Request::is('admin/devices/viewdevices/*') ? 'active' : '' }}">
          		<a href="{{ URL::to('admin/devices/display')}}"><i class="fa fa-circle-o"></i>{{ trans('labels.link_devices') }} </a>
            </li>
            <li class="{{ Request::is('admin/devices/notifications') ? 'active' : '' }} ">
            	<a href="{{ URL::to('admin/devices/notifications') }}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_send_notifications') }}</a>
            </li>
          </ul>
        </li>
        @endif
      <?php

        if($check->orders_view == 1){
      ?>
        <li class="treeview {{ Request::is('admin/orders/display') ? 'active' : '' }} {{ Request::is('admin/orders/vieworder/*') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/orders/display')}}" ><i class="fa fa-list-ul" aria-hidden="true"></i> <span> {{ trans('labels.link_orders') }}</span>
          </a>
        </li>
      <?php } ?>
      <?php

        if($check->shipping_methods_view == 1){
      ?>
        <li class="treeview {{ Request::is('admin/shippingmethods/display') ? 'active' : '' }} {{ Request::is('admin/shippingmethods/upsShipping/display') ? 'active' : '' }} {{ Request::is('admin/shippingmethods/flateRate/display') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/shippingmethods/display')}}"><i class="fa fa-truck" aria-hidden="true"></i> <span> {{ trans('labels.link_shipping_methods') }}</span>
          </a>
        </li>
          <?php } ?>
          <?php
            if($check->payment_methods_view == 1){
          ?>
            <li class="treeview {{ Request::is('admin/paymentmethods/index') ? 'active' : '' }}">
              <a  href="{{ URL::to('admin/paymentmethods/index')}}"><i class="fa fa-credit-card" aria-hidden="true"></i> <span>
              {{ trans('labels.link_payment_methods') }}</span>
              </a>
            </li>
          <?php }
            if(Auth()->user()->role_id != \App\Models\Core\User::ROLE_MERCHANT || auth()->user()->report_view == 1){
          ?>
          @if(Auth::guard('saleadvisor')->check())

          @else
            @if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT)

            @else
              <li class="treeview {{ Request::is('admin/statistics') ? 'active' : '' }}">
                <a href="#">
                  <i class="fa fa-file-text-o" aria-hidden="true"></i>
                    <span>{{ trans('labels.Statistics Report') }}</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li class="{{ Request::is('admin/statistics') && Request::get('type') == "car" ? 'active' : '' }} "><a href="{{ URL::to('admin/statistics?type=car')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.Popular Cars') }}</a></li>
                  <li class="{{ Request::is('admin/statistics') && Request::get('type') == "brand" ? 'active' : '' }} "><a href="{{ URL::to('admin/statistics?type=brand')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.Popular Brands') }}</a></li>
                  <li class="{{ Request::is('admin/statistics') && Request::get('type') == "model" ? 'active' : '' }} "><a href="{{ URL::to('admin/statistics?type=model')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.Popular Models') }}</a></li>
                  <li class="{{ Request::is('admin/statistics') && Request::get('type') == "type" ? 'active' : '' }} "><a href="{{ URL::to('admin/statistics?type=type')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.Popular Types') }}</a></li>
                  <li class="{{ Request::is('admin/statistics') && Request::get('type') == "state" ? 'active' : '' }} "><a href="{{ URL::to('admin/statistics?type=state')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.Popular States') }}</a></li>
                  <li class="{{ Request::is('admin/statistics') && Request::get('type') == "city" ? 'active' : '' }} "><a href="{{ URL::to('admin/statistics?type=city')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.Popular Cities') }}</a></li>
                  <li class="{{ Request::is('admin/statistics') && Request::get('type') == "price" ? 'active' : '' }} "><a href="{{ URL::to('admin/statistics?type=price')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.Popular Price Range') }}</a></li>
                </ul>
              </li>
            @endif
          @endif
          
          <?php
            }
            if($check->reports_view == 1){
          ?>
        <li class="treeview {{ Request::is('admin/statscustomers') ? 'active' : '' }} {{ Request::is('admin/outofstock') ? 'active' : '' }} {{ Request::is('admin/statsproductspurchased') ? 'active' : '' }} {{ Request::is('admin/statsproductsliked') ? 'active' : '' }} {{ Request::is('admin/lowinstock') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-file-text-o" aria-hidden="true"></i>
  <span>{{ trans('labels.link_reports') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/lowinstock') ? 'active' : '' }} "><a href="{{ URL::to('admin/lowinstock')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_products_low_stock') }}</a></li>
            <li class="{{ Request::is('admin/outofstock') ? 'active' : '' }} "><a href="{{ URL::to('admin/outofstock')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_out_of_stock_products') }}</a></li>
           <!-- <li class="{{ Request::is('admin/productsstock') ? 'active' : '' }} "><a href="{{ URL::to('admin/stockin')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.stockin') }}</a></li>-->
            <li class="{{ Request::is('admin/statscustomers') ? 'active' : '' }} "><a href="{{ URL::to('admin/statscustomers')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_customer_orders_total') }}</a></li>
            <li class="{{ Request::is('admin/statsproductspurchased') ? 'active' : '' }}"><a href="{{ URL::to('admin/statsproductspurchased')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_total_purchased') }}</a></li>
            <li class="{{ Request::is('admin/statsproductsliked') ? 'active' : '' }}"><a href="{{ URL::to('admin/statsproductsliked')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_products_liked') }}</a></li>
          </ul>
        </li>
      <?php } ?>
      <?php

      $route =  DB::table('settings')
                 ->where('name','is_web_purchased')
                 ->where('value', 1)
                 ->first();
        if($check->website_setting_view == 1 and $route != null){
      ?>

        <li class="treeview {{ Request::is('admin/googlesettings') ? 'active' : '' }} {{ Request::is('admin/facebooksettings') ? 'active' : '' }} {{ Request::is('admin/sliders') ? 'active' : '' }} {{ Request::is('admin/addsliderimage') ? 'active' : '' }} {{ Request::is('admin/editslide/*') ? 'active' : '' }} {{ Request::is('admin/webpages') ? 'active' : '' }}  {{ Request::is('admin/addwebpage') ? 'active' : '' }}  {{ Request::is('admin/editwebpage/*') ? 'active' : '' }} {{ Request::is('admin/websettings') ? 'active' : '' }} {{ Request::is('admin/webthemes') ? 'active' : '' }} {{ Request::is('admin/customstyle') ? 'active' : '' }} {{ Request::is('admin/constantbanners') ? 'active' : '' }} {{ Request::is('admin/addconstantbanner') ? 'active' : '' }} {{ Request::is('admin/editconstantbanner/*') ? 'active' : '' }} {{ Request::is('admin/templates') ? 'active' : '' }} {{ Request::is('admin/addtemplates') ? 'active' : '' }} {{ Request::is('admin/edittemplate/*') ? 'active' : '' }} {{ Request::is('admin/banks') ? 'active' : '' }} {{ Request::is('admin/addbanks') ? 'active' : '' }} {{ Request::is('admin/editbanks/*') ? 'active' : '' }} {{ Request::is('admin/segments') ? 'active' : '' }} {{ Request::is('admin/addsegments') ? 'active' : '' }} {{ Request::is('admin/editsegments/*') ? 'active' : '' }} {{ Request::is('admin/emailtemplate') ? 'active' : '' }}{{ Request::is('admin/campaignemailtemplate') ? 'active' : '' }}" >
          <a href="#">
            <i class="fa fa-gears" aria-hidden="true"></i>
  <span> {{ trans('labels.link_site_settings') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <!--li class="treeview {{ Request::is('admin/media/add') ? 'active' : '' }}">
              <a href="#">
                <i class="fa fa-picture-o"></i> <span>{{ trans('labels.Theme Setting') }}</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="treeview {{ Request::is('admin/theme/setting') ? 'active' : '' }} ">
                    <a href="{{url('admin/webPagesSettings')}}/1">
                        <i class="fa fa-picture-o" aria-hidden="true"></i> <span> {{ trans('labels.Home Page') }} </span>
                    </a>
                </li>
                <li class="treeview {{ Request::is('admin/theme/setting') ? 'active' : '' }} ">
                    <a href="{{url('admin/webPagesSettings')}}/4">
                        <i class="fa fa-picture-o" aria-hidden="true"></i> <span> Product Page Settings </span>
                    </a>
                </li>
                <li class="treeview {{ Request::is('admin/theme/setting') ? 'active' : '' }} ">
                    <a href="{{url('admin/webPagesSettings')}}/5">
                        <i class="fa fa-picture-o" aria-hidden="true"></i> <span> Shop Page Settings </span>
                    </a>
                </li>
                <li class="treeview {{ Request::is('admin/theme/setting') ? 'active' : '' }} ">
                    <a href="{{url('admin/webPagesSettings')}}/2">
                        <i class="fa fa-picture-o" aria-hidden="true"></i> <span> Cart Page Settings </span>
                    </a>
                </li>
                <li class="treeview {{ Request::is('admin/theme/setting') ? 'active' : '' }} ">
                    <a href="{{url('admin/webPagesSettings')}}/6">
                        <i class="fa fa-picture-o" aria-hidden="true"></i> <span> Contact Page Settings</span>
                    </a>
                </li>
                <li class="treeview {{ Request::is('admin/theme/setting') ? 'active' : '' }} ">
                    <a href="{{url('admin/webPagesSettings')}}/7">
                        <i class="fa fa-picture-o" aria-hidden="true"></i> <span> Colors Settings</span>
                    </a>
                </li>
              </ul>
            </li-->
            <!--li class="{{ Request::is('admin/constantbanners') ? 'active' : '' }} {{ Request::is('admin/constantbanners') ? 'active' : '' }} {{ Request::is('admin/constantbanners/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/constantbanners')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_Banners') }}</a></li-->

            <li class="{{ Request::is('admin/sliders') ? 'active' : '' }} {{ Request::is('admin/addsliderimage') ? 'active' : '' }} {{ Request::is('admin/editslide/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/sliders')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_Sliders') }}</a></li>

            @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_SUPER_ADMIN || Auth()->user()->role_id == \App\Models\Core\User::ROLE_NORMAL_ADMIN )
            <li class="{{ Request::is('admin/facebooksettings') ? 'active' : '' }}"><a href="{{ URL::to('admin/facebooksettings')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_facebook') }}</a></li>

            <li class="{{ Request::is('admin/googlesettings') ? 'active' : '' }}"><a href="{{ URL::to('admin/googlesettings')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_google') }}</a></li>

            <li class="{{ Request::is('admin/templates') ? 'active' : '' }} {{ Request::is('admin/addtemplates') ? 'active' : '' }} {{ Request::is('admin/edittemplate/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/templates')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_template') }}</a></li>

            <li class="{{ Request::is('admin/emailtemplate') ? 'active' : '' }} {{ Request::is('admin/emailtemplate') ? 'active' : '' }} {{ Request::is('admin/emailtemplate') ? 'active' : '' }}"><a href="{{ URL::to('admin/emailtemplate')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkPromoEmail') }}</a></li>

            <li class="{{ Request::is('admin/campaignemailtemplate') ? 'active' : '' }} "><a href="{{ URL::to('admin/campaignemailtemplate')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.linkCampaignEmail') }}</a></li>

            <li class="{{ Request::is('admin/banks') ? 'active' : '' }} {{ Request::is('admin/addbanks') ? 'active' : '' }} {{ Request::is('admin/editbanks/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/banks')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.Bank') }}</a></li>

            <li class="{{ Request::is('admin/segments') ? 'active' : '' }} {{ Request::is('admin/addsegments') ? 'active' : '' }} {{ Request::is('admin/editsegments/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/segments')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.Segment') }}</a></li>
            @endif

            <!--li class="{{ Request::is('admin/webpages') ? 'active' : '' }}  {{ Request::is('admin/addwebpage') ? 'active' : '' }}  {{ Request::is('admin/editwebpage/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/webpages')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.content_pages') }}</a></li-->

            <!-- <li class="{{ Request::is('admin/webthemes') ? 'active' : '' }} "><a href="{{ URL::to('admin/webthemes')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.website_themes') }}</a></li> -->

            <!--li class="{{ Request::is('admin/seo') ? 'active' : '' }} "><a href="{{ URL::to('admin/seo')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.seo content') }}</a></li-->

            <!--li class="{{ Request::is('admin/customstyle') ? 'active' : '' }} "><a href="{{ URL::to('admin/customstyle')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.custom_style_js') }}</a></li-->

            <!--li class="{{ Request::is('admin/websettings') ? 'active' : '' }}"><a href="{{ URL::to('admin/websettings')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_setting') }}</a></li-->
          </ul>
        </li>
      <?php } ?>
      <?php
      $route =  DB::table('settings')
                 ->where('name','is_app_purchased')
                 ->where('value', 1)
                 ->first();

        if($check->application_setting_view == 1 and $route != null){
      ?>

        <li class="treeview {{ Request::is('admin/banners') ? 'active' : '' }} {{ Request::is('admin/addbanner') ? 'active' : '' }} {{ Request::is('admin/editbanner/*') ? 'active' : '' }} {{ Request::is('admin/pages') ? 'active' : '' }}  {{ Request::is('admin/addpage') ? 'active' : '' }}  {{ Request::is('admin/editpage/*') ? 'active' : '' }}  {{ Request::is('admin/appSettings') ? 'active' : '' }} {{ Request::is('admin/admobSettings') ? 'active' : '' }} {{ Request::is('admin/applabel') ? 'active' : '' }} {{ Request::is('admin/addappkey') ? 'active' : '' }} {{ Request::is('admin/applicationapi') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-gears" aria-hidden="true"></i>
  <span> {{ trans('labels.link_app_settings') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/banners') ? 'active' : '' }} {{ Request::is('admin/addbanner') ? 'active' : '' }} {{ Request::is('admin/editbanner/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/banners')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_Banners') }}</a></li>

            <li class="{{ Request::is('admin/pages') ? 'active' : '' }}  {{ Request::is('admin/addpage') ? 'active' : '' }}  {{ Request::is('admin/editpage/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/pages')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.content_pages') }}</a></li>

            <li class="{{ Request::is('admin/admobSettings') ? 'active' : '' }}"><a href="{{ URL::to('admin/admobSettings')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_admob') }}</a></li>

            <li class="android-hide {{ Request::is('admin/applabel') ? 'active' : '' }} {{ Request::is('admin/addappkey') ? 'active' : '' }}"><a href="{{ URL::to('admin/applabel')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.labels') }}</a></li>

            <li class="{{ Request::is('admin/applicationapi') ? 'active' : '' }}"><a href="{{ URL::to('admin/applicationapi')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.applicationApi') }}</a></li>

            <li class="{{ Request::is('admin/appsettings') ? 'active' : '' }}"><a href="{{ URL::to('admin/appsettings')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_setting') }}</a></li>

          </ul>
        </li>
      <?php } ?>
      <?php
        if($check->general_setting_view == 1){
      ?>

        <li class="treeview  {{ Request::is('admin/setting') ? 'active' : '' }} {{ Request::is('admin/pushnotification') ? 'active' : '' }} {{ Request::is('admin/orderstatus') ? 'active' : '' }} {{ Request::is('admin/addorderstatus') ? 'active' : '' }} {{ Request::is('admin/editorderstatus/*') ? 'active' : '' }} {{ Request::is('admin/alertsetting') ? 'active' : '' }} {{ Request::is('admin/units') ? 'active' : '' }} {{ Request::is('admin/addunit') ? 'active' : '' }} {{ Request::is('admin/editunit/*') ? 'active' : '' }} {{ Request::is('admin/currencies/display') ? 'active' : '' }} {{ Request::is('admin/currencies/add') ? 'active' : '' }} {{ Request::is('admin/currencies/edit/*') ? 'active' : '' }} {{ Request::is('admin/currencies/filter') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-gears" aria-hidden="true"></i>
  <span> {{ trans('labels.link_general_settings') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/units') ? 'active' : '' }} {{ Request::is('admin/addunit') ? 'active' : '' }} {{ Request::is('admin/editunit/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/units')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_units') }}</a></li>
            <li class="{{ Request::is('admin/orderstatus') ? 'active' : '' }} {{ Request::is('admin/addorderstatus') ? 'active' : '' }} {{ Request::is('admin/editorderstatus/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/orderstatus')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_order_status') }}</a></li>
            <li class="{{ Request::is('admin/pushnotification') ? 'active' : '' }}"><a href="{{ URL::to('admin/pushnotification')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_push_notification') }}</a></li>
            <li class="{{ Request::is('admin/alertsetting') ? 'active' : '' }}"><a href="{{ URL::to('admin/alertsetting')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.alertSetting') }}</a></li>
            <li class="{{ Request::is('admin/setting') ? 'active' : '' }}"><a href="{{ URL::to('admin/setting')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_setting') }}</a></li>
            <li class="{{ Request::is('admin/currencies/display') ? 'active' : '' }} {{ Request::is('admin/currencies/add') ? 'active' : '' }} {{ Request::is('admin/currencies/edit/*') ? 'active' : '' }} {{ Request::is('admin/currencies/filter') ? 'active' : '' }}"><a href="{{ URL::to('admin/currencies/display')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.currency') }}</a></li>
          </ul>
        </li>
      <?php } ?>
      <?php

        if($check->manage_admins_view == 1){
      ?>

         <li class="treeview {{ Request::is('admin/admins') ? 'active' : '' }} {{ Request::is('admin/addadmins') ? 'active' : '' }} {{ Request::is('admin/editadmin/*') ? 'active' : '' }} {{ Request::is('admin/manageroles') ? 'active' : '' }} {{ Request::is('admin/addadminType') ? 'active' : '' }} {{ Request::is('admin/editadminType/*') ? 'active' : '' }} {{ Request::is('admin/managers') ? 'active' : '' }} {{ Request::is('admin/addmanagers') ? 'active' : '' }} {{ Request::is('admin/editmanager/*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-users" aria-hidden="true"></i>
  <span> {{ trans('labels.Manage Admins') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>

          <ul class="treeview-menu">
              <li class="{{ Request::is('admin/admins') ? 'active' : '' }} {{ Request::is('admin/addadmins') ? 'active' : '' }} {{ Request::is('admin/editadmin/*') ? 'active' : '' }}">
                  <a href="{{ URL::to('admin/admins')}}">
                      <i class="fa fa-circle-o"></i>
                      {{ trans('labels.link_admins') }}
                  </a>
              </li>

              <li class="{{ Request::is('admin/managers') ? 'active' : '' }} {{ Request::is('admin/addmanagers') ? 'active' : '' }} {{ Request::is('admin/editmanager/*') ? 'active' : '' }}">
                  <a href="{{ URL::to('admin/managers')}}">
                      <i class="fa fa-circle-o"></i>
                      {{ trans('labels.link_managers') }}
                  </a>
              </li>
              <li class="{{ Request::is('admin/agents') ? 'active' : '' }} {{ Request::is('admin/addmanagers') ? 'active' : '' }} {{ Request::is('admin/editmanager/*') ? 'active' : '' }}">
                  <a href="{{ URL::to('admin/agents')}}">
                      <i class="fa fa-circle-o"></i>
                      Agents
                  </a>
              </li>

              <li class="{{ Request::is('admin/manageroles') ? 'active' : '' }} {{ Request::is('admin/addadminType') ? 'active' : '' }} {{ Request::is('admin/editadminType/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/manageroles')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_manage_roles') }}</a></li>
          </ul>
        </li>
        <?php }
        if($check->edit_management == 1){
        ?>

          <!--------create middlewares -------->
        <li class="treeview {{ Request::is('admin/managements/merge') ? 'active' : '' }} {{ Request::is('admin/managements/updater') ? 'active' : '' }} {{ Request::is('admin/managements/restore') ? 'active' : '' }} {{ Request::is('admin/managements/backup') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-gears" aria-hidden="true"></i>
  <span> {{ trans('labels.Managements') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>

          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/managements/updater') ? 'active' : '' }}"><a href="{{ URL::to('admin/managements/backup')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.Back Up / Restore') }}</a></li>
            <li class="{{ Request::is('admin/managements/updater') ? 'active' : '' }}"><a href="{{ URL::to('admin/managements/import')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.Import Data') }}</a></li>
            <li class="{{ Request::is('admin/managements/merge') ? 'active' : '' }}"><a href="{{ URL::to('admin/managements/merge')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_merge') }}</a></li>
            <li class="{{ Request::is('admin/managements/updater') ? 'active' : '' }}"><a href="{{ URL::to('admin/managements/updater')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_updater') }}</a></li>
          </ul>
        </li>
        <?php } ?>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

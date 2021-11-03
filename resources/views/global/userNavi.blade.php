<p class="h2 pb-3">{{ __('main.settings') }}</p>
<div id="userNavigation">
    <!-- Mobile Navi -->
    @if((\Request::route()->getName() == 'user.settings'))
        <a class="h4 btn btn-outline d-block text-left {{ (\Request::route()->getName() == 'user.edit') ? 'active' : '' }}" href="/ycity/user/edit">{{ __('navi.profile') }} <i class="float-right fa fa-lg fa-angle-right ml-1"></i></a>
        @if(Session::get('userType') == 'client')
            <a class="h4 btn btn-outline d-block text-left {{ (\Request::route()->getName() == 'client.index') ? 'active' : '' }}" href="/ycity/client">{{ __('navi.clients') }} <i class="float-right fa fa-lg fa-angle-right ml-1"></i></a>
        @endif
        @if(Session::get('userType') == 'creator' AND Session::get("activated"))
            <a class="h4 btn btn-outline d-block text-left {{ (\Request::route()->getName() == 'creator.portfolioEdit') ? 'active' : '' }}" href="/ycity/creator/portfolio/edit">{{ __('navi.portfolio') }} <i class="float-right fa fa-lg fa-angle-right ml-1"></i></a>
        @endif
        @if(Session::get('userType') !== 'nothing')
            <a class="h4 btn btn-outline d-block text-left" href="/ycity/user/notifications">{{ __('navi.messages') }} <i class="float-right fa fa-lg fa-angle-right ml-1"></i></a>
            <a class="h4 btn btn-outline d-block text-left {{ (\Request::route()->getName() == 'user.billingOptions') ? 'active' : '' }}" href="/ycity/user/billing-options">{{ __('navi.billing_options') }}<i class="float-right fa fa-lg fa-angle-right ml-1"></i> </a>
        @endif
    @else
        <a class="h5 d-table {{ (\Request::route()->getName() == 'user.edit') ? 'active' : '' }}" href="/ycity/user/edit">{{ __('navi.profile') }}</a>
        @if(Session::get('userType') == 'client')
            <a class="h5 d-table {{ (\Request::route()->getName() == 'client.index') ? 'active' : '' }}" href="/ycity/client">{{ __('navi.clients') }}</a>
        @endif
        @if(Session::get('userType') == 'creator' AND Session::get("activated"))
            <a class="h5 d-table {{ (\Request::route()->getName() == 'creator.portfolioEdit') ? 'active' : '' }}" href="/ycity/creator/portfolio/edit">{{ __('navi.portfolio') }}</a>
            <a class="h5 d-table {{ (\Request::route()->getName() == 'creator.skillsEdit') ? 'active' : '' }}" href="/ycity/creator/skills/edit">{{ __('navi.skills') }}</a>
        @endif
        @if(Session::get('userType') == 'client' OR (Session::get('userType') == 'creator' AND Session::get("activated")))
            <!--<a class="h5 d-table" href="/ycity/user/notifications">{{ __('navi.messages') }}</a>-->
            <a class="h5 d-table {{ (\Request::route()->getName() == 'user.billingOptions') ? 'active' : '' }}" href="/ycity/user/billing-options">{{ __('navi.billing_options') }}</a>
        @endif
    @endif
</div>

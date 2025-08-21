<div class="col-12">
    <div class="dashboard-header-menu justify-content-between">
        <div class="div">
            <a href="{{ route('user.future.order.open') }}" class="dashboard-header-menu__link {{ menuActive('user.future.order.open') }}">@lang('Open Order')</a>
            <a href="{{ route('user.future.position.open') }}" class="dashboard-header-menu__link {{ menuActive('user.future.position.open') }}">@lang('Open Position')</a>
            <a href="{{ route('user.future.order.history') }}" class="dashboard-header-menu__link {{ menuActive('user.future.order.history') }}">@lang('Order History')</a>
            <a href="{{ route('user.future.position.history') }}" class="dashboard-header-menu__link {{ menuActive('user.future.position.history') }}">@lang('Position History')</a>
            <a href="{{ route('user.future.trade.history') }}" class="dashboard-header-menu__link {{ menuActive('user.future.trade.history') }}">@lang('Trade History')</a>
        </div>
        <form class="d-flex gap-2 flex-wrap">
            <div class="flex-fill">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}" placeholder="@lang('Pair,coin')">
                    <button type="submit" class="input-group-text bg--primary text-white"><i class="las la-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

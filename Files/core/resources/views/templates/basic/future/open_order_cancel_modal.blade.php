<div id="cancelQueueModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form class="orderCancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you sure to cancel this order?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn--dark btn--sm" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--base btn--sm" data-bs-dismiss="modal">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

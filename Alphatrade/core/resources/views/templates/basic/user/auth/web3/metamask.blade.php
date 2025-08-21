@props(['action'])
<div class="continue-google">
    <button type="button" class="btn w-100" id="metamask">
        <span class="google-icon">
            <img src="{{ asset($activeTemplateTrue . 'images/icons/metamask.svg') }}">
        </span> {{ __(ucfirst($action)) }} @lang('With Metamask')
    </button>
</div>

@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", (e) => {
            document.getElementById("metamask").addEventListener('click', async function() {

                metamaskBtnLoading(true);

                const MMSDK = new MetaMaskSDK.MetaMaskSDK()
                setTimeout(async () => {

                    const ethereum = MMSDK.getProvider();
                    const accounts = await ethereum.request({
                        method: 'eth_requestAccounts'
                    });

                    const account = accounts[0];

                    const messageResp = await fetch(
                        "{{ route('user.web3.metamask.login.message') }}", {
                            method : "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                '_token'        : '{{ csrf_token() }}',
                                'wallet_address': account
                            })
                        });

                    const message = await messageResp.json();
                    if (!message.success) {
                        metamaskBtnLoading();
                        notify('error', message.message);
                        return;
                    }
                    const signature = await ethereum.request({
                        method: 'personal_sign',
                        params: [message.message, account],
                    });

                    const verifyRequest = await fetch(
                        "{{ route('user.web3.metamask.login.verify') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                '_token': '{{ csrf_token() }}',
                                'signature': signature
                            })
                        });

                    const verifyMessage = await verifyRequest.json();
                    if (!verifyMessage.success) {
                        metamaskBtnLoading();
                        notify('error', verifyMessage.message);
                        metamaskBtnLoading();
                        return;
                    }

                    metamaskBtnLoading();
                    notify('success', verifyMessage.message);

                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }, 100)
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('#metamask')) return false;
                metamaskBtnLoading();
            });

            function metamaskBtnLoading(isShow = false) {
                const metamaskBtn = document.getElementById("metamask");
                if(isShow) {
                    metamaskBtn.innerHTML = `<div class="spinner-border text-primary" role="status"></div>`;
                    metamaskBtn.setAttribute('disabled', true);
                } else {
                    metamaskBtn.removeAttribute('disabled');
                    metamaskBtn.innerHTML = `<span class="google-icon">
                        <img src="{{ asset($activeTemplateTrue . 'images/icons/metamask.svg') }}">
                    </span> {{ __(ucfirst($action)) }} @lang('With Metamask')`
                }
            }
        });
    </script>
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/metamask-sdk.js') }}"></script>
@endpush

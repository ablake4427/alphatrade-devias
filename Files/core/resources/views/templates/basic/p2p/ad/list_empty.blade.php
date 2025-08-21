@for ($i = 0; $i < 7; $i++)
    <tr class="p2pEmpty">
        <td>
            <div class="skeleton"></div>
        </td>
        <td>
            <div class="skeleton"></div>
        </td>
        <td>
            <div class="skeleton"></div>
        </td>
        <td>
            <div class="skeleton"></div>
        </td>
        <td>
            <div class="skeleton"></div>
        </td>
        <td>
            <div class="skeleton"></div>
        </td>
    </tr>
@endfor

@push('style')
    <style>
        .skeleton {
            height: 35px;
        }

        @media (max-width: 991px) {
            .table tbody tr.p2pEmpty td {
                position: relative;
            }
            
            [data-theme="light"] .table tbody tr.p2pEmpty td::before {
                content: '';
                position: absolute;
                width: 40% !important;
                height: 100%;
                z-index: 10;
                background: linear-gradient(90deg, #181d20, #080808, #181d20);
                background-size: 200% !important;
                animation: skeleton 1.5s infinite reverse;
                height: 35px;
            }

            [data-theme="dark"] .table tbody tr.p2pEmpty td::before {
                content: '';
                position: absolute;
                width: 40% !important;
                height: 100%;
                z-index: 10;
                background: linear-gradient(to right, #eee, #f9f9f9, #eee);
                background-size: 200% !important;
                animation: skeleton 1.5s infinite reverse;
                height: 35px;
            }

            .skeleton {
                width: 40%;
                margin-left: auto;
            }

            .table--responsive--lg tbody tr.p2pEmpty:nth-child(odd) {
                background-color: transparent !important;
            }
        }
    </style>
@endpush

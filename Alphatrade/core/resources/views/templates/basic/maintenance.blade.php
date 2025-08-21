<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ gs()->sitename(__($pageTitle)) }}</title>
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">

</head>

<body>
    <section class="maintenance-page">
        <div class="container">
            <div class="row justify-content-center text-center mt-5">
                <div class="col-lg-10">
                    <img src="{{ getImage(getFilePath('maintenance') . '/' . $maintenance->data_values->image, getFileSize('maintenance')) }}">
                    <h1 class="my-3 text-danger fw-bold">{{ __($maintenance->data_values->heading) }}</h1>
                    <div class="mb-1">
                        @php echo $maintenance->data_values->description @endphp
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>

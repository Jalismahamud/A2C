@if (Session::has('success'))
    <script>
        toastr.options = {
            'progressBar': true,
            'closeButton': true,
            'positionClass': 'toast-top-right',
            'preventDuplicates': true,
        };
        toastr.success("{{ Session::get('success') }}", 'Success!', {
            timeout: 120
        });
    </script>
@elseif(Session::has('error'))
    <script>
        toastr.options = {
            'progressBar': true,
            'closeButton': true,
            'positionClass': 'toast-top-right',
        }
        toastr.error("{{ Session::get('error') }}");
    </script>
@endif
@if ($errors->any())
    <script>
        $(document).ready(function () {
            @foreach ($errors->all() as $error)
                toastr.options = {
                'progressBar': true,
                'closeButton': true,
                'positionClass': 'toast-bottom-right',
            }
            toastr.error('{{ $error }}');
            @endforeach
        });
    </script>
@endif

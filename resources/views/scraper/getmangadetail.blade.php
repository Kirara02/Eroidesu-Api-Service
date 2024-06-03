<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('assets/images/eroidesu_logo.png') }}" type="image/x-icon"/>
    <title>Eroi Desu</title>
</head>
<body>
    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            getManga(1892);
        });
    
        function getManga(index) {
            $.ajax({
                url: '{{ route('api.get.manga.detail') }}',
                type: 'GET',
                data: {
                    index: index
                },
                success: function(response) {
                    console.log(response);
    
                    if (response.success && response.nextIndex !== null) {
                        getManga(response.nextIndex);
                    }else{
                        getManga(response.currentIndex);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
    </script>
</body>
</html>


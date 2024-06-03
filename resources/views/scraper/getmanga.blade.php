<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Eroi Desu</title>
</head>
<body>
    

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            getManga();
        });
    
        function getManga() {
            $.ajax({
                url: '{{ route('api.get.list.manga') }}',
                type: 'GET',
                success: function(response) {
                    console.log(response);
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
    </script>
</body>
</html>
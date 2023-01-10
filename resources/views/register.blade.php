<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranked Paper Scissor</title>
    <link href="{{ asset('css/master.css') }}" rel="stylesheet" media="all">
    <link rel="stylesheet" href="{{mix('css/app.css')}}?v={{rand(1, 1000000000)}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Erica+One&display=swap" rel="stylesheet">
</head>
<body>
    <div id="app-register">
        <div class="container-fluid min-vh-100 d-flex flex-column">
            <div class="row flex-grow-1 d-flex align-items-center justify-content-center">
                <div class="col-md-6">
                    <div class="card p-2">
                        <div class="card-body">
                            <div class="row text-center mb-2 card-title">
                                <h4>Register</h4>
                            </div>
                            <form action="{{route('register.post')}}" method="post" name="register-form" v-on:submit="register($event)">
                                @csrf
                                <div class="mb-3">
                                    <label for="text" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" autocomplete="off" v-model="username">
                                  </div>
                                <div class="mb-3">
                                  <label for="email" class="form-label">E-mail</label>
                                  <input type="text" class="form-control" id="email" name="email" autocomplete="off" v-model="email">
                                </div>
                                <div class="mb-4">
                                  <label for="password" class="form-label">Password</label>
                                  <input type="password" class="form-control" id="password" name="password" v-model="password">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary w-50 mb-2">Register</button>
                                    <a type="button" class="btn btn-secondary w-50" href="{{route('login')}}">Back</a>
                                </div>
                              </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{mix('/js/app.js')}}?v={{rand(1, 1000000000)}}"></script>
    <script>
        @if(Session::has('message'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true,
                "timeOut": 5000,
            }
            toastr.success("{{ session('message') }}");
        @endif
        @if(Session::has('error'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true,
                "timeOut": 5000,
            }
            toastr.error("{{ session('error') }}");
        @endif
        @if(Session::has('info'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true,
                "timeOut": 5000,
            }
            toastr.info("{{ session('info') }}");
        @endif
        @if(Session::has('warning'))
        toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true,
                "timeOut": 5000,
            }
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>
    <script>
        const app = new Vue({
            el: '#app-register',
            data:{
                username: '',
                email: '',
                password: '',
            },
            methods: {
                register: function(event){
                    event.preventDefault();

                    if(this.username.trim() != '' && this.email.trim() != '' && this.password.trim() != ''){
                        $("form[name=register-form]").submit();
                    }else{
                        toastr.error('Fill in all fields');
                    }
                }
            },
        });
    </script>
</body>
</html>
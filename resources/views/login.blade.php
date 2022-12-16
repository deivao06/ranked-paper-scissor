<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranked Paper Scissor</title>
    <link href="{{ asset('css/master.css') }}" rel="stylesheet" media="all">
    <link rel="stylesheet" href="{{mix('css/app.css')}}?v={{rand(1, 1000000000)}}">
</head>
<body>
    <div id="app-login">
        <div class="container-fluid min-vh-100 d-flex flex-column">
            <div class="row flex-grow-1 d-flex align-items-center justify-content-center">
                <div class="col-6">
                    <div class="card p-2">
                        <div class="card-body">
                            <div class="row text-center mb-2 card-title">
                                <h4>Ranked Paper Scissor</h4>
                            </div>
                            <form action="{{route('login.post')}}" method="post" name="login-form" v-on:submit="login($event)">
                                @csrf
                                <div class="mb-3">
                                  <label for="email" class="form-label">E-mail</label>
                                  <input type="text" class="form-control" id="email" name="email" autocomplete="off" v-model="email">
                                </div>
                                <div class="mb-3">
                                  <label for="password" class="form-label">Password</label>
                                  <input type="password" class="form-control" id="password" name="password" v-model="password">
                                </div>
                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary w-50 mb-2">Login</button>
                                    <a type="button" class="btn btn-secondary w-50" href="{{route('register')}}">Register</a>
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
            el: '#app-login',
            data:{
                email: "{{ Session::has('email') ? session('email') : old('email') }}",
                password: '',
            },
            mounted: function() {
                @if(Session::has('email'))
                    $('#password').focus();
                @endif
            },
            methods: {
                login: async function(event){
                    event.preventDefault();

                    if(this.email != '' && this.password != ''){
                        $("form[name=login-form]").submit();
                    }else{
                        toastr.error('Fill in all fields');
                    }
                }
            },
        });
    </script>
</body>
</html>
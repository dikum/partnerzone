<!DOCTYPE html>
<html>
<head>
	<title>Login|{{config('app.name', 'PartnerZone')}}</title>
	<style>
        #loader {
            transition: all .3s ease-in-out;
            opacity: 1;
            visibility: visible;
            position: fixed;
            height: 100vh;
            width: 100%;
            background: #fff;
            z-index: 90000
        }

        #loader.fadeOut {
            opacity: 0;
            visibility: hidden
        }

        .spinner {
            width: 40px;
            height: 40px;
            position: absolute;
            top: calc(50% - 20px);
            left: calc(50% - 20px);
            background-color: #333;
            border-radius: 100%;
            -webkit-animation: sk-scaleout 1s infinite ease-in-out;
            animation: sk-scaleout 1s infinite ease-in-out
        }

        @-webkit-keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0)
            }
            100% {
                -webkit-transform: scale(1);
                opacity: 0
            }
        }

        @keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0)
            }
            100% {
                -webkit-transform: scale(1);
                transform: scale(1);
                opacity: 0
            }
        }
    </style>
    <link href="{{'assets/login/css/style.css'}}" rel="stylesheet">
    <link href="{{'assets/font-awesome/css/font-awesome.min.css'}}" rel="stylesheet">
</head>
<body class="app">
    <div id="loader">
        <div class="spinner"></div>
    </div>
    <script type="ca7aeb39db87a60fd695aba0-text/javascript">
        window.addEventListener('load', () => {
            const loader = document.getElementById('loader');
            setTimeout(() => {
                loader.classList.add('fadeOut');
            }, 300);
        });
    </script>
    <div class="peers ai-s fxw-nw h-100vh">
        <div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv" style="background-image: url({{asset('assets/login/images/bg.jpg')}}) ">
            <div class="pos-a centerXY">
                <div class="quote">“The purpose of life is to glorify God in both good and hard times alike.” “Avoid the trap of looking back unless it is to glorify God for what He has done.” “Your troubles may point you to death and destruction, but God's Word points you to life.” “If you turn to God once, He will turn to you a million times.”</div>
                <!--<div class="bgc-white bdrs-50p pos-r" style="width:120px;height:120px"><img class="pos-a centerXY" src="./assets/login/images/logo.png" alt=""></div>-->
				<div class="author">-TB Joshua</div>
            </div>
        </div>
        <div class="col-12 col-md-4 peer pX-40  h-100 bgc-white scrollable pos-r" style="min-width:320px">
			<div class="text-center">
				<img class="img-fluid" style="margin-bottom:50px;" src="{{'assets/login/images/emmanueltv-logo.png'}}" />
                <div id="message" class="error-message"></div>
			</div>
            <div id="message" class="message"></div>
            <form  method="POST" id="login_form">
                {{ csrf_field() }}
                <div class="form-group"><label class="text-normal text-dark">Email</label><input type="email" class="form-control" placeholder="Email" name="email" id="email" required="true" /> </div>
                <div class="form-group"><label class="text-normal text-dark">Password</label><input type="password" class="form-control" placeholder="Password" name="password" id="password" required="true"></div>
                <div class="form-group">
                    <!--<div class="peers ai-c jc-sb fxw-nw">
                        <div class="peer">
                            <div class="checkbox checkbox-circle checkbox-info peers ai-c"><input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer"><label for="inputCall1" class="peers peer-greed js-sb ai-c">
                                <span class="peer peer-greed">Remember Me</span></label>
                            </div>
                        </div>-->
                        <div class="peer"><button id="submit" class="btn btn-primary">Login</button></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="{{'assets/js/jquery.min.js'}}"></script>
    <script type="text/javascript" src="{{'assets/js/jquery.validate.min.js'}}"></script>
    <script type="ca7aeb39db87a60fd695aba0-text/javascript" src="{{('assets/js/vendor.js')}}"></script>
    <script type="ca7aeb39db87a60fd695aba0-text/javascript" src="{{'assets/js/bundle.js'}}"></script>
    <script src="https://ajax.cloudflare.com/cdn-cgi/scripts/7089c43e/cloudflare-static/rocket-loader.min.js" data-cf-settings="ca7aeb39db87a60fd695aba0-|49" defer=""></script>
    <script type="text/javascript" src="{{'assets/login/js/login.js'}}"></script>
</body>
</html>
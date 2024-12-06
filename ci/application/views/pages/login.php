<!DOCTYPE html>
<html lang="ko">
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>login</title>

	<!-- favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="//kobe.pmgasia.co.kr/favicon.ico">
	<link rel="icon" type="image/png" sizes="32x32" href="//kobe.pmgasia.co.kr/favicon.ico">
	<link rel="icon" type="image/png" sizes="16x16" href="//kobe.pmgasia.co.kr/favicon.ico">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="//kobe.pmgasia.co.kr/css/common.css"  />
	<link rel="stylesheet" type="text/css" href="//kobe.pmgasia.co.kr/css/w_style.css" />
	<link rel="stylesheet" media="screen" href="//kobe.pmgasia.co.kr/css/m_style.css" >
	<link rel="stylesheet" type="text/css" href="//kobe.pmgasia.co.kr/css/bootstrap.css" >
	<link rel="stylesheet" type="text/css" href="//kobe.pmgasia.co.kr/css/font-awesome.min.css" />

	<script type="text/javascript" src="//kobe.pmgasia.co.kr/js/jquery-3.5.1.min.js"></script>
	<script type="text/javascript" src="//kobe.pmgasia.co.kr/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="//kobe.pmgasia.co.kr/js/ui.js"></script>
    

	
</head>
	
<body>
	<div id="ccWrap" class="ccWrap" >
		<section class="cc_inner loginWrap">
			<div class="cc_loginWrap" id='cc_loginWrap'>
				<h1 class="loginTtile">
					<img src="//kobe.pmgasia.co.kr/img/pmg_logo.png" alt="" class="logo"><br/>
					TEST LOGIN PAGE
				</h1>
				<h5>KOBE 2024</h5>
				<?php if ($this->session->flashdata('error')): ?>
                    <p style="color: red;"><?= $this->session->flashdata('error'); ?></p>
                <?php endif; ?>
                <form action="/Login_controller/login_process" method="post" name='loginform'>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <br>
                    <button type="submit">Login</button>
                </form>
			</div>
		</section>

        <!-- 로그인 실패 메시지 alert -->
		<?php if ($this->session->flashdata('error')): ?>
			<script>
				window.onload = function() {
					alert("<?= $this->session->flashdata('error'); ?>");
				};
			</script>
		<?php endif; ?>
        
	</div>
	

    <script>
           
    </script>
    
</body>
</html>
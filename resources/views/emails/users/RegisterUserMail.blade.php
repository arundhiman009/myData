<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>{{Config::get('app.name')}} Invitaion Email</title>
	<style type="text/css">
		body {
			margin: 0;
			padding: 0;
			min-width: 100% !important;
		}
		img {
			height: auto;
		}
		.image{
			height: 80px;
		}
		.content {
			width: 100%;
			max-width: 600px;
			margin-top: 0;
			margin-bottom: 0
		}
		.header {
			padding: 0;
			border: 1px solid #f4f4f4;
		}
		.innerpadding {
			padding: 30px 30px 30px 30px;
		}
		.borderbottom {
			border-bottom: 1px solid #f2eeed;
		}
		.subhead {
			font-size: 15px;
			color: #ffffff;
			font-family: sans-serif;
			letter-spacing: 10px;
		}
		.h1, .h2, .bodycopy, .actionbody, .captiontext {
			color: #153643;
			font-family: sans-serif;
		}
		.h1 {
			font-size: 33px;
			line-height: 38px;
			font-weight: bold;
		}
		.h2 {
			padding: 0 0 15px 0;
			font-size: 24px;
			line-height: 28px;
			font-weight: bold;
		}
		.h4 {
			color: #153643;
			font-family: sans-serif;
			font-size: 20px;
			font-weight: bold;
			line-height: 28px;
			padding-bottom: 20px
		}
		.captiontext {
			font-size: 14px;
			padding-top: 40px;
		}
		.bodycopy {
			font-size: 16px;
			line-height: 25px;
			padding-bottom: 15px
		}
		.actionbody {
			padding-top: 30px;
			padding-bottom: 20px;
		}
		.actionbody a {
			text-decoration: none
		}
		.button {
			text-align: center;
			font-size: 18px;
			font-family: sans-serif;
			font-weight: bold;
			padding: 0 30px 0 30px;
		}
		.button a {
			color: #ffffff;
			text-decoration: none;
		}
		.footer {
			padding: 20px 30px 15px 30px;
		}
		.footercopy {
			font-family: sans-serif;
			font-size: 13px;
			color: #595959;
			padding-bottom: 0;
			padding-top: 6px;
		}
		.footercopy a {
			color: #595959;
			text-decoration: underline;
			display: inline-block
		}
		.userEmail a{
			text-decoration: none;
			color: black;
		}
	</style>
</head>
<body yahoo bgcolor="#f4f4f4">
<table width="100%" bgcolor="#f4f4f4" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table width="600" align="center" cellpadding="0" cellspacing="0" border="0" style="padding: 10px;padding-top: 20px;">
				<tr>
					<td>
						<table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0"
							   border="0" style="border: 2px solid #eaeaea;">
							<tr>
								<td bgcolor="#ffffff" class="header" style="padding:0;">
									<table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td>
												<table class="col425" align="left" border="0" cellpadding="0"
													   cellspacing="0" style="width: 100%; max-width: 425px;">
													<tr>
														<td height="55">
															<table width="100%" border="0" cellspacing="0"
																   cellpadding="0">
																<tr>
																	<td class="h1"
																		style="padding: 5px 0 0 0;color: red;text-align: left;padding-left: 24px;">
																		<a style="color:red;text-decoration:none;" href="{{Config::get('app.url')}}">
																			<img class="image" src='{{Config::get('app.url')}}/public/assets/images/logo/logo_email.png' height="80px"  />
																		</a>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<p style="border-bottom: 1px solid #6565;margin-top: 0;margin-bottom: 5px;" > </p>
								</td>
							</tr>
							<tr>
								<td class="innerpadding borderbottom" >
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center" class="h4">
												{{Config::get('app.name')}}
											</td>
                                        </tr>

										<tr>
											<td class="bodycopy">
												Hi,
											</td>
										</tr>
										<tr>
											<td class="bodycopy">
												{{ucfirst($adminData->username)}} has  has made you the Host at <a href="{{Config::get('app.url')}}" >{{Config::get('app.name')}}</a>. Click on the Login button to setup your Account for Future process.
                                              
											</td>
                                        </tr>

										<tr>
											<td class="">
												For refrence, here's your login information.
												<ul>
                                                <li>Username : {{$user->username}}</li>
                                                <li>Email : {{$user->email}}</li>
                                                <li>Password: {{$password}}</li>
												</ul>

											</td>>
										</tr>

                                        <tr>
											<td class="actionbody" align="center" valign="middle">
												<br><br><br>
												<a href="{{route('dashboard')}}"
												   style="border-radius: 6px;border: 1px solid #595959; padding-bottom: 10px; padding-top: 10px; padding-left: 40px;padding-right: 40px; background-color: #e0a403;color: white;font-weight: bold;">Login now</a>
											</td>
										</tr>
                                        <tr>
								            <td height="40"></td>
								        </tr>
										<tr>
											<td>
												<table style="">
													<tr>
														<td>
															If you have any queries; feel free to ask us on <a href="mailto:{{$adminData->email }}">{{$adminData->email}}</a>.
														</td>
													</tr>
												</table>
											</td>
										</tr>





										<tr>
											<td>
												<p class="bodycopy" style="color: #e0a403;margin-bottom:0;padding-top: 10px;padding-bottom: 5px;"><strong>Alibaba Casino App Team</strong></p>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>

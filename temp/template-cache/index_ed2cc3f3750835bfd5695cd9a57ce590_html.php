<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $tpl->Get('title'); ?></title>
<meta charset="utf-8">
<link rel="stylesheet" href="templates/css/reset.css" type="text/css" media="screen">
<link rel="stylesheet" href="templates/css/style.css" type="text/css" media="screen">
<link rel="stylesheet" href="templates/css/layout.css" type="text/css" media="screen">
<link rel="stylesheet" href="templates/css/autoSuggest.css" type="text/css" media="screen">
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js" ></script>  
<script type="text/javascript" src="templates/js/imagepreloader.js"></script>
<script type="text/javascript" src="templates/js/waterDrop.jquery.js"></script>
<script type="text/javascript" src="templates/js/jqautosuggest/jquery.autoSuggest.js"></script>
<script type="text/javascript" src="templates/js/cssmenu.js"></script>
<script type="text/javascript" async>
$(document).ready(function(e) {
    $("#home").waterDrop();
	$("#searchjob").waterDrop();
	$("#postresume").waterDrop();
	$("#postjob").waterDrop();
	$("#contactus").waterDrop();

	$("input[name='keywords']").autoSuggest("ajax/getkeywords.php",{asHtmlID:'keywords',startText:'Keywords (comma separated)'<?php echo $tpl->Get('more_options_kw'); ?>});
	$("input[name='location']").autoSuggest("ajax/getlocation.php",{asHtmlID:'location',startText:'Locations'<?php echo $tpl->Get('more_options_loc'); ?>});
});
</script>
<script type="text/javascript">
	preloadImages([
		'templates/images/nav-tail.gif', 
		'templates/images/nav-right.gif', 
		'templates/images/nav-left.gif', 
		'templates/images/SearchForm-a-bg-hover.jpg', 
		'templates/images/checkbox-active.gif']);
</script>   
<script type="text/javascript" src="templates/js/jquery.jqtransform.js"></script><?php echo $tpl->Get('custom_header_content'); ?>
</head>
<body id="page1"><div class="Table_01">
	<div class="topnav-01_"></div>
	<div class="topnav-02_"></div>
	<div class="topnav-03_"><a href="<?php echo $tpl->Get('topLink1_Link'); ?>"><?php echo $tpl->Get('topLink1_Text'); ?></a></div>
	<div class="topnav-03_"><span>|</span><a href="<?php echo $tpl->Get('topLink2_Link'); ?>"><?php echo $tpl->Get('topLink2_Text'); ?></a></div>
    <div class="topnav-03_"><span>|</span><a href="<?php echo $tpl->Get('topLink3_Link'); ?>"><?php echo $tpl->Get('topLink3_Text'); ?></a></div>
    <?php echo $tpl->Get('extra_top_nav'); ?>
    <div class="topnav-04_">&nbsp;</div>
</div>

	<!-- header -->
	<header>
		<div class="main">
			<div class="container">
				<h1><a href="<?php echo $tpl->Get('logo_link'); ?>"><?php echo $tpl->Get('logo_text'); ?></a></h1>
                
				<div class="social">
                	<?php if(!$tpl->Get('IsLoggedIn')): ?>
                    <span>Jobseeker's <a href="<?php echo $tpl->Get('LoginLink'); ?>">Login</a> | <a href="<?php echo $tpl->Get('RegCandidateLink'); ?>">Upload Resume</a></span>
                    <?php else: ?>
                    <span><!--<a href="<?php echo $tpl->Get('LogoutLink'); ?>">Logout</a>--></span>
                    <?php endif; ?>
                   <!-- <a href="<?php echo $tpl->Get('RssLink'); ?>"><img SRC="templates/images/rss.jpg" alt="" /></a>
					<a href="<?php echo $tpl->Get('FbLink'); ?>"><img SRC="templates/images/facebook.jpg" alt="" /></a>
<div id="social-icons">
<a href="https://twitter.com/YOUR_USER_NAME"><img src="YOUR_IMAGE_URL" width="48" height="48" alt="Twitter" /></a>
<a href="http://feeds.feedburner.com/YOUR_FEED_URL"><img src="YOUR_IMAGE_URL" width="48" height="48" alt="RSS" /></a>
<a href="http://www.linkedin.com/in/YOUR_DISPLAY_NAME"><img src="YOUR_IMAGE_URL" width="48" height="48" alt="LinkedIn" /></a>
<a href="http://www.facebook.com/prashant.singh.507464"><img src="templates/images/facebook.jpg" width="48" height="48" alt="Facebook" /></a>
</div>               -->    
<!-- Facebook Like + Count (light version) -->
<!--<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=245406032159496&xfbml=1"></script><fb:like href="http://www.t2xplore.com" send="false" layout="button_count" width="100" show_faces="false" font="arial"></fb:like> -->
<br />
<div id="social-media-icons">
		<div class="facebook-button">
		<div class="fb-like" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
		<div id="fb-root"></div>
			<script>
				(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=134944943249381";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
			</script>
		</div>

		<div class="twitter-button">
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $tpl->Get('current_social_url'); ?>" data-text="<?php echo $tpl->Get('current_social_title'); ?>" data-via="YOUR-TWITTER-USERNAME">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>

		<div class="gplus-button">
		<g:plusone size="medium" href="<?php echo $tpl->Get('current_social_url'); ?>"></g:plusone>
			<script type="text/javascript">
				(function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
 				})();
			</script>
		</div>

		<!--<div class="pinterest-button">
			<a href="http://pinterest.com/pin/create/button/?url=<?php echo $tpl->Get('current_social_title'); ?>" class="pin-it-button" count-layout="horizontal">Pin It</a><script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>
		</div>

		<div class="stumbleupon-button">
			<su:badge layout="1"></su:badge>
				<script type="text/javascript">
					(function() {
						var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
						li.src = window.location.protocol + '//platform.stumbleupon.com/1/widgets.js';
						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
					})();
				</script>
		</div>-->

		<div class="linkedin-button">
			<script src="http://platform.linkedin.com/in.js" type="text/javascript"> </script>
			<script type="IN/Share" data-url="<?php echo $tpl->Get('current_social_title'); ?>" data-counter="right"> </script>
		</div>
	</div>


				</div>
				<div class="clear"></div>
			</div>
            <div class="slogan" style="position:relative"><div class="sloganText"><?php echo $tpl->Get('SlogalText'); ?></div></div>
			<div class="wrapper">
  <nav>
<?php if(!$tpl->Get('IsLoggedIn')): ?>  
    <ul>
      <li class="first"><a id="home" href="<?php echo $tpl->Get('HomeLink'); ?>" <?php echo $tpl->Get('nav_home_class'); ?>><span><span>Home</span></span></a></li>
      <li><a id="searchjob" href="<?php echo $tpl->Get('SearchLink'); ?>" <?php echo $tpl->Get('nav_search_class'); ?>><span><span>find jobs</span></span></a></li>
      
      <li><a id="postresume" href="<?php echo $tpl->Get('RegCandidateLink'); ?>" <?php echo $tpl->Get('nav_registration_class'); ?>><span><span>Post Resumes</span></span></a></li>
      <li><a id="myprofile" href="<?php echo $tpl->Get('ProfileLink'); ?>" <?php echo $tpl->Get('nav_profile_class'); ?>><span><span>My Profile</span></span></a></li>
      <li><a id="careeradvice" href="<?php echo $tpl->Get('AdviceLink'); ?>" <?php echo $tpl->Get('nav_careeradvice_class'); ?>><span><span>Career Advice</span></span></a></li>
      <li><a id="contactus" href="<?php echo $tpl->Get('HelpContactLink'); ?>" <?php echo $tpl->Get('nav_contactus_class'); ?>><span><span>Contact us</span></span></a></li>
	</ul>
<?php else: ?>
<ul>
<style>
/* Menu */
#mtab {background: transparent url("templates/images/menu/mtab.gif") no-repeat; width:718px; height:44px; margin:0;}
a.ovalbutton {background: transparent url("templates/images/menu/menu2a.gif") no-repeat top left;
display: block; float: left; font: bold 13pt Arial; line-height: 16px; height: 44px; padding-left: 6px; text-decoration: none;}
a:link.ovalbutton, a:visited.ovalbutton, a:active.ovalbutton {color:#FFF;}
a.ovalbutton span {background: transparent url("templates/images/menu/menu2.gif") no-repeat top right;
display: block; padding: 14px 10px 14px 5px;}
a.ovalbutton:hover {background-position: bottom left;}
a.ovalbutton:hover span{background-position: bottom right; color:#990009;}
a.ovalbutton1 {background: transparent url("templates/images/menu/menu2a.gif") no-repeat bottom left;
display: block; float: left; font: bold 13pt Arial; line-height: 16px; height: 44px; padding-left: 6px; text-decoration: none;}
a:link.ovalbutton1, a:visited.ovalbutton1, a:active.ovalbutton1 {color:#990009;}
a.ovalbutton1 span {background: transparent url("templates/images/menu/menu2.gif") no-repeat bottom right;
display:block; padding: 14px 10px 14px 5px;}
a.ovalbutton2 {background: transparent url("templates/images/menu/menu1.gif") no-repeat top left;
display: block; float: left; font: bold 13pt Arial; line-height: 16px; height: 44px; padding-left: 6px; text-decoration: none;}
a:link.ovalbutton2, a:visited.ovalbutton2, a:active.ovalbutton2 {color: #FFFFFF;}
a.ovalbutton2 span {background: transparent url("templates/images/menu/menu1a.gif") no-repeat top right;
display: block; padding: 14px 28px 14px 5px;}
a.ovalbutton2:hover {background-position: bottom left;}
a.ovalbutton2:hover span{background-position: bottom right; color:#990009;}
a.ovalbutton3{background:transparent url("templates/images/menu/menu1.gif") no-repeat bottom left;
display:block; float:left; font:bold 13pt Arial; line-height:16px; height:44px; padding-left:6px; text-decoration:none;}
a:link.ovalbutton3, a:visited.ovalbutton3, a:active.ovalbutton3{color:#990009;}
a.ovalbutton3 span{background:transparent url("templates/images/menu/menu1a.gif") no-repeat bottom right;
display:block; padding:14px 28px 14px 5px;}
.dropmenudiv{position:absolute; top:0; margin-top:-1px; z-index:100; visibility:hidden; border-left:solid 2px #DDD; border-right:solid 2px #DDD; border-bottom:solid 2px #DDD;}
.dropmenudiv a{font:bold 11px Tahoma; width:auto; background-color:#F0F0F0; display:block; text-indent:5px; border-bottom:1px solid #DADADA; padding:6px 4px 6px 4px; text-decoration:none; letter-spacing:0px; color:#990007; font-weight:bold;}
* html .dropmenudiv a{width:100%;}
.dropmenudiv a:hover{background-color:#990007; color:#FFF; font-weight:bold;}
.dropmenudiv2{line-height:18px; background-color:#F0F0F0; padding:4px 5px;}
</style>
<div style="text-align:left;">
	<a id="dummy"><!--  --></a>
	<div id="mtab" style="overflow:hidden;">
		<div id="chromemenu" onmouseover="setfocus();" style="padding-left:10px;">
			<a id="m1" href="candidate_dashboard.php" class="ovalbutton1" rel=""><span>My Home</span></a><img src="templates/images/menu/sep.gif" alt="" align="left" border="0" hspace="1" vspace="10">
			
			<a id="m2" href="#" class="ovalbutton2" rel="dropmenu2"><span>My Profile</span></a><img src="templates/images/menu/sep_002.gif" alt="" align="left" border="0" hspace="1" vspace="10">
			
			<a id="m3" href="#" class="ovalbutton2" rel="dropmenu3"><span>My Search</span></a><img src="templates/images/menu/sep_002.gif" alt="" align="left" border="0" hspace="1" vspace="10">
			
			<a id="m4" href="#" class="ovalbutton2" rel="dropmenu4"><span>My Messages</span></a><img src="templates/images/menu/sep_002.gif" alt="" align="left" border="0" hspace="1" vspace="10">

		</div>
	</div>		
		
	<!-- Drop Down Option --> 
	<div id="dropmenu2" class="dropmenudiv" style="width:190px;"><div class="dropmenudiv2" onmouseover="fixedClass('m2','ovalbutton3');" onmouseout="fixedClass1('m2','ovalbutton2');">
		<a href="<?php echo $tpl->Get('View_Profile_Link'); ?>">My Profile</a>
		<a href="<?php echo $tpl->Get('View_Resume_Link'); ?>">My Resume</a>
		<a href="<?php echo $tpl->Get('Edit_AccountInfo_Link'); ?>">Privacy &amp; Settings</a>
	</div></div>

	<div id="dropmenu3" class="dropmenudiv" style="width:190px;"><div class="dropmenudiv2" onmouseover="fixedClass('m3','ovalbutton3');" onmouseout="fixedClass1('m3','ovalbutton2');">
		<a href="advanced-search.php">Advanced Search</a>
		<a href="by-category.php">Browse by Category</a>
		<a href="by-jobcode.php" style="border:none;">Search by Job Code</a>
	</div></div>

	<div id="dropmenu4" class="dropmenudiv" style="width:190px;"><div class="dropmenudiv2" onmouseover="fixedClass('m4','ovalbutton3');" onmouseout="fixedClass1('m4','ovalbutton2');">
		<a href="jobseeker/in-mails.php">Inbox</a>
		<a href="jobseeker/compose" style="border:none;">Compose Message</a>
	</div></div>
	
	
</div>
	<script type="text/javascript">
		cssdropdown.startchrome("chromemenu");
		function setfocus()
		{
			document.getElementById("dummy").focus();
		}
	</script>
    </ul>
<?php endif; ?>
    <?php if($tpl->Get('show_employers_bar')=='true'): ?>
    <div class="employers"> <strong>Employers:</strong>
      <div> <a id="postjob" href="<?php echo $tpl->Get('PostJOBLink'); ?>">Post Jobs</a> | &nbsp; <a href="<?php echo $tpl->Get('SearchCandidateLink'); ?>">Search Resumes</a> </div>
    </div>
    <?php else: ?>
    <div style="height: 61px;"></div>
    <?php endif; ?> </nav>
</div>

<?php if($tpl->Get('show_employers_bar')!='true'): ?>
<div style="position:relative">
  <div class="mainSideNav ">
    <div class="cl"></div>
    <ul class="fl navUL">
      <li class="t2XSideNav moreUL "><a target="_blank" href="#" class=" more">Career Services</a>
        <ul class="cs padl9">
          <li><span class="navBG"></span></li>
          <li> <a target="_blank" href="#">Resume Service<br>
            <span class="menuHelp">Resume &amp; Profile enhancement services</span> </a> <a target="_blank" href="#">Career Astro<br>
            <span class="menuHelp">Vedic Astrology based Career Advice </span> </a> <span class="disBlk cl"></span> </li>
          <li> <a target="_blank" href="#">Resume Critique<br>
            <span class="menuHelp">Check your Resume effectiveness &amp; strength</span> </a> <a target="_blank" href="#">Profile Verification<br>
            <span class="menuHelp">Pre-employment 'work + academic' verification services</span> </a> <span class="disBlk cl"></span> </li>
        </ul>
        <iframe class="ifrmBlk"></iframe>
      </li>
      <!--<li class=""><a target="_blank" href="#">TJinsite <span class="exlnk"></span></a></li>-->
      <li class=""><a href="#">Job Fairs</a></li>
      <li class=""><a href="#">Mobile</a></li>
      <li class="t2XSideNav moreUL "><a style="cursor: default; text-decoration: none;" class="more" href="#">More</a>
        <ul class="mre padl9">
          <li><span class="navBG"></span></li>
          <li><a target="_blank" href="#" class="noBrdr">Jobs on Email</a></li>
          <li><a target="_blank" href="#">Blog <span class="exlnk"></span></a></li>
          <li><a href="#">Career Articles </a></li>
        </ul>
        <iframe class="ifrmBlk"></iframe>
      </li>
    </ul>
    <div class="cl"></div>
  </div>
  <script type="text/javascript">jQuery(function(){jQuery('.t2XSideNav').hover(function(){jQuery('.faBlk').removeClass('posrel');jQuery('#divInd, #iframeInd').hide();jQuery(this).find('ul').show();var navWidth=jQuery(this).find('ul').width();var navHeight=jQuery(this).find('ul').height();jQuery(this).find('.ifrmBlk').css({width:""+navWidth+"",height:""+navHeight+"",display:'block'});},function(){jQuery(this).find('ul').hide();jQuery('.faBlk').addClass('posrel');jQuery(this).find('.ifrmBlk').css({'display':'none'});});jQuery('.t2XSideNav li').hover(function(){jQuery(this).toggleClass('csHover');});jQuery('.nav1 > li').hover(function(){jQuery(this).toggleClass('menuHover');});jQuery('a.more').parent().hover(function(){jQuery(this).find('a.more').addClass('moreBrdr');jQuery(this).find('.padl9').prev().addClass('pad_l9');},function(){jQuery(this).find('a.more').removeClass('moreBrdr');jQuery(this).find('.padl9').prev().removeClass('pad_l9');});jQuery(".t2XSideNav").hover(function(){var navWidth=jQuery(this).width()-4;jQuery(this).find(".navBG").css({width:""+navWidth+"px"})});jQuery(".moreUL").hover(function(){var navWidth=jQuery(this).width()-3;jQuery(this).find(".navBG").css({width:""+navWidth+"px"})});});</script> 
</div>
<?php endif; ?>
			<?php if($tpl->Get('bShowSearchForm')): ?>
						<form id="SearchForm" action="search_results.php" method="post">
            <div class="Find_Jobs">Find jobs</div>
				<label><strong>What?</strong><span>Type comma separated keywords Or just press tab after each keyword</span><input name="keywords" type="text" value="<?php echo $tpl->Get('keywords'); ?>" /></label>
				<label><strong class="color">Where?</strong><span>Type comma separated locations Or just press tab after each location</span><input name="location" type="text" value="<?php echo $tpl->Get('location'); ?>"  /></label>
                <input type="submit" value="Find Jobs" class="rounded" />
			</form>
            <?php endif; ?>
		</div>
	</header>
	<!-- content -->
	<section id="content">
		<div class="main">
        <?php echo $tpl->Get('Sucess_message'); ?><?php echo $tpl->Get('Error_message'); ?>
			<?php echo $tpl->Get('PageContent'); ?>
		</div>
	</section>
	<!-- footer -->
		<footer>
		<div class="main">
			<div class="wrapper">
				<span><?php echo $tpl->Get('FOOTERLNK'); ?></span>
				<p>T2Xplore.com &nbsp;&copy; 2012 All rights reserved &nbsp; | &nbsp; <a href="<?php echo $tpl->Get('Privacy_link'); ?>">Privacy Policy</a></p>
			</div>
		</div>
	</footer>  
    <?php echo $tpl->Get('custom_footer_content'); ?>
</body>
</html>

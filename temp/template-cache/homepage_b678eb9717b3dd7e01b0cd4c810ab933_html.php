<div class="indent1">
				<h2 class="h2-bg1">Recently posted jobs</h2>
				<div class="wrapper">
					<article class="col-1 col-indent">
						<ul class="date-list">
							<?php if($tpl->Get('Has_Recentlypostedjobs')): ?>
                            	<?php $array = $tpl->Get('Recentlypostedjobs_list'); if(is_array($array) || is_object($array)): foreach($array as $__key=>$eachjob): $tpl->Assign('__key', $__key, false); $tpl->Assign('eachjob', $eachjob, false);  ?>
                            <li>
								<?php if($tpl->Get('eachjob','imagepath')): ?>
                                <img SRC="<?php echo $tpl->Get('eachjob','imagepath'); ?>" alt="" />
                                <?php endif; ?>
								<a href="<?php echo $tpl->Get('eachjob','joblink'); ?>"><?php echo $tpl->Get('eachjob','jobtitle'); ?></a>
								<strong><?php echo $tpl->Get('eachjob','jobdesc'); ?></strong>
								<div>
									<span class="text_1"><strong>Location:</strong> <span><?php echo $tpl->Get('eachjob','location'); ?></span></span>
									<span class="text_2"><strong>Posted on:</strong> <span><?php echo $tpl->Get('eachjob','postdate'); ?></span></span>
								</div>
							</li>
                             <?php endforeach; endif; ?>
                            <?php else: ?>
                            <li>
								No Jobs posted yet
							</li>                            
                            <?php endif; ?>
							
						</ul>
					</article>
					<article class="col-1">
						<ul class="date-list">
							<?php if($tpl->Get('Has_Recentlypostedjobs2')): ?>
                            	<?php $array = $tpl->Get('Recentlypostedjobs_list2'); if(is_array($array) || is_object($array)): foreach($array as $__key=>$eachjob2): $tpl->Assign('__key', $__key, false); $tpl->Assign('eachjob2', $eachjob2, false);  ?>
                            <li>
								<?php if($tpl->Get('eachjob2','imagepath')): ?>
                                <img SRC="<?php echo $tpl->Get('eachjob2','imagepath'); ?>" alt="" />
                                <?php endif; ?>
								<a href="<?php echo $tpl->Get('eachjob2','joblink'); ?>"><?php echo $tpl->Get('eachjob2','jobtitle'); ?></a>
								<strong><?php echo $tpl->Get('eachjob2','jobdesc'); ?></strong>
								<div>
									<span class="text_1"><strong>Location:</strong> <span><?php echo $tpl->Get('eachjob2','location'); ?></span></span>
									<span class="text_2"><strong>Posted on:</strong> <span><?php echo $tpl->Get('eachjob2','postdate'); ?></span></span>
								</div>
							</li>
                             <?php endforeach; endif; ?>
                            <?php else: ?>
                            <li>
								No Jobs posted yet
							</li>                            
                            <?php endif; ?>
						</ul>
					</article>
				</div>
				<div class="line-hor"><div class="alignright"><a href="#" class="link">view all posted jobs</a></div></div>
				<h2 class="h2-bg3">Job Search Tools</h2>
				<div class="container">
					<article class="col-2 col-indent1 margin-left">
						<ul class="list_1">
							<li>
								<img SRC="templates/images/page1-img8.jpg" alt="" />
								<a href="#">Distribute Your Resume</a>
								<span>We can get your resume out 78% faster</span>
							</li>
							<li>
								<img SRC="templates/images/page1-img9.jpg" alt="" />
								<a href="#">Free Resume Critique</a>
								<span>Does your resume need to be improved?</span>
							</li>
						</ul>
					</article>
					<article class="col-2 col-indent2">
						<ul class="list_1">
							<li>
								<img SRC="templates/images/page1-img10.jpg" alt="" />
								<a href="#">Free Career Quiz</a>
								<span>What career is right for you?</span>
							</li>
							<li>
								<img SRC="templates/images/page1-img11.jpg" alt="" />
								<a href="#">Free Salary Calculator</a>
								<span>What career is right for you?</span>
							</li>
						</ul>
					</article>
					<article class="col-2">
						<ul class="list_1">
							<li>
								<img SRC="templates/images/page1-img12.jpg" alt="" />
								<a href="#">Career Advice</a>
								<span>What career is right for you?</span>
							</li>
							<li>
								<img SRC="templates/images/page1-img13.jpg" alt="" />
								<a href="#">Free Career Quiz</a>
								<span>What career is right for you?</span>
							</li>
						</ul>
					</article>
					<div class="clear"></div>
				</div>
				<div class="line-hor"><div class="alignright"><a href="#" class="link">more job search tools</a></div></div>
				<h2 class="h2-bg4">Careers by Category</h2>
				<div class="wrapper">
                    	<?php $array = $tpl->Get('results'); if(is_array($array) || is_object($array)): foreach($array as $id=>$con): $tpl->Assign('id', $id, false); $tpl->Assign('con', $con, false);  ?>
                            <?php if($tpl->Get('id')==0 && ($tpl->Get('id') % 5) == 0): ?>
                            <article class="col-3 col-indent1">
                                <ul class="list">
                             <?php endif; ?>   
                             <?php if($tpl->Get('id')!=0): ?>
                                    <li><a href="listjob.php?cat_id=<?php echo $tpl->Get('id'); ?>"><?php echo $tpl->Get('con','industryname'); ?></a></li>
                             <?php endif; ?>
							<?php if($tpl->Get('id')!=0 && ($tpl->Get('id') % 5) == 0): ?>              
                                </ul>
                            </article>
                            <article class="col-3 col-indent1">
                                <ul class="list">                            
                           <?php endif; ?>
						 <?php endforeach; endif; ?>         
                   
				</div>
				<div class="alignright"><a href="#" class="link">all categories</a></div>
			</div>
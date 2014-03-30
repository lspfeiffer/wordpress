<?php header("Content-Type: text/css"); ?>

<?php $styles = array(); ?>
<?php foreach ($_GET as $skey => $sval) : ?>
	<?php $styles[$skey] = urldecode($sval); ?>
<?php endforeach; ?>

<?php if (!empty($styles['wrapperid'])) : ?>
ul.slideshow { list-style:none !important; color:#fff; }
ul.slideshow span { display:none; }
#<?php echo $styles['wrapperid']; ?> { position:relative; height:0; width:100%; background:<?php echo $styles['background']; ?>; padding:0 0 <?php echo $styles['resheight'] . $styles['resheighttype']; ?> 0; border:<?php echo $styles['border']; ?>; margin:0; display:none; }
#<?php echo $styles['wrapperid']; ?> * { margin:0; padding:0; }
#<?php echo $styles['wrapperid']; ?> #fullsize { position:absolute; z-index:1; overflow:hidden; width:100%; height:100%; border: none; }
#<?php echo $styles['wrapperid']; ?> #information { text-align:left; font-family:Verdana, Arial, Helvetica, sans-serif !important; position:absolute; bottom:0; width:100%; height:0; background:<?php echo $styles['infobackground']; ?>; color:<?php echo $styles['infocolor']; ?>; overflow:hidden; z-index:200; opacity:.7; filter:alpha(opacity=70); }
#<?php echo $styles['wrapperid']; ?> #information h3 { color:<?php echo $styles['infocolor']; ?>; padding:4px 8px 3px; margin:0 !important; font-size:16px; font-weight:bold; }
#<?php echo $styles['wrapperid']; ?> #information p { color:<?php echo $styles['infocolor']; ?>; padding:0 8px 8px; margin:0 !important; font-size: 14px; font-weight:normal; }
#<?php echo $styles['wrapperid']; ?> #image { width:100%; no-repeat; }
#<?php echo $styles['wrapperid']; ?> #image img { border:none; height:100%; max-width:100%; width:100%; margin:0 auto; display:block; }
#<?php echo $styles['wrapperid']; ?> .imgnav { position:absolute; width:25%; height:100%; cursor:pointer; z-index:150; }
#<?php echo $styles['wrapperid']; ?> #imgprev { left:0; background:url('../../images/left.gif') left center no-repeat; }
#<?php echo $styles['wrapperid']; ?> #imgnext { right:0; background:url('../../images/right.gif') right center no-repeat; }
#<?php echo $styles['wrapperid']; ?> #imglink { position:absolute; zoom:1; background-color:#ffffff; height:100%; <?php if (!empty($styles['shownav']) && $styles['shownav'] == "true") : ?>width:50%; left:25%; right:20%;<?php else : ?>width:100%; left:0;<?php endif; ?> z-index:149; opacity:0; filter:alpha(opacity=0); }
#<?php echo $styles['wrapperid']; ?> .linkhover { background:transparent url('../../images/link.gif') center center no-repeat !important; text-indent:-9999px; opacity:.4 !important; filter:alpha(opacity=40) !important; }
#<?php echo $styles['wrapperid']; ?> #thumbnails { background:<?php echo $styles['background']; ?>; height:<?php echo ((int) $styles['thumbheight'] + 14); ?>px; display:block; }
#<?php echo $styles['wrapperid']; ?> .thumbstop { margin-bottom:8px !important; }
#<?php echo $styles['wrapperid']; ?> .thumbsbot { margin-top:8px !important; }
#<?php echo $styles['wrapperid']; ?> #slideleft { z-index:150; float:left; position:absolute; left:0; width:20px; height:<?php echo ((int) $styles['thumbheight'] + 14); ?>px; background:url('../../images/scroll-left.gif') center center no-repeat; background-color:#222; }
#<?php echo $styles['wrapperid']; ?> #slideleft:hover { background-color:#333; }
#<?php echo $styles['wrapperid']; ?> #slideright { z-index:150; float:right; position:absolute; right:0; width:20px; height:<?php echo ((int) $styles['thumbheight'] + 14); ?>px; background:#222 url('../../images/scroll-right.gif') center center no-repeat; }
#<?php echo $styles['wrapperid']; ?> #slideright:hover { background-color:#333; }
#<?php echo $styles['wrapperid']; ?> #slidearea { z-index:149; float:left; position:absolute; margin:0 5px; background:<?php echo $styles['background']; ?>; width:<?php echo ($styles['width'] != "auto") ? ((int) $styles['width'] - 50) . 'px' : '90%'; ?>; height:<?php echo ((int) $styles['thumbheight'] + 14); ?>px; overflow:hidden; }
#<?php echo $styles['wrapperid']; ?> #slider { position:absolute; left:0; height:<?php echo ((int) $styles['thumbheight'] + 14); ?>px; }
#<?php echo $styles['wrapperid']; ?> #slider img { cursor:pointer; border:1px solid #666; padding:2px; -moz-border-radius:4px; -webkit-border-radius:4px; float:left !important; }
#<?php echo $styles['wrapperid']; ?> #spinner { position:relative; top:50%; left:45%; text-align:left; }	
#<?php echo $styles['wrapperid']; ?> #spinner img { border:none; }
<?php endif; ?>
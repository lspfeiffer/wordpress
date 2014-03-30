<?php
$this->loadFragment('headerstart');
?>

<?php
$this->loadFragment('headerend');
?>

<?php
$this->loadFragment('firstcolstart');
?>

<?php
$this->loadFragment('firstcolend');
?>

<?php
$this->loadFragment('secondcolstart');
?>

<?php
    $css = NextendCss::getInstance();
    $css->addCssFile(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/css/create.css');
    $isMage = nextendIsMagento();
?>

<h2>Create new slider</h2>
<div class="blue-container">
  <table class="pane-table smartslider-slider-create">
    <tr class="smartslider-slider-create-image">
        <td><img src="<?php echo NextendUri::pathToUri(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/images/create-quick.png') ?>" /></td>
        <td><img src="<?php echo NextendUri::pathToUri(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/images/create-manual.png') ?>" /></td>
        <td><img src="<?php echo NextendUri::pathToUri(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/images/create-dynamic.png') ?>" /></td>
    </tr>
    <tr class="smartslider-slider-create-heading">
        <td>Quick slider</td>
        <td>Manual slider</td>
        <td>Dynamic slider</td>
    </tr>
    <tr class="smartslider-slider-create-note">
        <td>Fasten your seatbelt, because it was designed to be as simply and fast to setup as it could possibly be. Create an image slider in minutes.</td>
        <td>Build a new slider from layers and items. Your imagination is the only limit for sliders!  Ideal for homepages, showcases and presentations.</td>
        <td>Generate sliders from external sources. Show the most popular products from your webshop, blog posts from a category or pictures from a folder.</td>
    </tr>
    <tr class="smartslider-slider-create-button">
        <td>
            <a class="button big b" href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=createquick'); ?>">Start</a>
        </td>
        <td>
            <a class="button big b" href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=createmanual'); ?>">Start</a>
        </td>
        <td>
            <a class="button big b" href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=createdynamic'); ?>">Start</a>
            <br /><br />
        </td>
    </tr>
  </table>
</div>

<h2>Import sample slider</h2>
<div class="blue-container">
<?php
NextendMessage::show();

nextendimportsmartslider2('nextend.smartslider.check');

$imagesToUrl = array(
    'Movie_3D.png' => 'http://www.nextendweb.com/demo/smartslider2/widget/movie-2d-3d',
    'Movie_2D.png' => 'http://www.nextendweb.com/demo/smartslider2/widget/movie-2d-3d',
    'Thumbnail_Vertical.png' => 'http://www.nextendweb.com/demo/smartslider2/widget/thumbnail-vertical',
    'Thumbnail_Horizontal.png' => 'http://www.nextendweb.com/demo/smartslider2/widget/thumbnail-horizontal',
    'Bar_widget_with_parallax.png' => 'http://www.nextendweb.com/demo/smartslider2/widget/bar-widget-parallax',
    'Gallery_version.png' => 'http://www.nextendweb.com/demo/smartslider2/widget/gallery-version',
    'Sponsor_II.png' => 'http://www.nextendweb.com/demo/smartslider2/varied-size/sponsors',
    'Sponsor_I.png' => 'http://www.nextendweb.com/demo/smartslider2/varied-size/sponsors',
    'Carousel.png' => 'http://www.nextendweb.com/demo/smartslider2/varied-size/carousel-2in1',
    'Full_width_with_layer_and_3D.png' => 'http://www.nextendweb.com/demo/smartslider2/varied-size/full-width-layer',
    'Full_width_with_skitter_and_3D.png' => 'http://www.nextendweb.com/demo/smartslider2/varied-size/full-width-skitter',
    'Mini_Slider.png' => 'http://www.nextendweb.com/demo/smartslider2/varied-size/mini-slider',
    'Block_Layouts.png' => 'http://www.nextendweb.com/demo/smartslider2/design-inspiration/block-layouts-4in1',
    'One_page.png' => 'http://www.nextendweb.com/demo/smartslider2/design-inspiration/one-page',
    'Travel.png' => 'http://www.nextendweb.com/demo/smartslider2/design-inspiration/travel',
    'Stories.png' => 'http://www.nextendweb.com/demo/smartslider2/design-inspiration/stories',
    'Wonderful_world.png' => 'http://www.nextendweb.com/demo/smartslider2/design-inspiration/wonderful-world',
    'Posts_and_Articles.png' => 'http://www.nextendweb.com/demo/smartslider2/generator/joomla-article',
    'Minimal_with_Ken_Burns.png' => 'http://www.nextendweb.com/demo/smartslider2/generator/minimal',
    'Vertical_Accordion.png' => 'http://www.nextendweb.com/demo/smartslider2/type/vertical-accordion-faq',
    'Horizontal_Accordion.png' => 'http://www.nextendweb.com/demo/smartslider2/type/horizontal-accordion',
    'Full_page.png' => 'http://www.nextendweb.com/demo/smartslider2/type/full-page',
    'Showcase_II.png' => 'http://www.nextendweb.com/demo/smartslider2/type/showcase',
    'Showcase_I.png' => 'http://www.nextendweb.com/demo/smartslider2/type/showcase',
    'Simple_with_layer_animation.png' => 'http://www.nextendweb.com/demo/smartslider2/type/simple-layer-animation',
    'Main_slider.png' => 'http://www.nextendweb.com/demo/smartslider2/',
    'Image_stripe.png' => 'http://www.nextendweb.com/demo/smartslider2/generator/image-stripe'
);

function generateImportThumbs($controller, $path, $filename, $imagesToUrl, $full = false){
    ?>
    <div class="smartslider-import-thumb">
        <img src="<?php echo NextendUri::pathToUri($path) ?>" /><br />
        <?php if(!$full || smartsliderIsFull()): ?>
            <a class="button small b" href="<?php echo $controller->route('controller=sliders&view=sliders_slider&action=importlocal&full='.($full?1:0).'&slider='.substr($filename, 0, -4) ); ?>">Import</a>
        <?php else: ?>
            <a class="button small g" href="http://www.nextendweb.com/smart-slider/" target="_blank">Get full</a>
        <?php endif; ?>
        <?php if(isset($imagesToUrl[$filename])): ?>
         &nbsp;&nbsp;
        <a class="button small" href="<?php echo $imagesToUrl[$filename]; ?>" target="_blank">Preview</a>
        <?php endif; ?>
    </div>
    <?php
}
 
$freePath = NEXTEND_SMART_SLIDER2_ASSETS . 'admin/smart/free/';
$frees = NextendFilesystem::files($freePath);
foreach($frees as $free){
    if(pathinfo($free, PATHINFO_EXTENSION) == 'png') generateImportThumbs($this, $freePath.$free, $free, $imagesToUrl);
}

$fullPath = NEXTEND_SMART_SLIDER2_ASSETS . 'admin/smart/full/';
$fulls = NextendFilesystem::files($fullPath);
foreach($fulls as $full){
    if(pathinfo($full, PATHINFO_EXTENSION) == 'png') generateImportThumbs($this, $fullPath.$full, $full, $imagesToUrl, true);
}

?>
</div>


<?php
$this->loadFragment('secondcolend');
?>

<?php
$this->loadFragment('footer');

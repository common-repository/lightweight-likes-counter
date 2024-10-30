<?php
/**
 * This is the template which is loaded on the website
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
$LWLC_results  = get_option('LWLC_results');
if($LWLC_results['seperate'] || $LWLC_results['combined']){
?>
<div class="LWLC_Container">
  <?php
  /**
   * Container for the share buttons. This is a hidden field but
   * gets enbabled when the count buttons are clicked.
   *
   * @since 0.3
   * @author support@beansandpixels.com
   */
  ?>
  <ul id="LWLC_<?php the_ID(); ?>" class="LWLC_ShareLinks">
    <li class="LWLC_ShareLinksTitle">Share<span>x</span></li>
    <li>
      <a href="https://twitter.com/share" 
        data-url="<?php the_permalink(); ?>" 
        data-text="<?php the_title(); ?>" 
        class="twitter-share-button" 
        data-count="none" 
        data-via="">Tweet</a>
    </li>
    <li>
      <a name="fb_share" 
        type="button" 
        share_url="<?php the_permalink(); ?>">facebook</a>
    </li>
    <li><g:plusone size="tall" href="<?php echo get_permalink(); ?>" annotation="none"></g:plusone></li>
    <li><script type="in/share" data-url="<?php the_permalink(); ?>"></script></li>
  </ul>
  <?php
  /**
   * Count buttons for the different websites
   *
   * @since 0.1
   * @author support@beansandpixels.com
   */
  ?>
  <ul id="LWLC_<?php the_ID(); ?>" class="LWLC_ShareCount">
    <?php if($LWLC_results['seperate']){ ?>
    <li class="LWLC_twitter">
      <a title="Share this link on Twitter">
        <?php echo $LWLC_counts['twitter']; ?>
      </a>
    </li>
    <li class="LWLC_facebook">
      <a title="Share this link on Facebook">
        <?php echo $LWLC_counts['facebook']; ?>
      </a>
    </li>
    <li class="LWLC_googleplus">
      <a title="Share this link on Google+">
        <?php echo $LWLC_counts['google+']; ?>
      </a>
    </li>
    <li class="LWLC_linkedin">
      <a title="Share this link on LinkedIn">
        <?php echo $LWLC_counts['linkedin']; ?>
      </a>
    </li>
    <? }if($LWLC_results['combined']){ ?>
    <li class="LWLC_combined"><a title="Total shares on the web"><?php echo $LWLC_counts['combined']; ?></a></li>
    <?php } ?>
  </ul>
</div>
<?php }
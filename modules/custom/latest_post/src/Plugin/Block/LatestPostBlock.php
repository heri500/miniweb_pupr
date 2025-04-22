<?php

namespace Drupal\latest_post\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;
use Drupal\Core\File\FileUrlGeneratorInterface;

/**
 * Provides a 'Frontpage Main' Block.
 *
 * @Block(
 *   id = "latest_post_block",
 *   admin_label = @Translation("Latest Post Block"),
 * )
 */
class LatestPostBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $latest_post = get_view_result_values('latest_post_frontpage', 'default');
    return [
      '#markup' => $this->create_html_block($latest_post),
    ];
  }
  protected function create_html_block($latest_post = null){
    $HtmlPage = '<section class="latest-posts section" id="latest-posts">
    <!-- Section Title -->
    <div class="container section-title aos-init aos-animate" data-aos="fade-up">
        <h2>
            Latest Posts
        </h2>
        <div>
            <span>Check Our</span> <span class="description-title">Latest Posts</span>
        </div>
    </div>
    <!-- End Section Title -->
    <div class="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">';
    foreach ($latest_post as $content) {
      $HtmlPage .= '<div class="col-lg-4">
                <article>
                    <div class="post-img">
                        <img class="img-fluid" src="'.$content['image'].'" alt="">
                    </div>
                    <p class="post-category">
                        '.$content['kategori'].'
                    </p>
                    <h2 class="title">
                        <a href="'.$content['node_link'].'">'.$content['title'].'</a>
                    </h2>
                    <div class="d-flex align-items-center">
                        <img class="img-fluid post-author-img flex-shrink-0" src="'.$content['author_image'].'" alt="">
                        <div class="post-meta">
                            <p class="post-author">
                                '.$content['author'].'
                            </p>
                            <p class="post-date">
                                <time datetime="'.$content['post_date'].'">'.$content['post_date'].'</time>
                            </p>
                        </div>
                    </div>
                </article>
            </div>
            <!-- End post list item -->';
    }
    $HtmlPage .= '</div>
    </div>
</section>';
    return $HtmlPage;
  }
}

<?php

namespace Drupal\frontpage_main\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Frontpage Main' Block.
 *
 * @Block(
 *   id = "frontpage_main_block",
 *   admin_label = @Translation("Frontpage Main Block"),
 * )
 */
class FrontpageMainBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array
  {
    $main = get_view_result_values('main_content_highlight', 'default');
    if (!empty($main[0])){
      $main = $main[0];
    }
    $second = get_view_result_values('second_content_highlight', 'default');
    if (!empty($second[0])){
      $second = $second[0];
    }
    $third = get_view_result_values('front_content_highlight', 'default');
    return [
      '#markup' => $this->create_html_block($main, $second, $third),
    ];
  }
  protected function create_html_block($main = null, $second = null, $third = null): string
  {
    $HtmlPage = '<section class="blog-hero section" id="blog-hero">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="blog-grid">
            <!-- Featured Post (Large) -->
            ';
    if (!is_null($main)) {
      $HtmlPage .= '<article class="blog-item featured" data-aos="fade-up">
                <img class="img-fluid" src="'.$main['image'].'" alt="Blog Image">
                <div class="blog-content">
                    <div class="post-meta">
                        <span class="date">'.$main['post_date'].'</span> <span class="category">'.$main['kategori'].'</span>
                    </div>
                    <h2 class="post-title">
                        <a href="'.$main['node_link'].'" title="'.$main['title'].'">'.$main['title'].'</a>
                    </h2>
                </div>
            </article>';
    }
    if (!is_null($second)) {
      $HtmlPage .= '<!-- End Featured Post --><!-- Regular Posts -->
            <article class="blog-item" data-aos="fade-up" data-aos-delay="100">
                <img class="img-fluid" src="'.$second['image'].'" alt="Blog Image">
                <div class="blog-content">
                    <div class="post-meta">
                        <span class="date">'.$second['post_date'].'</span> <span class="category">'.$second['kategori'].'</span>
                    </div>
                    <h3 class="post-title">
                        <a href="'.$second['node_link'].'" title="'.$second['title'].'">'.$second['title'].'</a>
                    </h3>
                </div>
            </article>
            <!-- End Blog Item -->';
    }
    if (!is_null($third)) {
      foreach ($third as $featured) {
        $HtmlPage .= '<article class="blog-item" data-aos="fade-up" data-aos-delay="200">
                <img class="img-fluid" src="'.$featured['image'].'" alt="Blog Image">
                <div class="blog-content">
                    <div class="post-meta">
                        <span class="date">'.$featured['post_date'].'</span> <span class="category">'.$featured['kategori'].'</span>
                    </div>
                    <h3 class="post-title">
                        <a href="'.$featured['node_link'].'" title="'.$featured['title'].'">'.$featured['title'].'</a>
                    </h3>
                </div>
            </article>
            <!-- End Blog Item -->';
      }
    }
    $HtmlPage .= '</div>
        <p>
            <a class="scroll-top d-flex align-items-center justify-content-center" href="#" id="scroll-top"><i class="bi bi-arrow-up-short"></i></a>
        </p>
    </div>
    <!-- Preloader -->
    <div id="preloader">
        &nbsp;
    </div>
</section>
<!-- /Blog Hero Section -->';
    return $HtmlPage;
  }
}

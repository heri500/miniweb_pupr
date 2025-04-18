<?php

declare(strict_types=1);

namespace Drupal\Tests\views\Functional\Wizard;

/**
 * Tests the ability of the views wizard to create views without a pager.
 *
 * @group views
 */
class PagerTest extends WizardTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Tests the pager option.
   */
  public function testPager(): void {
    // Create nodes, each with a different creation time so that we have
    // conditions that are meaningful for the use of a pager.
    $this->drupalCreateContentType(['type' => 'page']);
    for ($i = 0; $i < 12; $i++) {
      $this->drupalCreateNode(['created' => \Drupal::time()->getRequestTime() - $i]);
    }

    // Make a View that uses a pager.
    $path_with_pager = 'test-view-with-pager';
    $this->createViewAtPath($path_with_pager, TRUE);
    $this->drupalGet($path_with_pager);

    // This technique for finding the existence of a pager
    // matches that used in Drupal\views_ui\Tests\PreviewTest.php.
    $this->assertSession()->elementExists('xpath', '//ul[contains(@class, "pager__items")]/li');

    // Make a View that does not have a pager.
    $path_with_no_pager = 'test-view-without-pager';
    $this->createViewAtPath($path_with_no_pager, FALSE);
    $this->drupalGet($path_with_no_pager);
    $this->assertSession()->elementNotExists('xpath', '//ul[contains(@class, "pager__items")]/li');
  }

  /**
   * Create a simple View of nodes at a given path.
   *
   * @param string $path
   *   The path at which the View should be created.
   * @param bool $pager
   *   A boolean for whether the View created should use a pager.
   */
  protected function createViewAtPath($path, $pager = TRUE): void {
    $view = [];
    $view['label'] = $this->randomMachineName(16);
    $view['id'] = $this->randomMachineName(16);
    $view['show[sort]'] = 'node_field_data-created:ASC';
    $view['page[create]'] = 1;
    $view['page[title]'] = $this->randomMachineName(16);
    $view['page[path]'] = $path;
    $view['page[pager]'] = $pager;
    $this->drupalGet('admin/structure/views/add');
    $this->submitForm($view, 'Save and edit');
  }

}
